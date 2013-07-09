<?php
namespace Authorizit\Subject;

interface SubjectInterface
{
    public function __construct($subject);
    public function getClass();
    public function checkProperties($conditions);
}