<?php
namespace Authorizit\Subject;

class ObjectSubject implements SubjectInterface
{
    const PUBLIC_MODIFIER     = 'public';
    const PROTECTED_MODIFIER  = 'protected';
    const PRIVATE_MODIFIER    = 'private';

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
        if (is_string($this->subject)) {
            return true;
        }

        foreach ($conditions as $attr => $value) {
            if ($this->getValueFromSubject() != $value) {
                return false;
            }
        }

        return true;
    }

    private function getValueFromSubject()
    {
        $reflectionSubject = new \ReflectionClass($this->subject);

        $reflectionProperty = $reflectionSubject->getProperty($attr);

        $modifier = $this->getPropertyModifier($reflectionProperty);

        $reflectionProperty->setAccessible(true);

        $this->setPropertyModifier($reflectionProperty, $modifier);

        return $reflectionProperty->getValue($this->subject);
    }

    private function getPropertyModifier($reflectionProperty)
    {
        $modifier = '';

        if ($reflectionProperty->isProtected()) {
            $modifier = static::PROTECTED_MODIFIER;
        } else if ($reflectionProperty->isPublic()) {
            $modifier = static::PUBLIC_MODIFIER;
        } else if ($reflectionProperty->isPrivate()) {
            $modifier = static::PRIVATE_MODIFIER;
        }

        return $modifier;
    }

    private function setPropertyModifier($reflectionProperty, $modifier)
    {
        switch($modifier) {
            case static::PROTECTED_MODIFIER:
            case static::PRIVATE_MODIFIER:
                $reflectionProperty->setAccessible(false);
                break;
            case static::PUBLIC_MODIFIER:
                $reflectionProperty->setAccessible(true);
                break;
            default:
                throw new \InvalidArgumentException(
                    "Modifier is not valid. Valid ones: " .
                    implode(
                        array(static::PROTECTED_MODIFIER, static::PUBLIC_MODIFIER, static::PRIVATE_MODIFIER),
                        ', '
                    )
                );
        }
    }
}
