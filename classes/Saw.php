<?php
class Saw
{
	private $dbo			 	= null;
	private $sawNumber 	= null;
	
	function __construct($dbo, $sawNumber){
		$this->dbo = $dbo;
		$this->sawNumber = $sawNumber;
	}
	
	function setTheBoardCuttingState($boardId, $stateId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `cutting_state_id`=:stateId WHERE `orders_boards`.`id`=:boardId");
		$query -> bindValue (':stateId', $stateId, PDO::PARAM_INT);
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setTheBoardCuttingWorkers($boardId, $sawWorkers){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		if($this->deleteTheBoardCuttingWorkers($boardId) != ACTION_OK ){
			return ACTION_FAILED;
		}
		foreach($sawWorkers as $sawWorkerId){
			$query = $this -> dbo -> prepare ("INSERT INTO `cutting_workers` VALUES (:boardId, :sawWorkerId)");
			$query -> bindValue (':sawWorkerId', $sawWorkerId, PDO::PARAM_INT);
			$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
			if (!$query -> execute()){ 
				return ACTION_FAILED;
			}	
		}
		return ACTION_OK;		
	}
	
	function deleteTheBoardCuttingWorkers($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this->dbo->prepare("DELETE FROM `cutting_workers` WHERE `orders_boards_id`=:boardId");
		$query->bindValue(':boardId', $boardId, PDO::PARAM_INT);   
		
		if(!$query->execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;		
	}
	
	function setTheBoardCuttingComment($boardId, $comment){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `cutting_comments` VALUES (:boardId, :comment)");
		$query -> bindValue (':comment', $comment, PDO::PARAM_STR);
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteTheBoardCuttingComment($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = "DELETE FROM `cutting_comments` WHERE `orders_boards_id`={$boardId}";
		if (!$this->dbo->exec($query)){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function setTheBoardCuttingCompletionDate($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `cutting_completion_dates` VALUES (:boardId, now())");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function returnOutstandingOrdersOfTheSaw(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`saw_number`={$this->sawNumber} AND `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_state_id`='0') ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders_boards`.`id` as boardId, `boards_signs`.`sign`, `boards_symbols`.`symbol`, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `orders_boards`.`cutting_metters`, `orders_boards`.`cutting_state_id` as stateId, `cutting_comments`.`comment` as cuttingComment FROM `orders_boards` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `boards_signs`, `boards_symbols`, `boards_thickness`, `boards_structures` WHERE `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id`")){
			$boards = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}
	
	/*function returnOutstandingOrdersOfTheSaw2(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, `states`.`name` as state FROM `orders` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `states` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`state_id`=`states`.`id` AND `orders`.`state_id`='0' AND `orders`.`saw_number`={$this->sawNumber} ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}*/
	
	function setTheBoardComment(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['boardCuttingComment'])){
			return FORM_DATA_MISSING;
		}
		
		if($_POST['boardCuttingComment'] == ''){
			return $this->deleteBoardCuttingComment($_POST['boardId']);
		}
		
		return $this->setTheBoardCuttingComment($_POST['boardId'], $_POST['boardCuttingComment']);
	}
	
	function resetTheBoardCutting(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		
		if($this->setTheBoardCuttingState($_POST['boardId'], 0) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this->deleteTheBoardCuttingComment($_POST['boardId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this->deleteTheBoardCuttingWorkers($_POST['boardId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}
		
		return ACTION_OK;
	}
	
	function setTheBoardCutting(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['sawWorkers'])){
			return FORM_DATA_MISSING;
		}
		$boardId = filter_input(INPUT_POST, 'boardId');
		
		foreach($_POST['sawWorkers'] as $sawWorker){
			if((int)$sawWorker < 1){
				return FORM_DATA_MISSING;
			}
		}
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		if($this -> setTheBoardCuttingState($boardId, 1) != ACTION_OK){
			return ACTION_FAILED;
		}
		if($this -> setTheBoardCuttingWorkers($boardId, $_POST['sawWorkers']) != ACTION_OK){
			return ACTION_FAILED;
		}
		if(isset($_POST['boardCuttingComment']) && $_POST['boardCuttingComment'] != ''){
			if($this -> setTheBoardCuttingComment($boardId, $_POST['boardCuttingComment']) != ACTION_OK){
				return ACTION_FAILED;
			}
		}
		if($this -> setTheBoardCuttingCompletionDate($boardId) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	
	}
	
	function returnSawWorkers(){
		$sawWorkers = array();
		if($result = $this->dbo->query("SELECT `id`, `name` FROM workers WHERE `id` IN (SELECT `worker_id` FROM workers_stands WHERE `stand_id`= 1 OR `stand_id`= 2)")){
			$sawWorkers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $sawWorkers;
	}
	
	function showOrderList(){
		//$this->setOrderListPeriod();
		$orderList = $this->returnOutstandingOrdersOfTheSaw();
		
		include 'scripts/orderListForSawScripts.php';
		include 'templates/orderListForSaw.php';
	}
	
	function showOrderCuttingForm(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1){
			return FORM_DATA_MISSING;
		}
		$orderId = filter_input(INPUT_POST, 'orderId');
		
		$boards = $this -> returnOrderDetails($orderId);
		$sawWorkers = $this -> returnSawWorkers();
		
		include 'scripts/orderCuttingFormScripts.php';
		include 'templates/orderCuttingForm.php';
	}
}
?>