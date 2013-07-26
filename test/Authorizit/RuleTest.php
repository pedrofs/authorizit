<?php
namespace Authorizit;

use Authorizit\Rule;
use Authorizit\Subject\SubjectInterface;

class SomeClass { public function doSomething() {}}

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subjectStub = $this->getMock('Authorizit\Subject\SubjectInterface');

        $this->subjectStub
             ->expects($this->any())
             ->method('getClass')
             ->will($this->returnValue('SubjectStub'));

        $this->subjectStub
            ->expects($this->any())
            ->method('checkProperties')
            ->will($this->returnValue(true));
    }

    public function testMatchRuleWithoutConditionsPassingObjectToCheck()
    {
        $ruleA = new Rule('create', 'SubjectStub');
        $ruleB = new Rule('read', 'SubjectStub');
        $ruleC = new Rule('update', 'SubjectStub');
        $ruleD = new Rule('delete', 'SubjectStub');

        $this->assertTrue($ruleA->match('create', $this->subjectStub));
        $this->assertTrue($ruleB->match('read', $this->subjectStub));
        $this->assertTrue($ruleC->match('update', $this->subjectStub));
        $this->assertTrue($ruleD->match('delete', $this->subjectStub));

        $rule = new Rule('manage', 'SubjectStub');

        $this->assertTrue($rule->match('create', $this->subjectStub));
        $this->assertTrue($rule->match('update', $this->subjectStub));
        $this->assertTrue($rule->match('read', $this->subjectStub));
        $this->assertTrue($rule->match('delete', $this->subjectStub));
    }

    public function testMatchRuleWithoutRightPrivilegiesPassingObjectToCheck()
    {
        $rule = new Rule('read', 'Testing');

        $this->assertFalse($rule->match('create', $this->subjectStub));
        $this->assertFalse($rule->match('update', $this->subjectStub));
        $this->assertFalse($rule->match('delete', $this->subjectStub));

        $rule = new Rule('create', 'Testing');

        $this->assertFalse($rule->match('read', $this->subjectStub));
        $this->assertFalse($rule->match('update', $this->subjectStub));
        $this->assertFalse($rule->match('delete', $this->subjectStub));

        $rule = new Rule('update', 'Testing');

        $this->assertFalse($rule->match('create', $this->subjectStub));
        $this->assertFalse($rule->match('update', $this->subjectStub));
        $this->assertFalse($rule->match('delete', $this->subjectStub));

        $rule = new Rule('delete', 'Testing');

        $this->assertFalse($rule->match('create', $this->subjectStub));
        $this->assertFalse($rule->match('update', $this->subjectStub));
        $this->assertFalse($rule->match('read', $this->subjectStub));
    }

    public function testMatchRuleWithoutConditionsAllSubjectsPrivilegiesUsingObjectToCheck()
    {
        $ruleA = new Rule('create', 'all');
        $ruleB = new Rule('read', 'all');
        $ruleC = new Rule('update', 'all');
        $ruleD = new Rule('delete', 'all');

        $this->assertTrue($ruleA->match('create', $this->subjectStub));
        $this->assertTrue($ruleB->match('read', $this->subjectStub));
        $this->assertTrue($ruleC->match('update', $this->subjectStub));
        $this->assertTrue($ruleD->match('delete', $this->subjectStub));
    }

    public function testMatchWithAllSubjectsAndManagePrivilegiesUsingObjectToCheck()
    {
        $rule = new Rule('manage', 'all');

        $this->assertTrue($rule->match('create', $this->subjectStub));
        $this->assertTrue($rule->match('read', $this->subjectStub));
        $this->assertTrue($rule->match('update', $this->subjectStub));
        $this->assertTrue($rule->match('delete', $this->subjectStub));
    }

    public function testMatchRuleWithRightConditions()
    {
        $ruleA = new Rule('create', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleB = new Rule('read', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleC = new Rule('update', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleD = new Rule('delete', 'SubjectStub', array('userId' => 1, 'foo' => 1));

        $this->assertTrue($ruleA->match('create', $this->subjectStub));
        $this->assertTrue($ruleB->match('read', $this->subjectStub));
        $this->assertTrue($ruleC->match('update', $this->subjectStub));
        $this->assertTrue($ruleD->match('delete', $this->subjectStub));
    }

    public function testMatchRuleWithWrongConditions()
    {
        $subjectStub = $this->getMock('Authorizit\Subject\SubjectInterface');

        $subjectStub
             ->expects($this->any())
             ->method('getClass')
             ->will($this->returnValue('SubjectStub'));

        $ruleA = new Rule('create', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleB = new Rule('read', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleC = new Rule('update', 'SubjectStub', array('userId' => 1, 'foo' => 1));
        $ruleD = new Rule('delete', 'SubjectStub', array('userId' => 1, 'foo' => 1));

        $this->assertFalse($ruleA->match('create', $subjectStub));
        $this->assertFalse($ruleB->match('read', $subjectStub));
        $this->assertFalse($ruleC->match('update', $subjectStub));
        $this->assertFalse($ruleD->match('delete', $subjectStub));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage You must provide a valid action. Availables: create, read, update, delete, manage.
     */
    public function testExceptionWhenInvalidActionProvided()
    {
        $rule = new Rule('nothatime', 'Authorizit\Testing', array('userId' => 1, 'foo' => 1));
    }
}
