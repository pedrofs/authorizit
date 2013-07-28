<?php
namespace Authorizit\Resource\Factory;

use Authorizit\Resource\ObjectResource;

class ObjectResourceFactory
{
    public function get($resource)
    {
        return new ObjectResource($resource);
    }
}
