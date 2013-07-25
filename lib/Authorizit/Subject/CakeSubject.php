<?php
namespace Authorizit\Subject;

class CakeSubject implements SubjectInterface
{
    private $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function getClass()
    {
        if (is_string($this->subject)) {
            return $this->subject;
        }

        return $this->subject->alias;
    }

    public function checkProperties($conditions)
    {
        $data = $this->read();
        foreach ($conditions as $attr => $value) {
            if ($data[$attr] != $value) {
                return false;
            }
        }

        return true;
    }
}
