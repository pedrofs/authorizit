<?php

namespace Authorizit;

use Authorizit\Resource\ResourceFactoryInterface;
use Authorizit\Collection\RuleCollection;

abstract class Base
{
    protected $user;
    protected $rules;
    protected $resourceFactory;
    protected $modelAdapter;

    public function __construct($user, $resourceFactory)
    {
        $this->user = $user;
        $this->rules = new RuleCollection();
        $this->resourceFactory = $resourceFactory;
    }

    abstract public function init();

    /**
     * Just rules getter
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function write($action, $resource, $conditions = array())
    {
        $this->rules->add(new Rule($action, $resource, $conditions));
    }

    public function check($action, $resource)
    {
        $resource = $this->resourceFactory->get($resource);

        foreach ($this->getRules() as $rule) {
            if ($rule->match($action, $resource)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the resource factory
     *
     * @param ResourceFactoryInterface $resourceFactory
     * @return $this
     */
    public function setResourceFactory(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;

        return $this;
    }

    /**
     * Get the resource factory
     *
     * @return ObjectResourceFactory
     */
    public function getResourceFactory()
    {
        return $this->resourceFactory;
    }
}
