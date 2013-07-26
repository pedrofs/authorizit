<?php
require __DIR__ . '/../vendor/autoload.php';

use Authorizit\Base;

class Authorizit extends Base
{
    public function init()
    {
        $this->write('read', 'Testing', array('userId' => $this->user['id']));
    }
}

class Testing
{
	protected $userId;

	public function __construct($userId)
	{
		$this->userId = $userId;
	}
}

$auth = new Authorizit(array('id' => 1));

$auth->init();

var_dump($auth->check('read', 'Testing'));
var_dump($auth->check('read', new Testing(2)));
var_dump($auth->check('read', new Testing(1)));
