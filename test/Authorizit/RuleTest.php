<?php
namespace Authorizit;

use Authorizit\Rule;
use Authorizit\Resource\ResourceInterface;

class SomeClass { public function doSomething() {}}

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resourceStub = $this->getMock('Authorizit\Resource\ResourceInterface');

        $this->resourceStub
             ->expects($this->any())
             ->method('getClass')
             ->will($this->returnValue('ResourceStub'));

        $this->resourceStub
            ->expects($this->any())
            ->method('checkProperties')
            ->will($this->returnValue(true));
    }

    public function testMatchRuleWithoutConditionsPassingObjectToCheck()
    {
        $ruleA = new Rule('create', 'ResourceStub');
        $ruleB = new Rule('read', 'ResourceStub');
        $ruleC = new Rule('update', 'ResourceStub');
        $ruleD = new Rule('delete', 'ResourceStub');

        $this->assertTrue($ruleA->match('create', $this->resourceStub));
        $this->assertTrue($ruleB->match('read', $this->resourceStub));
        $this->assertTrue($ruleC->match('update', $this->resourceStub));
        $this->assertTrue($ruleD->match('delete', $this->resourceStub));

        $rule = new Rule('manage', 'ResourceStub');

        $this->assertTrue($rule->match('create', $this->resourceStub));
        $this->assertTrue($rule->match('update', $this->resourceStub));
        $this->assertTrue($rule->match('read', $this->resourceStub));
        $this->assertTrue($rule->match('delete', $this->resourceStub));
    }

    public function testMatchRuleWithoutRightPrivilegiesPassingObjectToCheck()
    {
        $rule = new Rule('read', 'Testing');

        $this->assertFalse($rule->match('create', $this->resourceStub));
        $this->assertFalse($rule->match('update', $this->resourceStub));
        $this->assertFalse($rule->match('delete', $this->resourceStub));

        $rule = new Rule('create', 'Testing');

        $this->assertFalse($rule->match('read', $this->resourceStub));
        $this->assertFalse($rule->match('update', $this->resourceStub));
        $this->assertFalse($rule->match('delete', $this->resourceStub));

        $rule = new Rule('update', 'Testing');

        $this->assertFalse($rule->match('create', $this->resourceStub));
        $this->assertFalse($rule->match('update', $this->resourceStub));
        $this->assertFalse($rule->match('delete', $this->resourceStub));

        $rule = new Rule('delete', 'Testing');

        $this->assertFalse($rule->match('create', $this->resourceStub));
        $this->assertFalse($rule->match('update', $this->resourceStub));
        $this->assertFalse($rule->match('read', $this->resourceStub));
    }

    public function testMatchRuleWithoutConditionsAllResourcesPrivilegiesUsingObjectToCheck()
    {
        $ruleA = new Rule('create', 'all');
        $ruleB = new Rule('read', 'all');
        $ruleC = new Rule('update', 'all');
        $ruleD = new Rule('delete', 'all');

        $this->assertTrue($ruleA->match('create', $this->resourceStub));
        $this->assertTrue($ruleB->match('read', $this->resourceStub));
        $this->assertTrue($ruleC->match('update', $this->resourceStub));
        $this->assertTrue($ruleD->match('delete', $this->resourceStub));
    }

    public function testMatchWithAllResourcesAndManagePrivilegiesUsingObjectToCheck()
    {
        $rule = new Rule('manage', 'all');

        $this->assertTrue($rule->match('create', $this->resourceStub));
        $this->assertTrue($rule->match('read', $this->resourceStub));
        $this->assertTrue($rule->match('update', $this->resourceStub));
        $this->assertTrue($rule->match('delete', $this->resourceStub));
    }

    public function testMatchRuleWithRightConditions()
    {
        $ruleA = new Rule('create', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleB = new Rule('read', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleC = new Rule('update', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleD = new Rule('delete', 'ResourceStub', array('userId' => 1, 'foo' => 1));

        $this->assertTrue($ruleA->match('create', $this->resourceStub));
        $this->assertTrue($ruleB->match('read', $this->resourceStub));
        $this->assertTrue($ruleC->match('update', $this->resourceStub));
        $this->assertTrue($ruleD->match('delete', $this->resourceStub));
    }

    public function testMatchRuleWithWrongConditions()
    {
        $resourceStub = $this->getMock('Authorizit\Resource\ResourceInterface');

        $resourceStub
             ->expects($this->any())
             ->method('getClass')
             ->will($this->returnValue('ResourceStub'));

        $ruleA = new Rule('create', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleB = new Rule('read', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleC = new Rule('update', 'ResourceStub', array('userId' => 1, 'foo' => 1));
        $ruleD = new Rule('delete', 'ResourceStub', array('userId' => 1, 'foo' => 1));

        $this->assertFalse($ruleA->match('create', $resourceStub));
        $this->assertFalse($ruleB->match('read', $resourceStub));
        $this->assertFalse($ruleC->match('update', $resourceStub));
        $this->assertFalse($ruleD->match('delete', $resourceStub));
    }

    public function testMatchRuleWithCallableConditions()
    {
        $resourceStub = $this->getMock('Authorizit\Resource\ResourceInterface');

        $resourceStub
             ->expects($this->any())
             ->method('getClass')
             ->will($this->returnValue('ResourceStub'));

        $ruleA = new Rule('create', 'ResourceStub', function ($stub) {return false;});
        $ruleB = new Rule('read', 'ResourceStub', function ($stub) {return false;});

        $ruleC = new Rule('update', 'ResourceStub', function ($stub) {return true;});
        $ruleD = new Rule('delete', 'ResourceStub', function ($stub) {return true;});

        $this->assertFalse($ruleA->match('create', $resourceStub));
        $this->assertFalse($ruleB->match('read', $resourceStub));

        $this->assertTrue($ruleC->match('update', $resourceStub));
        $this->assertTrue($ruleD->match('delete', $resourceStub));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage You must provide a valid action. Availables: create, read, update, delete, manage.
     */
    public function testExceptionWhenInvalidActionProvided()
    {
        $rule = new Rule('nothatime', 'Authorizit\Testing', array('userId' => 1, 'foo' => 1));
    }

    public function testRuleHasConditions()
    {
        $ruleA = new Rule('read', 'ResourceStub', array('id' => 1));
        $ruleB = new Rule('read', 'ResourceStub');
        $ruleC = new Rule('read', 'ResourceStub', function () {});

        $this->assertTrue($ruleA->hasConditions());
        $this->assertFalse($ruleB->hasConditions());
        $this->assertFalse($ruleC->hasConditions());
    }
}
