<?php

namespace Authorizit;

use Authorizit\Base;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $baseMock = $this->getMockForAbstractClass(
            'Authorizit\Base',
            array('AuthorizitUser'),
            'ConcreteBaseMock'
        );

        $this->assertInstanceOf('Authorizit\Base', $baseMock);

        return array(
            array($baseMock)
        );
    }

    /**
     * @dataProvider testConstructor
     * @param Base $baseMock
     */
    public function testWrite($baseMock)
    {
        $this->assertCount(0, $baseMock->getRules());

        $baseMock->write('create', 'target', array('AuthorizitCondition'));

        $rules = $baseMock->getRules();

        $this->assertCount(1, $rules);
        $this->assertInstanceOf('Authorizit\Rule', $rules->first());
    }

    /**
     * @dataProvider providerForTestCheck
     * @param \Authorizit\Base $baseMock
     */
    public function testCheck($baseMock)
    {
        $baseMock->write(
            'create',
            'Authorizit\Subject\ObjectSubject',
            array('subject' => 'AuthorizitCondition')
        );

        $this->assertTrue(
            $baseMock->check('create', 'Authorizit\Subject\ObjectSubject')
        );

        $this->assertFalse(
            $baseMock->check('create', 'Authorizit\Subject\ObjectSubject')
        );
    }

    /**
     * @dataProvider testConstructor
     * @param Base $baseMock
     */
    public function testSetSubjectFactory($baseMock)
    {
        $subjectFactoryMock = $this->getMock(
            'Authorizit\Subject\SubjectFactoryInterface'
        );

        $baseMock->setSubjectFactory($subjectFactoryMock);

        $this->assertInstanceOf(
            'Authorizit\Subject\SubjectFactoryInterface',
            $baseMock->getSubjectFactory()
        );
    }

    public function providerForTestCheck()
    {
        $rulesMock = $this->getMock(
            'Authorizit\Rule',
            array('match'),
            array('create', 'Authorizit\Subject\ObjectSubject')
        );

        $rulesMock->expects($this->at(0))
            ->method('match')
            ->will($this->returnValue(true));

        $rulesMock->expects($this->at(1))
            ->method('match')
            ->will($this->returnValue(false));

        $baseMock = $this->getMock(
            'Authorizit\Base',
            array('getRules', 'authorizit'),
            array('AuthorizitUser')
        );

        $baseMock->expects($this->any())
            ->method('getRules')
            ->will($this->returnValue(array($rulesMock)));

        return array(
            array($baseMock)
        );
    }
}
