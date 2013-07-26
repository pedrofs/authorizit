<?php
namespace Authorizit;

class Rule
{
    const MANAGE_ACTION = 'manage';
    const ALL_SUBJECTS  = 'all';

    private $subject;
    private $action;
    private $conditions;
    private $availableActions = array(
        'create',
        'read',
        'update',
        'delete',
        'manage',
    );

    public function __construct($action, $subject, $conditions = array())
    {
        if (!in_array($action, $this->availableActions)) {
            throw new \InvalidArgumentException(
                "You must provide a valid action. Availables: " .
                implode($this->availableActions, ', ') . "."
            );
        }

        $this->subject    = $subject;
        $this->action     = $action;
        $this->conditions = $conditions;
    }

    public function match($action, $subject)
    {
        return $this->matchAction($action) &&
            $this->matchSubject($subject);
    }

    public function matchAction($action)
    {
        return $this->action == self::MANAGE_ACTION ||
            $this->action == $action;
    }

    public function matchSubject($subject)
    {
        return $this->matchSubjectClass($subject) &&
            $this->matchSubjectConditions($subject);
    }

    private function matchSubjectClass($subject)
    {
        $subjectClass = $this->getSubjectClass($subject);

        return $this->subject == self::ALL_SUBJECTS ||
            $this->subject == $subjectClass;
    }

    private function matchSubjectConditions($subject)
    {
        return $subject->checkProperties($this->conditions);
    }

    private function getSubjectClass($subject)
    {
        return $subject->getClass();
    }
}
