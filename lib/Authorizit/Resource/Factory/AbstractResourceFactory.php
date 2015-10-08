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

namespace Authorizit\Resource\Factory;

use Authorizit\Resource\StringResource;

/**
 * This is the abstract factory for creating the right $resource object of your domain.
 *
 * @author Pedro Fernandes Steimbruch <pedrofsteimbruch@gmail.com>
 */
abstract class AbstractResourceFactory
{
    /**
     * The get method will create the right Resource class.
     *
     * @return ResourceInterface
     */
    public function get($resource)
    {
    	if (is_string($resource)) {
    		return new StringResource($resource);
    	}

    	return $this->getInstance($resource);
    }

    /**
     * The getInstance method will be implemented by the concrete factory class.
     *
     * @return ResourceInterface
     */
    abstract public function getInstance($resource);
}