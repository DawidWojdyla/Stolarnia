<?php
class EdgeBandingMachine
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}
	
	function setTheBoardEdgeBandingMetters($edgeBandingId, $edgeBandingMetters){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `edge_banding` SET `edge_banding_metters_machine`=:edgeBandingMetters WHERE `edge_banding`.`id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingMetters', $edgeBandingMetters, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setTheBoardEdgeBandingWorkers($edgeBandingId, $workers){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		if($this->deleteTheBoardEdgeBandingWorkers($edgeBandingId) != ACTION_OK ){
			return ACTION_FAILED;
		}
		foreach($workers as $worker){
			$query = $this -> dbo -> prepare ("INSERT INTO `edge_banding_workers` VALUES (:workerId, :edgeBandingId)");
			$query -> bindValue (':workerId', $worker->id, PDO::PARAM_INT);
			$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
			if (!$query -> execute()){ 
				return ACTION_FAILED;
			}	
		}
		return ACTION_OK;		
	}
	
	function deleteEdgeBandingWorkers($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this->dbo->prepare("DELETE FROM `edge_banding_workers` WHERE `edgeBandingId`.`id`=:edgeBandingId");
		$query->bindValue(':boardId', $boardId, PDO::PARAM_INT);   
		
		if(!$query->execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;		
	}
	
	function setEdgeBandingComment($edgeBandingId, $comment){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `edge_banding_comments` VALUES (:edge_banding_id, :comments)");
		$query -> bindValue (':comments', $comment, PDO::PARAM_STR);
		$query -> bindValue (':edgeBandingId', $edge_banding_id, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteEdgeBandingComment($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = "DELETE FROM `edge_banding_comments` WHERE `edge_banding_id`={$edgeBandingId}";
		if (!$this->dbo->exec($query)){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function setEdgeBandingCompletionDate($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_banding_completion_dates` VALUES (:edgeBandingId, now())");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function returnCutOrdersList(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`id` NOT IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_state_id`='0' AND `orders_boards`.`board_sign_id` <> '1') ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$orders = array();
		if($result = $this->dbo->query("SELECT `edge_banding`.`id`,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, `boards_symbols`.`symbol`,  `edge_banding`.`edge_banding_metters_wz` as wzMetters, `edge_banding`.`edge_banding_metters_machine` as machineMetters, `edge_band_comments`.`comments` as edgeBandComment,  `boards_signs`.`sign`, `boards_symbols`.`symbol`, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `orders_boards`.`cutting_state_id` as cuttingStateId, `cutting_comments`.`comment` as cuttingComment FROM `orders_boards` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `boards_signs`, `boards_symbols`, `boards_thickness`, `boards_structures` WHERE `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id`")){
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
	
	function showOrderSearchingForm(){
		if(!isset($_POST['documentNumber']) || $_POST['documentNumber'] == ''){
			return FORM_DATA_MISSING;
		}
		$documentNumber = filter_input(INPUT_POST, 'documentNumber');
		
		include 'scripts/orderSeachringFormForSawScripts.php';
		include 'templates/orderSeachringFormForSaw.php';
	}
}
?>