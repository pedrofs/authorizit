<?php

namespace Authorizit\Collection;

use Authorizit\Rule;

/**
 * An RuleCollection is a Collection implementation that wraps a regular PHP array.
 *
 * @author  Ricardo Heck <ricardoh3ck@gmail.com>
 */
class RuleCollection implements \IteratorAggregate, \Countable
{
    /**
     * An ArrayIterator containing the entries of this collection.
     *
     * @var ArrayIterator
     */
    private $rules;

    /**
     * Initializes a new RuleCollection.
     */
    public function __construct(array $rules = array())
    {
        $this->rules = $rules;
    }

    /**
     * Gets the PHP array representation of this collection.
     *
     * @return array The PHP array representation of this collection.
     */
    public function toArray()
    {
        return $this->rules;
    }

    /**
     * Adds an rule to the collection.
     *
     * @param Rule $rule
     * @return boolean Always TRUE.
     */
    public function add(Rule $rule)
    {
        $this->rules[] = $rule;

        return true;
    }

    /**
     * Sets the internal iterator to the first rule in the collection and
     * returns it.
     *
     * @return Rule
     */
    public function first()
    {
        return reset($this->rules);
    }

    /**
     * Returns the number of rules in the collection.
     *
     * @return integer The number of rules in the collection.
     */
    public function count()
    {
        return count($this->rules);
    }

    /**
     * Gets an iterator for iterating over the rules in the collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->rules);
    }

}
