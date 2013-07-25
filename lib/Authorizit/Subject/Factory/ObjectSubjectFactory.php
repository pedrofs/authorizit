<?php
namespace Authorizit\Subject\Factory;

use Authorizit\Subject\ObjectSubject;

class ObjectSubjectFactory
{
    public function get($subject)
    {
        return new ObjectSubject($subject);
    }
}
