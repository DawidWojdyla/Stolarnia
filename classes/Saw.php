<?php
class Saw
{
	private $dbo			 	= null;
	private $sawNumber 	= null;
	
	function __construct($dbo, $sawNumber){
		$this -> dbo = $dbo;
		$this -> sawNumber = $sawNumber;
	}
	
	function setTheBoardCuttingCompletionDate($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `cutting_completion_date`=NOW() WHERE `orders_boards`.`id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function resetTheBoardCuttingCompletionDate($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `cutting_completion_date`=NULL WHERE `orders_boards`.`id`=:boardId");
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
		$query = $this -> dbo -> prepare ("DELETE FROM `cutting_comments` WHERE `orders_boards_id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function returnOutstandingOrdersOfTheSaw(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname,  `customers`.`phone`, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`saw_number`={$this->sawNumber} AND `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL) ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$boards = array();
		if($result = $this->dbo->query("SELECT `orders_boards`.`id` as boardId, `boards_signs`.`sign`, `boards_symbols`.`symbol`, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `orders_boards`.`cutting_metters`, `orders_boards`.`cutting_completion_date`,`cutting_comments`.`comment` as cuttingComment, `edge_banding`.`id` as edgeBandingId FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `boards_signs`, `boards_symbols`, `boards_thickness`, `boards_structures` WHERE `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id` GROUP BY boardId")){
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
		
		if($this->resetTheBoardCuttingCompletionDate($_POST['boardId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this->deleteTheBoardCuttingComment($_POST['boardId']) != ACTION_OK){
			return NO_PERMISSION;
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
		if($this -> setTheBoardCuttingCompletionDate($boardId) != ACTION_OK){
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
	
	function findOrderByDocumentNumber($documentNumber){
		$query = $this -> dbo -> prepare ("SELECT `orders`.`id` as orderId, `customers`.`id` as customerId,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as phone,`customers_temp`.`name` as tempCustomerName, `customers_temp`.`phone` as tempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`saw_number`={$this->sawNumber} AND `document_number`=:documentNumber");
		$query -> bindValue(':documentNumber', $documentNumber, PDO::PARAM_STR);
		if(!$query->execute()){
			return null;
		}
		
		if(!$result = $query->fetch(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}
	
	function returnSawWorkers(){
		$sawWorkers = array();
		if($result = $this->dbo->query("SELECT `id`, `name` FROM workers WHERE `id` IN (SELECT `worker_id` FROM workers_stands WHERE `stand_id`= 1 OR `stand_id`= 2)")){
			$sawWorkers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $sawWorkers;
	}
	
	/*function showTheOrder($orderId, $orderTitle){
		$boards = $this -> returnOrderDetails($orderId);
		$sawWorkers = $this -> returnSawWorkers();
		
		include 'scripts/orderCuttingFormScripts.php';
		include 'templates/orderCuttingForm.php';
	}*/
	
	function showLastCutBoards($positionsAmount){
		$positionsAmount = intval($positionsAmount);
		$orders = array();
		if($query = $this -> dbo -> prepare ("SELECT max(`orders_boards`.`cutting_completion_date`) as lastCuttingDate, `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname,  `customers`.`phone`, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `orders_boards` ON `orders_boards`.`order_id`=`orders`.`id` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`saw_number`={$this->sawNumber} AND `orders_boards`.`cutting_completion_date` IS NOT NULL GROUP BY orderId ORDER BY lastCuttingDate DESC LIMIT :positionsAmount")){
			$query -> bindValue (':positionsAmount', $positionsAmount, PDO::PARAM_INT);
			if ($query -> execute()){ 
				$orders = $query -> fetchAll(PDO::FETCH_OBJ);
			}
		}
		return $orders;
	}
	
	function showLastMadeOrders(){
		//$this->setOrderListPeriod();
		
		$positionsAmount = 30;
		
		$orderList = $this -> showLastCutBoards($positionsAmount);
		
		include 'scripts/lastMadeOrdersForSawScript.php';
		include 'templates/lastMadeOrdersForSaw.php';
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
		$documentNumber = filter_input(INPUT_POST, 'documentNumber');
		$customerName = filter_input(INPUT_POST, 'customerName');
		$phone = filter_input(INPUT_POST, 'phone');
		$comment = filter_input(INPUT_POST, 'comment');
		
		$boards = $this -> returnOrderDetails($orderId);
		$sawWorkers = $this -> returnSawWorkers();
		
		include 'scripts/orderCuttingFormScripts.php';
		include 'templates/orderCuttingForm.php';
	
	}
	
	function showOrderSearchingForm(){
		$documentType = filter_input (INPUT_POST, 'documentType');
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');
		$documentBranch = filter_input (INPUT_POST, 'documentBranch');
	
		include 'scripts/orderSeachringFormForSawScripts.php';
		include 'templates/orderSeachringFormForSaw.php';
	}
	
	function showSearchResult(){
		if (!isset($_POST['documentType']) || $_POST['documentType'] =='' || !isset($_POST['documentNumber']) || $_POST['documentNumber'] == "" || !isset($_POST['documentBranch']) || $_POST['documentBranch'] == "" ){
			return FORM_DATA_MISSING;
		}
		
		$_POST['documentNumber'] = str_pad($_POST['documentNumber'], 6, "0", STR_PAD_LEFT);
		$documentNumber = $_POST['documentType'] . $_POST['documentNumber'] . $_POST['documentBranch'];
		
		if($order = $this -> findOrderByDocumentNumber($documentNumber)){
			$comment = $order ->orderComment;
			if($order -> customerId != 1){
				$customerName = $order->customerName . ' ' . $order->customerSurname;
				$phone = $order->phone;
			}
			else{
				$customerName = $order -> tempCustomerName;
				$phone = $order -> tempPhone;
			}
			$boards = $this -> returnOrderDetails($order->orderId);
			$sawWorkers = $this -> returnSawWorkers();
		
			include 'scripts/orderCuttingFormScripts.php';
			include 'templates/orderCuttingForm.php';
		}
		else{
			include 'templates/noResults.php';
		}
	
	}
	
}
?>
