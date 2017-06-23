<?php

namespace Printastigo\Support\Storage\Contracts;

interface StorageInterface
{
	public function get($index);
	public function set($index, $value);
	public function all();
	public function exists($index);
	public function unsetIt($index);
	public function clear();
}

?>