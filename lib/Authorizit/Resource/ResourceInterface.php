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

namespace Authorizit\Resource;

/**
 * The ResourceInterface will be implemented by your concrete Resource class.
 * The concrete Resource class will know how to check the properties of a $resource against $conditions
 * and will also know how to retrieve the class for a $resource.
 *
 * @author Pedro Fernandes Steimbruch <pedrofsteimbruch@gmail.com>
 */
interface ResourceInterface
{
    /**
     * Constructor
     *
     * @param mixed $resource Your resource domain object.
     */
    public function __construct($resource);

    /**
     * getClass will implement how to return the class for a given $resource. 
     *
     * @return string The class of your resource domain object.
     */
    public function getClass();

    /**
     * checkProperties will know how to check the given conditions against the given $resource.
     *
     * @param array $conditions
     */
    public function checkProperties($conditions);

    /**
     * @return mixed
     */
    public function getResource();
}
