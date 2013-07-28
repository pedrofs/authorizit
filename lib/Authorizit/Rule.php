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

        $this->resource    = $resource;
        $this->action     = $action;
        $this->conditions = $conditions;
    }

    public function match($action, $resource)
    {
        return $this->matchAction($action) &&
                $this->matchResourceClass($resource) &&
                $this->matchResourceConditions($resource);
    }

    public function matchAction($action)
    {
        return $this->action == self::MANAGE_ACTION ||
            $this->action == $action;
    }

    private function matchResourceClass($resource)
    {
        $resourceClass = $this->getResourceClass($resource);

        return $this->resource == self::ALL_RESOURCES ||
            $this->resource == $resourceClass;
    }

    private function matchResourceConditions($resource)
    {
        return $resource->checkProperties($this->conditions);
    }

    private function getResourceClass($resource)
    {
        return $resource->getClass();
    }
}
