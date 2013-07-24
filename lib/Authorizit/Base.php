<?php
namespace Authorizit;

use Authorizit\Subject\Factory\ObjectSubjectFactory;

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

    public function write($action, $subject, $conditions = array())
    {
        $this->rules[] = new Rule($action, $subject, $conditions);
    }

    public function check($action, $subject)
    {
        $subject = $this->subjectFactory->get($subject);

        foreach ($this->rules as $r) {
            if ($r->match($action, $subject)) {
                return true;
            }
        }

        return false;
    }

    public function setSubjectFactory($subjectFactory)
    {
        $this->subjectFactory = $subjectFactory;
    }
}
