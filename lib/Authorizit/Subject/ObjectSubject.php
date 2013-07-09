<?php
namespace Authorizit\Subject;

class ObjectSubject implements SubjectInterface
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

        return get_class($this->subject);
    }

    public function checkProperties($conditions)
    {
        foreach ($conditions as $attr => $value) {
            $reflectionSubject = new \ReflectionClass($this->subject);

            $reflectionProperty = $reflectionSubject->getProperty($attr);

            if ($reflectionProperty->isProtected() || $reflectionProperty->isPrivate()) {
                $changeModifier = true;
                $reflectionProperty->setAccessible(true);
            }

            $valueFromSubject = $reflectionProperty->getValue($this->subject);

            if ($changeModifier) {
                $reflectionProperty->setAccessible(false);   
            }

            if ($valueFromSubject != $value) {
                return false;
            }
        }

        return true;
    }
}