<?php
class EdgeBandingMachine
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
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
	
	function returnOutstandingOrders(){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`id` NOT IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL AND `orders_boards`.`board_sign_id` <> '1') AND `orders`.`id` IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `edge_banding`.`edge_banding_metters_machine`=0) ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$boards = array();
	//	$query = $this -> dbo -> prepare ("SELECT `orders_boards`.`id` as boardId, `boards_signs`.`sign` as boardSign, `boards_symbols`.`symbol` as boardSymbol, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `orders_boards`.`cutting_metters`, `orders_boards`.`cutting_completion_date`,`cutting_comments`.`comment` as cuttingComment, `edge_banding`.`id` as edgeBandingId,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, `boards_symbols`.`symbol` as edgeBandSymbol, `edge_banding`.`edge_banding_metters_wz` as wzMetters, `edge_banding`.`edge_banding_metters_machine` as machineMetters, `edge_banding`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment FROM `orders_boards` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `boards_signs`, `boards_thickness`, `boards_structures`, `edge_banding` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=`edge_banding`.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `boards_symbols` WHERE `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id` AND `edge_banding`.`orders_boards_id`=`orders_boards`.`id` AND `edge_banding`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `edge_banding`.`edge_band_type_id`=`edge_band_types`.`id` AND `boards_symbols`.`id`=`edge_banding`.`board_symbol_id`");
		
		if($result = $this->dbo->query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, boardSymbols.`symbol` as boardSymbol, `boards_thickness`.`thickness`, `boards_structures`.`structure`, ob.`amount`, ob.`cutting_metters`, `cutting_comments`.`comment` as cuttingComment, eb.`id` as edgeBandingId,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, edgeBandSymbols.`symbol` as edgeBandSymbol, `eb`.`edge_banding_metters_wz` as wzMetters, `eb`.`edge_banding_metters_machine` as machineMetters, `eb`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment, `edge_banding_comments`.`comments` as edgeBandingComment, `workers`.`name` as cuttingWorkers, `workers`.`id` as cuttingWorkersId FROM `orders_boards` ob LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=ob.`board_symbol_id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=ob.`id`, `boards_signs`, `boards_thickness`, `boards_structures`, `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_banding_comments` ON eb.`id`=`edge_banding_comments`.`edge_banding_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `cutting_workers`, `workers` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` AND ob.`board_structure_id`=`boards_structures`.`id` AND `eb`.`orders_boards_id`=`ob`.`id` AND `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id` AND `cutting_workers`.`worker_id`=`workers`.`id` AND `cutting_workers`.`orders_boards_id`=ob.`id`")){
			$boards = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}
	
	function groupOrderDetailsByBoardsId($boards){
		$tempArray = array();
		if(!empty($boards)){
			$boardId = $boards[0] -> boardId;
			$tempArray[$boardId]['boardId'] = $boardId;
			$tempArray[$boardId]['boardName'] = $boards[0] -> boardSign . ($boards[0] -> thickness + 0) .' - ' . $boards[0] -> boardSymbol;
			if($boards[0] -> structure !='---'){
				$tempArray[$boardId]['boardName'] .=' ' . $boards[0] -> structure;
			}
			$tempArray[$boardId]['boardAmount'] = $boards[0] -> amount; ' szt.' . '  cięcie -> ' . ($boards[0] -> cutting_metters + 0) . ' m';
			$tempArray[$boardId]['boardCuttingComment'] = $boards[0] -> cuttingComment;
			$tempArray[$boardId]['boardCuttingMetters'] = ($boards[0] -> cutting_metters + 0);
			$i = 0;
			$j = 0;
			$eId =0;
			foreach($boards as $board){
				
				if($board->edgeBandingId !=$eId){
					$eId = $board->edgeBandingId;
					foreach($board as $key => $value){
						if($key == 'boardId' && $value != $boardId){
							$boardId = $value;
							$j = 0;
							$tempArray[$boardId]['boardId'] = $boardId;
							$tempArray[$boardId]['boardName'] = $boards[$i] -> boardSign . ($boards[$i] -> thickness + 0) .'-' . $boards[$i] -> boardSymbol;
							if($boards[$i] -> structure !='---'){
								$tempArray[$boardId]['boardName'] .=' ' . $boards[$i] -> structure;
							}
							$tempArray[$boardId]['boardAmount'] = $boards[$i] -> amount; ' szt.' . '  cięcie -> ' . ($boards[$i] -> cutting_metters + 0) . ' m';
							$tempArray[$boardId]['boardCuttingComment'] = $boards[$i] -> cuttingComment;
							$tempArray[$boardId]['boardCuttingMetters'] = ($boards[$i] -> cutting_metters + 0);
						}
						if($key == 'edgeBandingId' || $key == 'stickerSymbol'  || $key == 'edgeBandType' || $key == 'edgeBandSymbol'  || $key == 'wzMetters'  || $key == 'machineMetters'  || $key == 'edgeBandingDate'  || $key == 'edgeBandComment' || $key == 'edgeBandingComment'){
							$tempArray [$boardId]['edgeBanding'][$j][$key] = $value;
						}
					}
					$i++;
					$j++;
				}
				$tempArray[$boardId]['cuttingWorkers'][$board->cuttingWorkersId] = $board->cuttingWorkers;
			}
		}
		return $tempArray;
	}
	
	function returnEdgeBandingDetails($boardId){
		$edgeBanding = array();
		if($result = $this->dbo->query("SELECT `edge_banding`.`id` as edgeBandingId,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, `boards_symbols`.`symbol` as edgeBandSymbol, `edge_banding`.`edge_banding_metters_wz` as wzMetters, `edge_banding`.`edge_banding_metters_machine` as machineMetters, `edge_banding`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment FROM `edge_banding` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=`edge_banding`.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `boards_symbols` WHERE `edge_banding`.`orders_boards_id`={$boardId} AND `edge_banding`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `edge_banding`.`edge_band_type_id`=`edge_band_types`.`id` AND `boards_symbols`.`id`=`edge_banding`.`board_symbol_id`")){
			$edgeBanding = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBanding;
	}
	
	/*function returnOrderDetails2($edgeBandingId){
		$orders = array();
		if($result = $this->dbo->query("SELECT `edge_banding`.`id`,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, `boards_symbols`.`symbol` as edgeBandSymbol,  `edge_banding`.`edge_banding_metters_wz` as wzMetters, `edge_banding`.`edge_banding_metters_machine` as machineMetters, `edge_band_comments`.`comments` as edgeBandComment,  `boards_signs`.`sign`, `boards_symbols`.`symbol` as boardSymbol, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `cutting_comments`.`comment` as cuttingComment FROM `orders_boards` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `edge_banding` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=`edge_banding`.`id`, `boards_signs`, `boards_symbols`, `boards_thickness`, `boards_structures`, `edge_band_sticker_symbols`, `edge_band_types` WHERE `orders_boards`.`id`=`edge_banding`.`orders_boards_id` AND `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id`")){
			$boards = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}*/
	
	/*function returnOutstandingOrdersOfTheSaw2(){   
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, `states`.`name` as state FROM `orders` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `states` WHERE `orders`.`customer_id`=`customers`.`id` AND `orders`.`state_id`=`states`.`id` AND `orders`.`state_id`='0' AND `orders`.`saw_number`={$this->sawNumber} ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}*/
	
	
	
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
		
		if(!$this->dbo->beginTransaction()){
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
		
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		
		if($this -> setMachineMetters($_POST['edgeBandingId'], 0) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this->resetEdgeBandingCompletionDate($_POST['edgeBandingId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if($this->deleteEdgeBandingComment($_POST['edgeBandingId']) != ACTION_OK){
			return NO_PERMISSION;
		}
		
		if($this->deleteEdgeBandingWorkers($_POST['edgeBandingId']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}
		
		return ACTION_OK;
	}
	
	function returnEdgeBandingWorkers(){
		$edgeBandingWorkers = array();
		if($result = $this->dbo->query("SELECT `id`, `name` FROM `workers` WHERE `id` IN (SELECT `worker_id` FROM `workers_stands` WHERE `stand_id`= 3)")){
			$edgeBandingWorkers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandingWorkers;
	}
	
	function showOrderList(){
		//$this->setOrderListPeriod();
		$orderList = $this->returnOutstandingOrders();
		
		include 'scripts/orderListForEBMachineScripts.php';
		include 'templates/orderListForEBMachine.php';
	}
	
	/*function showTheOrder($orderId, $orderTitle){
		$boards 	= $this -> returnOrderDetails($orderId);
		$boards 	= $this -> groupOrderDetailsByBoardsId($boards);
		$workers 	= $this -> returnEdgeBandingWorkers();
		
		include 'scripts/orderEdgeBandingFormScripts.php';
		include 'templates/orderEdgeBandingForm.php';
	}*/
	
	function showOrderEdgeBandingForm(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		$orderId = filter_input(INPUT_POST, 'orderId');
		$orderTitle = filter_input(INPUT_POST, 'orderName');
		$phone = filter_input(INPUT_POST, 'phone');
		//$this -> showTheOrder($orderId, $orderTitle);
		$boards 	= $this -> returnOrderDetails($orderId);
		$boards 	= $this -> groupOrderDetailsByBoardsId($boards);
		$workers 	= $this -> returnEdgeBandingWorkers();
		
		include 'scripts/orderEdgeBandingFormScripts.php';
		include 'templates/orderEdgeBandingForm.php';
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