<?php
namespace Authorizit\Resource\Factory;

use Authorizit\Resource\StringResource;

abstract class AbstractResourceFactory
{
    public function get($resource)
    {
    	if (is_string($resource)) {
    		return new StringResource($resource);
    	}

    	return $this->getInstance($resource);
    }

    abstract public function getInstance($resource);
}