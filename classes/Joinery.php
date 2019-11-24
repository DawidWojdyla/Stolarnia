<?php
class Joinery extends MyDB
{
	public $stand = null;
	
	function __construct($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8'){
		$this->dbo = $this->initDB($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8');
		$this->stand = $this->getActualStand();
	}
	
	function getActualStand(){
		if(isset($_SESSION['stand'])){
			return $_SESSION['stand'];
		}
		else{ 
			return null;
		}
	}
	
	function setMessage($message){
		$_SESSION['message'] = $message;
	}
  
	function getMessage(){
		if(isset($_SESSION['message'])){
			$message = $_SESSION['message'];
			unset($_SESSION['message']);
			return $message;
		}
		else{
			return null;
		}
	}
	
	function hideMessageAfterTime($delay){
		$_SESSION['delay'] = $delay;
	}
	
	function getDelay(){
		if(isset($_SESSION['delay'])){
			$delay = $_SESSION['delay'];
			unset($_SESSION['delay']);
			return $delay;
		}
		else{
			return null;
		}
	}
	
	function login(){
		if( !$this->dbo) return SERVER_ERROR;
		if ($this->stand) return NO_LOGIN_REQUIRED;
		 
		if (!isset($_POST["standId"]) || !isset($_POST["password"])) return FORM_DATA_MISSING;
		
		$standId = filter_input(INPUT_POST, 'standId');
		$password = filter_input(INPUT_POST, 'password');
		
		$passwordLength = mb_strlen($password, 'utf8');
		
		if($standId < 1 || $standId > 4 || $passwordLength < 2 || $passwordLength > 10){
			return ACTION_FAILED;
		}
		
		$query = "SELECT `name`, `password` FROM `stands` WHERE `id`=:standId";
		$PDOstatement = $this->dbo->prepare($query);
		$PDOstatement -> bindValue(':standId', $standId, PDO::PARAM_INT);

		if(!$PDOstatement->execute()){
			return SERVER_ERROR;
		}
		
		if(!$result = $PDOstatement->fetch(PDO::FETCH_NUM)){
		  return ACTION_FAILED;
		}
		
		 //if(!password_verify($password, $result[1])){
			//return ACTION_FAILED;
		 //}
		 if($password != $result[1]){
			 return ACTION_FAILED;
		 }
		 
		$_SESSION['stand'] = new Stand($standId, $result[0]);
		
		return ACTION_OK;
	}
	  
	function logout(){
		$this->stand = null;
		if (isset($_SESSION['stand']))
		unset($_SESSION['stand']);
	}
	
	function addNewOrder(){
		if ($this->stand->id != 4)  return NO_PERMISSION;
		$orders = new Orders ($this->dbo);
		return $orders -> addNewOrder();
	}
	
	function showOrderAddingForm(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 4)  return NO_PERMISSION;
		$orders = new Orders ($this->dbo);
		return $orders->showOrderAddingForm();
	}
	
	function showOrderListForShop(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 4)  return NO_PERMISSION;
		$orders = new Orders ($this->dbo);
		return $orders->showOrderListForShop();
	}
  
}
?>