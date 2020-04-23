<?php
class Stands
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}

	function returnStandsList(){
		$standsList = array();
		if($result = $this -> dbo -> query("SELECT `id`, `name` FROM stands ORDER BY `id`")){
			$standsList = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $standsList;
	}
	
	function returnStandsListWithWorkersAmount(){
		$standsList = array();
		if($result = $this -> dbo -> query("SELECT `id`, `name`, COUNT(`workers_stands`.`worker_id`) as workersAmount FROM `stands`, `workers_stands` WHERE `workers_stands`.`stand_id`=`stands`.`id` GROUP BY `stands`.`id`")){
			$standsList = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $standsList;
	}
	
	function showLoginForm(){
		$stands = $this -> returnStandsList();
		include 'templates/loginForm.php';
	}
	
	function showStandsUpdatingForm(){
		$stands = $this -> returnStandsListWithWorkersAmount();
		include 'scripts/standsUpdatingFormScripts.php';
		include 'templates/standsUpdatingForm.php';
	}
	
			
		
	
	function	setStandName($id, $name){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `stands` SET `name`=:name WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateStandName(){
		if(!isset($_POST['id']) || (int)$_POST['id'] < 0 || (int)$_POST['id'] > 4 || !isset($_POST['name']) || $_POST['name'] ==""){
			return FORM_DATA_MISSING;
		}
		$name = ucwords($_POST['name']);
		return $this -> setStandName($_POST['id'], $name);
	}
	
	function setStandPassword($id, $oldPassword, $newPassword){
		
		$query = $this -> dbo -> prepare("SELECT `password` FROM `stands` WHERE `id`=:id");
		$query -> bindValue(':id', $id, PDO::PARAM_INT);

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
		 
		$query = $this -> dbo -> prepare ("UPDATE `stands` SET `password`=:password WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		$query -> bindValue (':password', $password, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}

	function updateStandPassword(){
		if(!isset($_POST['id']) || (int)$_POST['id'] < 0 || (int)$_POST['id'] > 4 || !isset($_POST['oldPassword']) || $_POST['oldPassword'] =="" || !isset($_POST['password']) || $_POST['password'] ==""){
			return FORM_DATA_MISSING;
		}
		
		return $this -> setStandPassword($_POST['id'], $_POST['oldPassword'], $_POST['password']);
	}
}
?>