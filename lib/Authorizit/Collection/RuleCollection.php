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
     * Adds/sets an rule in the collection at the index / with the specified key.
     *
     * @param mixed $key
     * @param Rule $rule
     */
    public function set($key, Rule $rule)
    {
        $this->rules[$key] = $rule;
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
     * Gets the element with the given key/index.
     *
     * @param mixed $key The key.
     * @return mixed The rule or NULL, if no element exists for the given key.
     */
    public function get($key)
    {
        return isset($this->rules[$key]) ? $this->rules[$key] : false;
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
     * Removes an rule with a specific key/index from the collection.
     *
     * @param mixed $key
     * @return mixed The removed rule or NULL, if no element exists for the given key.
     */
    public function remove($key)
    {
        if (isset($this->rules[$key])) {
            $removed = $this->rules[$key];
            unset($this->rules[$key]);

            return $removed;
        }

        return null;
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param Rule $rule The rule to remove.
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRule(Rule $rule)
    {
        $key = array_search($rule, $this->rules, true);

        if ($key !== false) {
            unset($this->rules[$key]);

            return true;
        }

        return false;
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
     * Checks whether the collection is empty.
     *
     * Note: This is preferable over count() == 0.
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        return ! $this->rules;
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

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * Clears the collection.
     */
    public function clear()
    {
        $this->rules = array();
    }

    /**
     * Extract a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->rules, $offset, $length, true);
    }

}
