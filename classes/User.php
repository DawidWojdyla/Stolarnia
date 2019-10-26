<?php
class User
{
	public $id;
	public $name;
	public $permissions;
  
	function __construct($id, $name){
		$this->id = $id;
		$this->name = $name;
		$this->permissions = [];
	}
}
?>