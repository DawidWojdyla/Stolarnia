<?php
class Joinery extends MyDB
{
	public $loggedUser = null;
	
	function __construct($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8'){
		$this->dbo = $this->initDB($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8');
		$this->loggedUser = $this->getActualUser();
	}
	
	function getActualUser(){
		if(isset($_SESSION['loggedUser'])){
			return $_SESSION['loggedUser'];
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

	function login(){
		
		//TREŒÆ FUNKCJI ODPOWIADAJ¥CEJ ZA LOGOWANIE
	}
	  
	function logout()
	{
		//TREŒÆ FUNKCJI ODPOWIADAJ¥CEJ ZA WYLOGOWANIE
	}
  
}
?>