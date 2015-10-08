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

namespace Authorizit\ModelAdapter;

/**
 * The ModelAdapterInterface is an interface to be implemented by a concrete class in charge of
 * retrieving the right resources from the database taking in account the $rules conditions.
 *
 * @author Pedro Fernandes Steimbruch <pedrofsteimbruch@gmail.com>
 */
interface ModelAdapterInterface
{
	public function loadResources($rules);
}