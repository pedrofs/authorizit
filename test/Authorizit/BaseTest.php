<?php

namespace Authorizit;

use Authorizit\Base;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testWrite($baseMock)
    {
        $this->assertCount(0, $baseMock->getRules());

        $baseMock->write('create', 'Resource', array('attr' => 1));

        $rules = $baseMock->getRules();

        $this->assertCount(1, $rules);
        $this->assertInstanceOf('Authorizit\Rule', $rules->first());
    }

    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testCheck($baseMock)
    {
        $baseMock->write(
            'create',
            'Authorizit\Resource\ResourceInterface',
            array('userId' => 1)
        );

        $this->assertTrue(
            $baseMock->check('create', 'Authorizit\Resource\ResourceInterface')
        );
    }

    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testSetResourceFactory($baseMock)
    {
        $resourceFactoryMock = $this->getMock(
            'Authorizit\Resource\ResourceFactoryInterface'
        );

        $baseMock->setResourceFactory($resourceFactoryMock);

        $this->assertInstanceOf(
            'Authorizit\Resource\ResourceFactoryInterface',
            $baseMock->getResourceFactory()
        );
    }

    public function getConcreteBaseMock()
    {
        $resourceMock = $this->getResourceMock();

        $resourceFactoryMock = $this->getMock(
            'Authorizit\Resource\Factory\ResourceFactoryInterface'
        );

        $resourceFactoryMock->expects($this->any())
            ->method('get')
            ->will($this->returnValue($resourceMock));

        $baseMock = $this->getMock(
            'Authorizit\Base',
            array('init'),
            array(array('id' => 1), $resourceFactoryMock)
        );

        return array(
            array($baseMock)
        );
    }

    private function getResourceMock()
    {
        $resourceMock = $this->getMock('Authorizit\Resource\ResourceInterface');
        $resourceMock->expects($this->any())
            ->method('getClass')
            ->will($this->returnValue('Authorizit\Resource\ResourceInterface'));

        $resourceMock->expects($this->at(0))
            ->method('checkProperties')
            ->will($this->returnValue(true));

        $resourceMock->expects($this->at(1))
            ->method('checkProperties')
            ->will($this->returnValue(true));

        $resourceMock->expects($this->at(2))
            ->method('checkProperties')
            ->will($this->returnValue(false));

        return $resourceMock;
    }
}
