<?php
class Workers
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function showWorkerAddingForm(){
		
		$stands = new Stands ($this -> dbo);
		$standsList = $stands -> returnStandsList();
		
		include 'templates/workerAddingForm.php';
	}
	
	function checkIfWorkerNameExistsInDatabase($name){
		$query = $this -> dbo -> prepare("SELECT `id` FROM `workers` WHERE `name`=:name");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		$query -> execute();
		if ($query -> rowCount()){
			return true;
		}
		return false;
	}
	
	function addNewWorkerToTheDatabase($name, $stands){
		if(!$this -> dbo){ return SERVER_ERROR;}
		if($this -> checkIfWorkerNameExistsInDatabase($name)){
			return WORKER_NAME_ALREADY_EXISTS;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		$query  = $this -> dbo -> prepare ("INSERT INTO `workers` VALUES (NULL, :name)");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		
		if (!$query -> execute()){
			return SERVER_ERROR;
		}
		
		foreach($stands as $standId){
			$query = $this -> dbo -> prepare ("INSERT INTO `workers_stands` VALUES (LAST_INSERT_ID(), :standId)");
			$query -> bindValue (':standId', $standId, PDO::PARAM_INT);
		
			if (!$query -> execute()){ 
				return ACTION_FAILED;
			}	
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}	
		unset($_SESSION['workerName']);
		unset($_SESSION['workerSurname']);
		
		return ACTION_OK;
	}
	
	function addNewWorker(){
		if(!isset($_POST['workerName']) || $_POST['workerName'] == '' || !isset($_POST['workerSurname']) || $_POST['workerSurname'] == '' || !isset($_POST['stands'])){
			return FORM_DATA_MISSING;
		}
		$_SESSION ['workerName'] = filter_input (INPUT_POST, 'workerName');
		$_SESSION ['workerSurname'] = filter_input (INPUT_POST, 'workerSurname');

		foreach($_POST['stands'] as $standId){
			if( (int)$standId < 1 || (int)$standId > 4){
				return FORM_DATA_MISSING;
			}
		}
		$name = trim($_POST['workerName']) . " " . trim($_POST['workerSurname']);
		$name = ucwords($name);
		
		return $this->addNewWorkerToTheDatabase($name, $_POST['stands']);
	}
	
}
?>