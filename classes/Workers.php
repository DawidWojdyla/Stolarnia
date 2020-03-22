<?php
class Workers
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function returnWorkers(){
		$workers = array();
		if($result = $this -> dbo -> query("SELECT `id`, CONCAT_WS(' ',`name`, `surname`) as name FROM workers WHERE `id` NOT IN (SELECT `worker_id` FROM `workers_removed`)")){
			$sawWorkers = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $workers;
	}
	
	function returnRemovedWorkers(){
		$workers = array();
		if($result = $this -> dbo -> query("SELECT `id`, CONCAT_WS(' ',`name`, `surname`) as name FROM workers WHERE `id` IN (SELECT `worker_id` FROM `workers_removed`)")){
			$sawWorkers = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $workers;
	}
	
	function returnSawWorkers(){
		$sawWorkers = array();
		if($result = $this -> dbo -> query("SELECT `id`, CONCAT_WS(' ',`name`, `surname`) as name FROM workers WHERE `id` IN (SELECT `worker_id` FROM workers_stands WHERE `stand_id`= 1 OR `stand_id`= 2) AND `id` NOT IN (SELECT `worker_id` FROM `workers_removed`)")){
			$sawWorkers = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $sawWorkers;
	}
	
	function returnEdgeBandingWorkers(){
		$edgeBandingWorkers = array();
		if($result = $this->dbo->query("SELECT `id`, CONCAT_WS(' ',`name`, `surname`) as name FROM `workers` WHERE `id` IN (SELECT `worker_id` FROM `workers_stands` WHERE `stand_id`= 3) AND `id` NOT IN (SELECT `worker_id` FROM `workers_removed`)")){
			$edgeBandingWorkers = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandingWorkers;
	}
	
	function returnSellers(){
		$sellers = array();
		if($result = $this -> dbo -> query("SELECT `id`, CONCAT_WS(' ',`name`, `surname`) as name FROM workers WHERE `id` IN (SELECT `worker_id` FROM workers_stands WHERE `stand_id`= 4) AND `id` NOT IN (SELECT `worker_id` FROM `workers_removed`)")){
			$sellers = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $sellers;
	}
	
	function showWorkerAddingForm(){
		
		$stands = new Stands ($this -> dbo);
		$standsList = $stands -> returnStandsList();
		
		include 'templates/workerAddingForm.php';
	}
	
	function setWorkerName($id, $name){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$name = ucwords($name);
		
		$query = $this -> dbo -> prepare ("UPDATE `workers` SET `name`=:name WHERE `id`=:id");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	
	function updateWorkerName(){
		if(!isset($_POST['id']) || $_POST['id'] == '' || ((int)($_POST['id'])) < 1 || !isset($_POST['name']) || $_POST['name'] == ''  ){
			return FORM_DATA_MISSING;
		}
		return $this -> setWorkerName($_POST['id'], $_POST['name']);
	}
	
	function setWorkerSurname($id, $surname){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$surname = ucwords($surname);
		
		$query = $this -> dbo -> prepare ("UPDATE `workers` SET `surname`=:surname WHERE `id`=:id");
		$query -> bindValue (':surname', $surname, PDO::PARAM_STR);
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateWorkerSurname(){
		if(!isset($_POST['id']) || $_POST['id'] == '' || ((int)($_POST['id'])) < 1 || !isset($_POST['surname']) || $_POST['surname'] == ''  ){
			return FORM_DATA_MISSING;
		}
		return $this -> setWorkerSurname($_POST['id'], $_POST['surname']);
	}
	
	function deleteWorkerStands($workerId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this->dbo->prepare("DELETE FROM `workers_stands` WHERE `worker_id`=:workerId");
		$query->bindValue(':workerId', $workerId, PDO::PARAM_INT);   
		
		if(!$query->execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;		
	}
	
	function setWorkerStands($id, $stands){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		foreach($stands as $standId){
			
			$query = $this -> dbo -> prepare ("INSERT INTO `workers_stands` VALUES (:id, :standId)");
			$query -> bindValue (':id', $id, PDO::PARAM_INT);
			$query -> bindValue (':standId', $standId, PDO::PARAM_INT);
		
			if (!$query -> execute()){ 
				return ACTION_FAILED;
			}	
		}
		return ACTION_OK;
	}
	
	function updateWorkerStands(){
		if(!isset($_POST['id']) || $_POST['id'] == '' || ((int)($_POST['id'])) < 1 || !isset($_POST['standsString']) || $_POST['standsString'] == ''  ){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if($this -> deleteWorkerStands($_POST['id']) != ACTION_OK){
			return ACTION_FAILED;
		}
		$stands =  explode(',', $_POST['standsString']);
		
		if($this -> setWorkerStands($_POST['id'], $stands) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}	
		return ACTION_OK;
	}
	
	function addWorkerToRemovedWorkersList($id){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `workers_removed` VALUES (:id)");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
	
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}	
		return ACTION_OK;
	}
	
	function removeWorker(){
		if(!isset($_POST['id']) || $_POST['id'] == '' || ((int)($_POST['id'])) < 1 ){
			return FORM_DATA_MISSING;
		}
		return $this -> addWorkerToRemovedWorkersList($_POST['id']);
	}
	
	function deleteWorkerFromRemovedWorkersList($id){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `workers_removed` WHERE `worker_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
	
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}	
		return ACTION_OK;
	}
	
	function restoreWorker(){
		if(!isset($_POST['id']) || $_POST['id'] == '' || ((int)($_POST['id'])) < 1 ){
			return FORM_DATA_MISSING;
		}
		return $this -> deleteWorkerFromRemovedWorkersList($_POST['id']);
	}
	
	function showWorkerUpdatingForm(){
		if (!isset($_POST['id']) || $_POST['id'] ==''){
			return FORM_DATA_MISSING;
		}

		$id = filter_input (INPUT_POST, 'id');
		$name = filter_input (INPUT_POST, 'firstName');
		$surname = filter_input (INPUT_POST, 'surname');	
		$stands = filter_input (INPUT_POST, 'stands');	
		$standsIds = filter_input (INPUT_POST, 'standsIds');	
		
		$standsObject = new Stands($this -> dbo);
		$standsList = $standsObject -> returnStandsList();
		
		include 'scripts/workerUpdatingFormScripts.php';
		include 'templates/workerUpdatingForm.php';
	}
	
	function checkIfWorkerNamesExistInDatabase($name, $surname){
		$query = $this -> dbo -> prepare("SELECT `id` FROM `workers` WHERE `name`=:name AND `surname`=:surname");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		$query -> bindValue (':surname', $surname, PDO::PARAM_STR);
		$query -> execute();
		if ($query -> rowCount()){
			return true;
		}
		return false;
	}
	
	function addNewWorkerToDatabase($name, $surname, $stands){
		if(!$this -> dbo){ return SERVER_ERROR;}
		if($this -> checkIfWorkerNamesExistInDatabase($name, $surname)){
			return WORKER_NAMES_ALREADY_EXISTS;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		$query  = $this -> dbo -> prepare ("INSERT INTO `workers` VALUES (NULL, :name, :surname)");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		$query -> bindValue (':surname', $surname, PDO::PARAM_STR);
		
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
		$name = trim($_POST['workerName']);
		$surname = trim($_POST['workerSurname']);
		$name = ucwords($name);
		$surname = ucwords($surname);
		
		return $this -> addNewWorkerToDatabase($name, $surname, $_POST['stands']);
	}
	
	function returnWorkersList($conditions12 = "", $condition3 = ""){
		$query = "SELECT `workers`.`id` AS workerId, `workers`.`name`, `workers`.`surname`, GROUP_CONCAT(`stands`.`id` ORDER BY  `stands`.`id`) AS standsIds, GROUP_CONCAT(`stands`.`name` ORDER BY `stands`.`id` SEPARATOR ', ') AS standsNames  FROM `workers`, `stands`, `workers_stands` WHERE `workers`.`id` NOT IN (SELECT `worker_id` FROM `workers_removed`) AND `workers_stands`.`worker_id`=`workers`.`id` AND `workers_stands`.`stand_id`=`stands`.`id`" . $conditions12 . " GROUP BY `workers`.`id`" . $condition3 . " ORDER BY `surname`";
		
		if(!$query = $this -> dbo -> query ($query)){
			return null;
		}
		
		if(!$result = $query -> fetchAll(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}
	
	function returnRemovedWorkersList(){
		$query = "SELECT `workers`.`id` AS workerId, `workers`.`name`, `workers`.`surname`, GROUP_CONCAT(`stands`.`id` ORDER BY  `stands`.`id`) AS standsIds, GROUP_CONCAT(`stands`.`name` ORDER BY `stands`.`id` SEPARATOR ', ') AS standsNames  FROM `workers`, `stands`, `workers_stands` WHERE `workers`.`id` IN (SELECT `worker_id` FROM `workers_removed`) AND `workers_stands`.`worker_id`=`workers`.`id` AND `workers_stands`.`stand_id`=`stands`.`id` GROUP BY `workers`.`id` ORDER BY `surname`";
		
		if(!$query = $this -> dbo -> query ($query)){
			return null;
		}
		
		if(!$result = $query -> fetchAll(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}
	
	function showWorkersList(){
		$workers = $this -> returnWorkersList();
		include 'scripts/workersListScripts.php';
		include 'templates/workersList.php';
	}
	
	function showRemovedWorkersList(){
		$removedWorkers = $this -> returnRemovedWorkersList();
		include 'scripts/removedWorkersListScripts.php';
		include 'templates/removedWorkersList.php';
	}
	
	
	function showSearchResult(){
		if (!isset($_POST['name']) || !isset($_POST['surname'])){
			return FORM_DATA_MISSING;
		}
		$condition1 = "";
		$condition2 = "";
		$condition3 = "";
		
		$name = trim($_POST['name']);
		$surname = trim($_POST['surname']);
		$name = ucwords($name);
		$surname = ucwords($surname);
		
		if($name != ""){
			$condition1 = " AND `workers`.`name` LIKE '%" . $name . "%'";
		}
			
		if($surname != ""){
			$condition2 = " AND `workers`.`surname` LIKE '%" . $surname . "%'";
		}
			
		if(isset($_POST['stands'])){
			$condition3 = " HAVING standsIds LIKE '%"; 
			foreach($_POST['stands'] as $standId){
				$condition3 .= $standId .","; 
			}
			$condition3 = substr($condition3, 0, -1);
			$condition3 .= "%'";
		}
		if($workers = $this -> returnWorkersList($condition1 . $condition2, $condition3)){
			include 'scripts/workersListScripts.php';
			include 'templates/workersList.php';
		}else{
			include 'templates/noResults.php';
		}
	}
	
	function showSearchingForm(){
		$name = filter_input (INPUT_POST, 'name');
		$surname = filter_input (INPUT_POST, 'surname');
		$standsIds   = filter_input(INPUT_POST, 'stands', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		
		$stands = new Stands($this -> dbo);
		$stands = $stands -> returnStandsList();
		
		include 'templates/workerSearchingForm.php';
	}
	
}
?>