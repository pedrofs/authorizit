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

    public function __construct($user, $resourceFactory, $modelAdapter = null)
    {
        $this->user            = $user;
        $this->rules           = new RuleCollection();
        $this->resourceFactory = $resourceFactory;
        $this->modelAdapter    = $modelAdapter;
    }

    /**
     * Abstract function that should be implemented in order to add
     * rules for this authorizit instance.
     */
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

    /**
     * Get the resource factory.
     *
     * @return ObjectResourceFactory
     */
    public function getResourceFactory()
    {
        return $this->resourceFactory;
    }

    /**
     * Set the resource factory.
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
     * Get the modelAdapter class.
     *
     * @return ModelAdapterInterface
     */
    public function getModelAdapter()
    {
        return $this->modelAdapter;
    }

    /**
     * Set the modelAdapter class.
     *
     * @param ModelAdapterInterface $modelAdapter
     * @return $this
     */
    public function setModelAdapter($modelAdapter)
    {
        $this->modelAdapter = $modelAdapter;

        return $this;
    }

    /**
     * Add a rule to this authorizit instance.
     *
     * @param string $action
     * @param string $resource
     * @param array  $conditions
     * @return null
     */
    public function write($action, $resourceClass, $conditions = array())
    {
        $this->rules->add(new Rule($action, $resourceClass, $conditions));
    }

    /**
     * Check the permission for given action and resource
     *
     * @param string $action
     * @param mixed $resource
     * @return bool
     */
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
     * Load resources based on authorizit rules.
     *
     * @param string $action
     * @param string $resourceClass
     * @return array
     */
    public function loadResources($resourceClass)
    {
        if (!$this->modelAdapter) {
            throw new \BadMethodCallException(
                "\$modelAdapter not set. " .
                "You should set one in order to retrieve authorized resources. " .
                "See ModelAdapterInterface."
            );
        }

        $rules = $this->getRelevantRules('read', $resourceClass);

        return $this->modelAdapter->loadResources($rules);
    }

    /**
     * Get rules that fit the $action and $resourceClass (do not check anything about conditions).
     *
     * @param string $action
     * @param string $resourceClass
     * @return array
     */
    public function getRelevantRules($action, $resourceClass)
    {
        $rules = array();

        foreach ($this->getRules() as $rule) {
            $resource = $this->resourceFactory->get($resourceClass);

            if ($rule->softMatch($action, $resource)) {
                $rules[] = $rule;
            }
        }

        return $rules;
    }
}
