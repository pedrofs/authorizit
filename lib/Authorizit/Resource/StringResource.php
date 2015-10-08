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
 * The StringResource is used when you are checking the permissions using a string instead of a object.
 * For instance: Base#check('read', 'Post');
 * This class is used internally.
 *
 * @author Pedro Fernandes Steimbruch <pedrofsteimbruch@gmail.com>
 */
class StringResource implements ResourceInterface
{
    /**
     * @var string
     */
	private $resource;

	/**
	 * Constructor
	 *
	 * @param string $resource
	 */
	public function __construct($resource)
	{
		$this->resource = $resource;
	}

	/**
	 * Constructor
	 *
	 * @return $string The $resource itself is a string
	 */
	public function getClass()
	{
		return $this->resource;
	}

	/**
	 * @param array $conditions Useless, only to fit the interface.
	 * @return true Always returns true, there is no properties to check
	 */
	public function checkProperties($conditions)
	{
		return true;
	}

    /**
     * @return string
     */
	public function getResource()
	{
		return $this->resource;
	}
}