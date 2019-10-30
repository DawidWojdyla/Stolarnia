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
		if( !$this->dbo) return SERVER_ERROR;
		if ($this->loggedUser) return NO_LOGIN_REQUIRED;
		 
		if (!isset($_POST["username"]) || !isset($_POST["password"])) return FORM_DATA_MISSING;
		
		$username = filter_input(INPUT_POST, 'username');
		$password = filter_input(INPUT_POST, 'password');
		
		$usernameLength = mb_strlen($username, 'utf8');
		$passwordLength = mb_strlen($password, 'utf8');
		
		if($usernameLength < 2 || $usernameLength >20 || $passwordLength < 4 || $passwordLength > 15){
			return ACTION_FAILED;
		}
		
		$query = "SELECT `id`, `password` FROM `users` WHERE `username`=:username";
		$PDOstatement = $this->dbo->prepare($query);
		$PDOstatement -> bindValue(':username', $username, PDO::PARAM_STR);

		if(!$PDOstatement->execute())
			return SERVER_ERROR;
		
		if(!$result = $PDOstatement->fetch(PDO::FETCH_NUM))
		  return ACTION_FAILED;
		
		 if(!password_verify($password, $result[1]))
			return ACTION_FAILED;
		 
		$_SESSION['loggedUser'] = new User($result[0], $username);
		if(!$result = $this->dbo->query("SELECT `permission_id` FROM `users_permissions` WHERE user_id={$_SESSION['loggedUser']->id}")){
			return NO_PERMISSIONS;
		}
		$_SESSION['loggedUser']->permissions = $result->fetchAll();
		return ACTION_OK;
	}
	  
	function logout()
	{
		$this->loggedUser = null;
		if (isset($_SESSION['loggedUser']))
		unset($_SESSION['loggedUser']);
	}
  
}
?>