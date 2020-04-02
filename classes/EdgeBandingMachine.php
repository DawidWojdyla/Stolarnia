<?php
class EdgeBandingMachine
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function setMachineMetters($edgeBandingId, $edgeBandingMetters){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `edge_banding` SET `edge_banding_metters_machine`=:edgeBandingMetters WHERE `id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingMetters', $edgeBandingMetters, PDO::PARAM_STR);
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setEdgeBandingWorkers($edgeBandingId, $workers){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		if($this->deleteEdgeBandingWorkers($edgeBandingId) != ACTION_OK ){
			return ACTION_FAILED;
		}
		foreach($workers as $workerId){
			$query = $this -> dbo -> prepare ("INSERT INTO `edge_banding_workers` VALUES (:workerId, :edgeBandingId)");
			$query -> bindValue (':workerId', $workerId, PDO::PARAM_INT);
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
		
		$query = $this->dbo->prepare("DELETE FROM `edge_banding_workers` WHERE `edge_banding_id`=:edgeBandingId");
		$query->bindValue(':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);   
		
		if(!$query->execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;		
	}
	
	function setEdgeBandingComment($edgeBandingId, $comment){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `edge_banding_comments` VALUES (:edgeBandingId, :comments)");
		$query -> bindValue (':comments', $comment, PDO::PARAM_STR);
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteEdgeBandingComment($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_banding_comments` WHERE `edge_banding_id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function setEdgeBandingCompletionDate($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `edge_banding` SET `edge_banding_completion_date`=now() WHERE `id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
		function resetEdgeBandingCompletionDate($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this -> dbo -> prepare ("UPDATE `edge_banding` SET `edge_banding_completion_date`=NULL WHERE `id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function returnNotCutOrders(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, DATE_FORMAT(`orders`.`order_completion_date`, '%d.%m') as completionDate, DATE_FORMAT(`orders`.`admission_date`, '%d.%m') as admissionDate, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone`, `orders`.`saw_number`, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL) AND `orders`.`id` IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `edge_banding`.`edge_banding_metters_machine`=0) AND `workers`.`id`=`orders`.`worker_id` ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOutstandingOrders(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone`, DATE_FORMAT(`orders`.`order_completion_date`, '%d.%m') as completionDate, `orders`.`saw_number`, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`id` NOT IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL AND `board_sign_id` <> '1' ) AND `orders`.`id`=ANY(SELECT  `order_id` FROM `orders_boards` WHERE `board_sign_id`<> '1')  AND `orders`.`id` IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `edge_banding`.`edge_banding_metters_machine`=0) ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
		function showLastEdgeBandingOrders($positionsAmount){
		$positionsAmount = intval($positionsAmount);
		$orders = array();
		if($query = $this -> dbo -> prepare ("SELECT max(`edge_banding`.`edge_banding_completion_date`) as lastEBDate,`orders`.`id` as orderId, `orders`.`document_number`, DATE_FORMAT(`orders`.`order_completion_date`, '%d.%m') as completionDate,  `orders`.`saw_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone`, `orders`.`order_completion_date`, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `orders_boards`.`order_id`=`orders`.`id` AND `orders`.`customer_id`=`customers`.`id` AND `edge_banding`.`edge_banding_completion_date` IS NOT NULL GROUP BY orderId ORDER BY lastEBDate DESC LIMIT :positionsAmount")){
			$query -> bindValue (':positionsAmount', $positionsAmount, PDO::PARAM_INT);
			if ($query -> execute()){ 
				$orders = $query -> fetchAll(PDO::FETCH_OBJ);
			}
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$boards = array();
		if($result = $this->dbo->query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, boardSymbols.`symbol` as boardSymbol, `boards_thickness`.`thickness`, ob.`amount`, ob.`cutting_metters`, DATE_FORMAT(ob.`cutting_completion_date`, '%d-%m-%Y') as cuttingDate, DATE_FORMAT(ob.`cutting_completion_date`, '%H:%i') as cuttingTime,`cutting_comments`.`comment` as cuttingComment, eb.`id` as edgeBandingId, `edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, edgeBandSymbols.`symbol` as edgeBandSymbol, `eb`.`edge_banding_metters_wz` as wzMetters, `eb`.`edge_banding_metters_machine` as machineMetters, `eb`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment, `edge_banding_comments`.`comments` as edgeBandingComment, GROUP_CONCAT(DISTINCT CONCAT_WS(' ', workers.`name`, workers.`surname`) SEPARATOR ',</br>')  as cuttingWorkers FROM `orders_boards` ob LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=ob.`board_symbol_id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=ob.`id`, `boards_signs`, `boards_thickness`, `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_banding_comments` ON eb.`id`=`edge_banding_comments`.`edge_banding_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `cutting_workers`, `workers` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` AND `eb`.`orders_boards_id`=`ob`.`id` AND `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id` AND `cutting_workers`.`worker_id`=`workers`.`id` AND `cutting_workers`.`orders_boards_id`=ob.`id` GROUP BY boardId, edgeBandingId")){
			$boards = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}

	function returnEdgeBandingDetails($boardId){
		$edgeBanding = array();
		if($result = $this -> dbo -> query("SELECT `edge_banding`.`id` as edgeBandingId,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, `boards_symbols`.`symbol` as edgeBandSymbol, `edge_banding`.`edge_banding_metters_wz` as wzMetters, `edge_banding`.`edge_banding_metters_machine` as machineMetters, `edge_banding`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment FROM `edge_banding` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=`edge_banding`.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `boards_symbols` WHERE `edge_banding`.`orders_boards_id`={$boardId} AND `edge_banding`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `edge_banding`.`edge_band_type_id`=`edge_band_types`.`id` AND `boards_symbols`.`id`=`edge_banding`.`board_symbol_id`")){
			$edgeBanding = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBanding;
	}
	
	function findOrderByDocumentNumber($documentNumber){
		$query = $this -> dbo -> prepare ("SELECT `orders`.`id` as orderId, `orders`.`saw_number`,`customers`.`id` as customerId,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as phone,`customers_temp`.`name` as tempCustomerName, `customers_temp`.`phone` as tempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `document_number`=:documentNumber");
		$query -> bindValue(':documentNumber', $documentNumber, PDO::PARAM_STR);
		if(!$query -> execute()){
			return null;
		}
		
		if(!$result = $query -> fetch(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}

	function updateEdgeBandingComment(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1 || !isset($_POST['edgeBandingComment'])){
			return FORM_DATA_MISSING;
		}
		
		if($_POST['edgeBandingComment'] == ''){
			return $this->deleteEdgeBandingComment($_POST['edgeBandingId']);
		}
		
		return $this->setEdgeBandingComment($_POST['edgeBandingId'], $_POST['edgeBandingComment']);
	}
	
	function updateEdgeBandingMachineMetters(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1 || !isset($_POST['edgeBandingMachineMetters']) || $_POST['edgeBandingMachineMetters'] == '' || (float)($_POST['edgeBandingMachineMetters']) < 0.00){
			return FORM_DATA_MISSING;
		}
		
		return $this->setMachineMetters($_POST['edgeBandingId'], $_POST['edgeBandingMachineMetters']);
	}
	
	function setEdgeBanding(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1 || !isset($_POST['edgeBandingMachineMetters']) || $_POST['edgeBandingMachineMetters'] == '' || (float)($_POST['edgeBandingMachineMetters']) < 0.00 || !isset($_POST['workers'])){
			return FORM_DATA_MISSING;
		}
		$edgeBandingId = filter_input(INPUT_POST, 'edgeBandingId');
		$machineMetters = filter_input(INPUT_POST, 'edgeBandingMachineMetters');
		
		foreach($_POST['workers'] as $worker){
			if((int)$worker < 1){
				return FORM_DATA_MISSING;
			}
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		if($this -> setEdgeBandingCompletionDate($edgeBandingId) != ACTION_OK){
			return ACTION_FAILED;
		}
		if($this -> setMachineMetters($edgeBandingId, $machineMetters) != ACTION_OK){
			return ACTION_FAILED;
		}
		if($this -> setEdgeBandingWorkers($edgeBandingId, $_POST['workers']) != ACTION_OK){
			return ACTION_FAILED;
		}
		if(isset($_POST['edgeBandingComment']) && $_POST['edgeBandingComment'] != ''){
			if($this -> setEdgeBandingComment($edgeBandingId, $_POST['edgeBandingComment']) != ACTION_OK){
				return ACTION_FAILED;
			}
		}
		
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	
	}
	
	function resetEdgeBanding(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if($this -> setMachineMetters($_POST['edgeBandingId'], 0) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this -> resetEdgeBandingCompletionDate($_POST['edgeBandingId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this -> deleteEdgeBandingComment($_POST['edgeBandingId']) != ACTION_OK){
			return NO_PERMISSION;
		}
		
		if($this -> deleteEdgeBandingWorkers($_POST['edgeBandingId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		
		return ACTION_OK;
	}
	
	function showNotCutOrderList(){
		$orderList = $this -> returnNotCutOrders();

		include 'scripts/notCutOrderListForEBMachineScripts.php';
		include 'templates/notCutOrderListForEBMachine.php';
	}
	
	function showOrderList(){
		
		$orderList = $this->returnOutstandingOrders();

		include 'scripts/orderListForEBMachineScripts.php';
		include 'templates/orderListForEBMachine.php';
	}
	
	
	function showLastMadeOrders(){
		
		$positionsAmount = 30;
		
		$orderList = $this -> showLastEdgeBandingOrders($positionsAmount);
		
		include 'scripts/lastMadeOrdersForEBMachineScript.php';
		include 'templates/lastMadeOrdersForEBMachine.php';
	}
	
	function showOrderEdgeBandingForm(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		$orderId = filter_input(INPUT_POST, 'orderId');
		$documentNumber = filter_input(INPUT_POST, 'documentNumber');
		$customerName = filter_input(INPUT_POST, 'customerName');
		$phone = filter_input(INPUT_POST, 'phone');
		$sawNumber = filter_input(INPUT_POST, 'sawNumber');
		$comment = filter_input(INPUT_POST, 'comment');

		$boards 	= $this -> returnOrderDetails($orderId);
		
		$smsManager = new SMSManager($this -> dbo);
		$smsContent = $smsManager -> returnActiveSMSContent();
		
		$workers = new Workers($this -> dbo);
		$workers 	= $workers -> returnEdgeBandingWorkers();
		
		include 'scripts/orderEdgeBandingFormScripts.php';
		include 'templates/orderEdgeBandingForm.php';
	}
	
	function showOrderSearchingForm(){
		$documentType = filter_input (INPUT_POST, 'documentType');
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');
		$documentBranch = filter_input (INPUT_POST, 'documentBranch');
		
		include 'templates/orderSeachringFormForSawAndEBMachine.php';
	}
	
	function showSearchResult(){
		if (!isset($_POST['documentType']) || $_POST['documentType'] =='' || !isset($_POST['documentNumber']) || $_POST['documentNumber'] == "" || !isset($_POST['documentBranch']) || $_POST['documentBranch'] == "" ){
			return FORM_DATA_MISSING;
		}
		
		$_POST['documentNumber'] = str_pad($_POST['documentNumber'], 6, "0", STR_PAD_LEFT);
		$documentNumber = $_POST['documentType'] . $_POST['documentNumber'] . $_POST['documentBranch'];
		
		
		if($order = $this -> findOrderByDocumentNumber($documentNumber)){
			$orderId = $order -> orderId;
			$comment = $order -> orderComment;
			$sawNumber  = $order -> saw_number;
			if($order -> customerId != 1){
				$customerName = $order->customerName . ' ' . $order->customerSurname;
				$phone = $order -> phone;
			}
			else{
				$customerName = $order -> tempCustomerName;
				$phone = $order -> tempPhone;
			}
			
			$boards 	= $this -> returnOrderDetails($orderId);
		
			$workers = new Workers($this -> dbo);
			$workers 	= $workers -> returnEdgeBandingWorkers();
		
			include 'scripts/orderEdgeBandingFormScripts.php';
			include 'templates/orderEdgeBandingForm.php';
		}
		else{
			include 'templates/noResults.php';
		}
	
	}
}
?>