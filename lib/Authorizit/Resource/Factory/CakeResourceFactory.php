<?php
namespace Authorizit\Resource\Factory;

use Authorizit\Resource\CakeResource;

class CakeResourceFactory
{
    public function get($resource)
    {
        return new CakeResource($resource);
    }
}
