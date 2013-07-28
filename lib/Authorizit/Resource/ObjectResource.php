<?php
namespace Authorizit\Resource;

class ObjectResource implements ResourceInterface
{
    const PUBLIC_MODIFIER     = 'public';
    const PROTECTED_MODIFIER  = 'protected';
    const PRIVATE_MODIFIER    = 'private';

    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function getClass()
    {
        return is_string($this->resource) ? $this->resource :
            get_class($this->resource);
    }

    public function checkProperties($conditions)
    {
        foreach ($conditions as $attr => $value) {
            if($this->getValueFromResource($attr) != $value) {
                return false;
            }
        }

        return true;
    }

    private function getValueFromResource($attr)
    {
        $reflectionResource = new \ReflectionClass($this->resource);
        $reflectionProperty = $reflectionResource->getProperty($attr);
        $modifier           = $this->getPropertyModifier($reflectionProperty);

        $reflectionProperty->setAccessible(true);

        $value = $reflectionProperty->getValue($this->resource);

        $this->setPropertyModifier($reflectionProperty, $modifier);

        return $value;
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
