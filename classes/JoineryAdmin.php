<?php
class JoineryAdmin extends MyDB
{
	public $loggedAdmin = null;
	
	function __construct($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8'){
		$this -> dbo = $this->initDB($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8');
		$this -> loggedAdmin = $this -> getActualAdmin();
	}
	
	function getActualAdmin(){
		if(isset($_SESSION['loggedAdmin'])){
			return $_SESSION['loggedAdmin'];
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
	
	function setHidingMessageDelay($delay){
		$_SESSION['hidingMessageDelay'] = $delay;
	}
	
	function getHidingMessageDelay(){
		if(isset($_SESSION['hidingMessageDelay'])){
			$delay = $_SESSION['hidingMessageDelay'];
			unset($_SESSION['hidingMessageDelay']);
			return $delay;
		}
		else{
			return null;
		}
	}
	
	function login(){
		if( !$this->dbo) return SERVER_ERROR;
		if ($this -> loggedAdmin) return NO_LOGIN_REQUIRED;
		 
		if (!isset($_POST["login"]) || !isset($_POST["password"])) return FORM_DATA_MISSING;
		
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'password');
		
		$loginLength = mb_strlen($password, 'utf8');
		$passwordLength = mb_strlen($password, 'utf8');
		
		if($loginLength < 3 || $loginLength > 15 || $passwordLength < 3 || $passwordLength > 15){
			return ACTION_FAILED;
		}
		
		$query = $this -> dbo -> prepare("SELECT `password` FROM `admins` WHERE `name`=:login");
		$query -> bindValue(':login', $login, PDO::PARAM_STR);

		if(!$query -> execute()){
			return SERVER_ERROR;
		}
		
		if(!$result = $query -> fetch(PDO::FETCH_NUM)){
		  return ACTION_FAILED;
		}
		
		 if(!password_verify($password, $result[0])){
			return ACTION_FAILED;
		 }
		 //if($password != $result[0]){
			// return ACTION_FAILED;
		 //}
		 
		$_SESSION['loggedAdmin'] = $login;
		
		return ACTION_OK;
	}
	  
	function logout(){
		$this -> loggedAdmin  = null;
		if (isset($_SESSION['loggedAdmin'])){
		unset($_SESSION['loggedAdmin']);
		}
	}
	
	function showWorkerAddingForm(){
		if(!$this->dbo) { return SERVER_ERROR; }
		if (!$this -> loggedAdmin) {  return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> showWorkerAddingForm();
	}
	
	function showWorkerUpdatingForm(){
		if(!$this->dbo) { return SERVER_ERROR; }
		if (!$this -> loggedAdmin) {  return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> showWorkerUpdatingForm();
	}
	
	function addNewWorker(){
		if(!$this -> dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> addNewWorker();
	}
	
	function updateWorkerName(){
		if(!$this -> dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> updateWorkerName();
	}
	
	function updateWorkerSurname(){
		if(!$this -> dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> updateWorkerSurname();
	}
	
	function updateWorkerStands(){
		if(!$this -> dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers -> updateWorkerStands();
	}
	
	function showWorkersList(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
			
		$workers = new Workers($this -> dbo);
		return $workers -> showWorkersList();
	}	
	
	function showWorkerSearchingForm(){
		if(!$this->dbo) { return SERVER_ERROR; }
		if (!$this -> loggedAdmin) {  return NO_PERMISSION; }
		$workers = new Workers ($this->dbo);
		return $workers->showSearchingForm();
	}
	
	function showWorkerSearchResult(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if (!$this -> loggedAdmin){ return NO_PERMISSION; }
			
		$workers = new Workers($this -> dbo);
		return $workers -> showSearchResult();
	}	
	
	
	
}
?>