<?php
namespace Authorizit\Resource;

interface ResourceInterface
{
    public function __construct($resource);
    public function getClass();
    public function checkProperties($conditions);
}
