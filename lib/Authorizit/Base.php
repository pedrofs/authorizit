<?php

namespace Authorizit;

use Authorizit\Subject\Factory\ObjectSubjectFactory;
use Authorizit\Subject\SubjectInterface;

abstract class Base
{
    protected $user;
    protected $rules;
    protected $subjectFactory;

    public function __construct($user)
    {
        $this->user = $user;
        $this->subjectFactory = new ObjectSubjectFactory();
    }

    abstract public function authorizit();

    /**
     * Just rules getter
     *
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    public function write($action, $subject, $conditions = array())
    {
        $this->rules[] = new Rule($action, $subject, $conditions);
    }

    public function check($action, $subject)
    {
        $subject = $this->subjectFactory->get($subject);

        foreach ($this->getRules() as $rule) {

            if ($rule->match($action, $subject)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the subject factory
     *
     * @param SubjectInterface $subjectFactory
     * @return $this
     */
    public function setSubjectFactory(SubjectInterface $subjectFactory)
    {
        $this->subjectFactory = $subjectFactory;

        return $this;
    }

    /**
     * Get the subject factory
     *
     * @return ObjectSubjectFactory
     */
    public function getSubjectFactory()
    {
        return $this->subjectFactory;
    }
}
