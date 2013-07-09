<?php
require __DIR__ . '/../vendor/autoload.php';

use Authorizit\Base;

class Authorizit extends Base
{
    public function __construct($user)
    {
        parent::__construct($user);
    }

    public function authorizit()
    {
        $this->write('read', 'Testing');
    }
}

class Testing {}

$auth = new Authorizit(array('id' => 1));

$auth->authorizit();

var_dump($auth->check('read', 'Testing'));
var_dump($auth->check('read', new Testing));
