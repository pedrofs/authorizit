<?php
namespace Authorizit\Subject\Factory;

use Authorizit\Subject\CakeSubject;

class CakeSubjectFactory
{
    public function get($subject)
    {
        return new CakeSubject($subject);
    }
}