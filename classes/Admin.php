<?php
class Admin
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}
	
	function returnAdminName(){
		if($query = $this -> dbo -> query("SELECT `name` FROM admins WHERE id='1'")){
			$name = $query -> fetch(PDO::FETCH_NUM);
		}
		return $name[0];
	}

	function showAdminUpdatingForm(){
		$login = $this -> returnAdminName();
		include 'scripts/adminUpdatingFormScripts.php';
		include 'templates/adminUpdatingForm.php';
	}
	
	function	setNewLogin($name){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `admins` SET `name`=:name WHERE `id`='1'");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateLogin(){
		if(!isset($_POST['login']) || $_POST['login'] == ""){
			return FORM_DATA_MISSING;
		}
		return $this -> setNewLogin($_POST['login']);
	}
	
	function setNewPassword($login, $oldPassword, $newPassword){
		
		$query = $this -> dbo -> prepare("SELECT `password` FROM `admins` WHERE `name`=:login");
		$query -> bindValue (':login', $login, PDO::PARAM_STR);
		if(!$query -> execute()){
			return SERVER_ERROR;
		}
		if(!$result = $query -> fetch(PDO::FETCH_NUM)){
		  return ACTION_FAILED;
		}
		
		 if(!password_verify($oldPassword, $result[0])){
			return PASSWORDS_DO_NOT_MATCH;
		 }
		 
		 $password = password_hash($newPassword, PASSWORD_DEFAULT);
		 
		$query = $this -> dbo -> prepare ("UPDATE `admins` SET `password`=:password WHERE `name`=:login");
		$query -> bindValue (':password', $password, PDO::PARAM_STR);
		$query -> bindValue (':login', $login, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}

	function updatePassword(){
		if(!isset($_POST['login']) || $_POST['login'] =="" || !isset($_POST['oldPassword']) || $_POST['oldPassword'] =="" || !isset($_POST['password']) || $_POST['password'] ==""){
			return FORM_DATA_MISSING;
		}
		
		return $this -> setNewPassword($_POST['login'], $_POST['oldPassword'], $_POST['password']);
	}
}
?>