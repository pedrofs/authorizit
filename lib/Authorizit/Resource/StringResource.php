<?php
namespace Authorizit\Resource;

class StringResource implements ResourceInterface
{
	private $resource;

	public function __construct($resource)
	{
		$this->resource = $resource;
	}

	public function getClass()
	{
		return $this->resource;
	}

	public function checkProperties($conditions)
	{
		return true;
	}

	public function getResource()
	{
		return $this->resource;
	}
}