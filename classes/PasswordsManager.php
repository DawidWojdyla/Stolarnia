<?php
class PasswordsManager
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}

}
?>