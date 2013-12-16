<?php
namespace Authorizit;

class Rule
{
    const MANAGE_ACTION = 'manage';
    const ALL_RESOURCES  = 'all';

    private $resource;
    private $action;
    private $conditions;
    private $availableActions = array(
        'create',
        'read',
        'update',
        'delete',
        'manage',
    );

    public function __construct($action, $resource, $conditions = array())
    {
        if (!in_array($action, $this->availableActions)) {
            throw new \InvalidArgumentException(
                "You must provide a valid action. Availables: " .
                implode($this->availableActions, ', ') . "."
            );
        }

        $this->resource   = $resource;
        $this->action     = $action;
        $this->conditions = $conditions;
    }

    /**
     * Validate rule against a given $action and $resource also using conditions.
     * 
     * @param string $action
     * @param ResourceInterface $resource
     * @return bool
     */
    public function match($action, $resource)
    {
        return $this->softMatch($action, $resource) &&
                $this->matchResourceConditions($resource);
    }

    /**
     * Validate rule using just $action and $resource. Ignoring conditions.
     *
     * @param string $action
     * @param string $resource
     * @return bool
     */
    public function softMatch($action, $resource)
    {
        return $this->matchAction($action) &&
            $this->matchResourceClass($resource);
    }

    public function hasConditions()
    {
        return is_array($this->conditions) && !empty($this->conditions);
    }

    /**
     * Returns rule conditions
     *
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * Match the rule action against a given one.
     *
     * @param string $action
     * @return bool
     */
    private function matchAction($action)
    {
        return $this->action == self::MANAGE_ACTION ||
            $this->action == $action;
    }

    /**
     * Match the rule resource class against a given one.
     *
     * @param ResourceInterface $resource
     * @return bool
     */
    private function matchResourceClass($resource)
    {
        $resourceClass = $this->getResourceClass($resource);

        return $this->resource == self::ALL_RESOURCES ||
            $this->resource == $resourceClass;
    }

    /**
     * Match the rule resource conditions against a given resource.
     *
     * @param ResourceInterface $resource
     * @return bool
     */
    private function matchResourceConditions($resource)
    {
        $conditions = $this->conditions;

        return is_callable($conditions) ? $conditions($resource)
            : $resource->checkProperties($conditions);
    }

    /**
     * Get the resource class from Resource.
     *
     * @param ResourceInterface $resource
     * @return string
     */
    private function getResourceClass($resource)
    {
        return $resource->getClass();
    }
}
