<?php
/**
 * Authorizit - resource focused authorization library
 * https://github.com/pedrofs/authorizit
 *
 * Licensed under UNLICENSE
 * For full copyright and license information, please see the UNLICENSE.txt
 * Check http://unlicense.org/
 *
 * @link          https://github.com/pedrofs/authorizit
 * @license       http://unlicense.org/ Unlicense Yourself: Set Your Code Free
 */

namespace Authorizit;

use Authorizit\Resource\AbstractResourceFactory;
use Authorizit\Collection\RuleCollection;
use Authorizit\ModelAdapter\ModelAdapterInterface;

/**
 * This is the abstract Base class that you will use to inherit your Authorizit class and
 * define your rules using Base#write, check
 *
 * @author Pedro Fernandes Steimbruch <pedrofsteimbruch@gmail.com>
 */
abstract class Base
{
    /**
     * User object of your domain
     *
     * @var mixed
     */
    protected $user;

    /**
     * RuleCollection object
     *
     * @var RuleCollection
     */
    protected $rules;

    /**
     * ResourceFactory object
     *
     * @var AbstractResourceFactory
     */
    protected $resourceFactory;

    /**
     * ModelAdapter object
     *
     * @var ModelAdapterInterface
     */
    protected $modelAdapter;


    /**
     * Constructor
     *
     * @param mixed $user Your user domain object
     * @param AbstractResourceFactory $resourceFactory The factory for your resource domain object
     * @param ModelAdapterInterface $modelAdapter The model adapter of your domain for retrieving the resources
     */
    public function __construct($user, $resourceFactory, ModelAdapterInterface $modelAdapter = null)
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
     * @return RuleCollection
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return ObjectResourceFactory
     */
    public function getResourceFactory()
    {
        return $this->resourceFactory;
    }

    /**
     * @param ResourceFactoryInterface $resourceFactory
     * @return $this
     */
    public function setResourceFactory($resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;

        return $this;
    }

    /**
     * @return ModelAdapterInterface
     */
    public function getModelAdapter()
    {
        return $this->modelAdapter;
    }

    /**
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

        $relevantRules = $this->getRelevantRules('read', $resourceClass);

        $rules = $this->selectRuleWithConditions($relevantRules);

        return $this->modelAdapter->loadResources($rules);
    }

    /**
     * Get rules that are relevant to be used in the loadResources. That means that the rule
     * should have conditions in order to be used in the query.
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

    private function selectRuleWithConditions($rules)
    {
        $rulesWithConditions = array();
        foreach ($rules as $rule) {
            if ($rule->hasConditions()) {
                $rulesWithConditions[] = $rule;
            }
        }

        return $rulesWithConditions;
    }
}
