<?php

namespace Authorizit;

use Authorizit\Base;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $resourceFactoryMock = $this->getMock(
            'Authorizit\Resource\ResourceFactoryInterface'
        );

        $baseMock = $this->getMockForAbstractClass(
            'Authorizit\Base',
            array(
                array('id' => 1),
                $resourceFactoryMock
            ),
            'ConcreteBaseMock'
        );

        $this->assertInstanceOf('Authorizit\Base', $baseMock);
    }

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

        $this->assertFalse(
            $baseMock->check('update', 'Authorizit\Resource\ResourceInterface')
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

    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testLoadResources($baseMock)
    {
        $adapterModelMock = $this->getMock(
            'Authorizit\ModelAdapter\ModelAdapterInterface'
        );

        $adapterModelMock->expects($this->any())
            ->method('loadResources')
            ->will($this->returnValue(array('id' => 1)));

        $baseMock->setModelAdapter($adapterModelMock);

        $this->assertCount(1, $baseMock->loadResources('action', 'TestResource'));
    }

    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testGetRelevantRules($baseMock)
    {
        $baseMock->write('create', 'Authorizit\Resource\ResourceInterface');
        $baseMock->write('update', 'Authorizit\Resource\ResourceInterface');
        $baseMock->write('create', 'Authorizit\Resource\ResourceInterface', function () {});

        $this->assertCount(2, $baseMock->getRelevantRules('create', 'Authorizit\Resource\ResourceInterface'));
    }

    /**
     * @dataProvider getConcreteBaseMock
     * @expectedException BadMethodCallException
     */
    public function testExceptionLoadResources($baseMock)
    {
        $this->assertCount(1, $baseMock->loadResources('action', 'TestResource'));
    }

    /**
     * @dataProvider getConcreteBaseMock
     */
    public function testSetModelAdapter($baseMock)
    {
        $modelAdapterMock = $this->getMock(
            'Authorizit\ModelAdapter\ModelAdapterInterface'
        );

        $baseMock->setModelAdapter($modelAdapterMock);

        $this->assertInstanceOf(
            'Authorizit\ModelAdapter\ModelAdapterInterface',
            $baseMock->getModelAdapter()
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
