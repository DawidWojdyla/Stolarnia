<?php
class Orders
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}
	
	function checkIfDocumentNumberExistsInTheDatabase($documentNumber, $year){
		$query = $this->dbo->prepare("SELECT `id` FROM `orders` WHERE `document_number`=:documentNumber AND YEAR(`admission_date`)=:year");
		$query -> bindValue (':documentNumber', $documentNumber, PDO::PARAM_STR);
		$query -> bindValue (':year', $year, PDO::PARAM_STR);
		$query -> execute();
		if ($query -> rowCount()){
			return true;
		}
		return false;
	}
	
	function addNewOrderToTheDatabase(){
		if( !$this->dbo){ return SERVER_ERROR;}
		
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		if($_POST['documentType'] != '4'){
			$documentNumberString = $_POST['documentType'].$_POST['documentNumber'].$_POST['documentBranch'];
			if($this->checkIfDocumentNumberExistsInTheDatabase($documentNumberString, substr($_POST['admissionDate'], 0, 4))){
				return DOCUMENT_NUMBER_ALREADY_EXISTS;
			}
		}
		else{
			$documentNumberString = NULL;
		}
		$query  = $this->dbo -> prepare ("INSERT INTO `orders` VALUES (NULL, :documentNumber, :customerId, :workerId, :sawNumber, :admissionDate, :orderCompletionDate)");
		$query -> bindValue (':documentNumber', $documentNumberString, PDO::PARAM_STR);
		$query -> bindValue (':customerId', $_POST['customerId'], PDO::PARAM_INT);
		$query -> bindValue (':workerId', $_POST['sellerId'], PDO::PARAM_INT);
		$query -> bindValue (':sawNumber', $_POST['sawNumber'], PDO::PARAM_INT);
		$query -> bindValue (':admissionDate', $_POST['admissionDate'], PDO::PARAM_STR);
		$query -> bindValue (':orderCompletionDate', $_POST['orderCompletionDate'], PDO::PARAM_STR);
		
		if (!$query -> execute()){
			return SERVER_ERROR;
		}
		
		if(!$this->dbo->query("SET @orderId=LAST_INSERT_ID()")){
			return SERVER_ERROR;
		}
		
		if($_POST['customerId'] == '1'){
			$customerName = filter_input(INPUT_POST, 'customerName');
			$query  = $this->dbo -> prepare ("INSERT INTO `customers_temp` VALUES (@orderId, :customerName, :customerPhone)");
			$query -> bindValue (':customerName',$customerName, PDO::PARAM_STR);
			$query -> bindValue (':customerPhone', $_POST['customerPhone'], PDO::PARAM_STR);
			if (!$query -> execute()){
				return SERVER_ERROR;
			}
		}
		
		if($_POST['orderComment'] != ''){
			$query  = $this->dbo -> prepare ("INSERT INTO `orders_comments` VALUES (@orderId, :comment)");
			$query -> bindValue (':comment', $_POST['orderComment'], PDO::PARAM_STR);
			if (!$query -> execute()){
				return SERVER_ERROR;
			}
		}
		foreach($_SESSION['positions'] as $position){
			$query  = $this->dbo -> prepare ("INSERT INTO `orders_boards` VALUES (NULL, @orderId, :boardSignId, :boardSymbolId, :boardStructureId, :boardThicknessId, :amount, :cuttingMetters, NULL)");		
			$query -> bindValue (':boardSignId', $position['boardSignId'], PDO::PARAM_INT);
			if(!isset($position['boardSymbolId'])){ 
				$query -> bindValue (':boardSymbolId', 1, PDO::PARAM_INT);
				$query -> bindValue (':boardStructureId', 1, PDO::PARAM_INT);
				}
			else{
				$query -> bindValue (':boardSymbolId', $position['boardSymbolId'], PDO::PARAM_INT);
				$query -> bindValue (':boardStructureId', $position['boardStructureId'], PDO::PARAM_INT);
			}
			
			$query -> bindValue (':boardThicknessId', $position['boardThicknessId'], PDO::PARAM_INT);
			$query -> bindValue (':amount', $position['amount'], PDO::PARAM_STR);
			$query -> bindValue (':cuttingMetters', $position['cuttingMetters'], PDO::PARAM_STR);
				
			if(!$query->execute()){
				return SERVER_ERROR;
			}
			
			if ($edgeBandsAmount = count($position['edgeBandTypesId'])){
				if(!$this->dbo->query("SET @ordersBoardsId=LAST_INSERT_ID()")){
					return SERVER_ERROR;
				}
				for($i = 0; $i < $edgeBandsAmount; $i++){
					$query  = $this->dbo -> prepare ("INSERT INTO `edge_banding` VALUES (NULL, @ordersBoardsId, :edgeBandStickerSymbolId, :edgeBandTypeId, :edgeBandingBoardSymbolId, :edgeBandingMettersWz, 0, NULL)");
					$query -> bindValue (':edgeBandStickerSymbolId', $position['edgeBandsStickersId'][$i], PDO::PARAM_INT);
					$query -> bindValue (':edgeBandTypeId', $position['edgeBandTypesId'][$i], PDO::PARAM_INT);
					$query -> bindValue (':edgeBandingMettersWz', $position['edgeBandingMetters'][$i], PDO::PARAM_STR);	
					$query -> bindValue (':edgeBandingBoardSymbolId', $position['edgeBandingBoardSymbolsId'][$i], PDO::PARAM_INT);
					
					if(!$query->execute()){
						return SERVER_ERROR;
					}
					
					if(isset($position['edgeBandComments'][$i]) && $position['edgeBandComments'][$i] != ''){
						$query  = $this->dbo -> prepare ("INSERT INTO `edge_band_comments` VALUES (LAST_INSERT_ID(), :comment)");
						$query -> bindValue (':comment', $position['edgeBandComments'][$i] , PDO::PARAM_STR);
						if(!$query->execute()){
							return SERVER_ERROR;
						}
					}
				}
			}
		}
			
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}	
		unset($_SESSION['documentType']);
		unset($_SESSION['documentBranch']);
		unset($_SESSION['documentNumber']);
		unset($_SESSION['customerId']);
		unset($_SESSION['customerPhone']);
		unset($_SESSION['customerName']);
		unset($_SESSION['sellerId']);
		unset($_SESSION['sawNumber']);
		unset($_SESSION['admissionDate']);
		unset($_SESSION['orderCompletionDate']);
		unset($_SESSION['orderComment']);
		unset($_SESSION['positions']);
		return ACTION_OK;
		}

	function addNewOrder(){
		if (!isset($_POST['documentType']) || !isset($_POST['documentNumber']) || !isset($_POST['documentBranch']) || !isset($_POST['sawNumber']) || !isset($_POST['sellerId']) || !isset($_POST['admissionDate']) || !isset($_POST['orderCompletionDate'])  || !isset($_POST['orderComment']) || !isset($_POST['positions'])){
			return FORM_DATA_MISSING;
		}
		$isAllOk = true;
		
		//NUMER DOKUMENTU
		$checker = new Checker();
		$isAllOk = $checker->checkDocumentNumber();
		$_SESSION ['documentType'] = filter_input (INPUT_POST, 'documentType');
		$_SESSION ['documentBranch'] = filter_input (INPUT_POST, 'documentBranch');
		$_SESSION['documentNumber'] = filter_input(INPUT_POST, 'documentNumber');
		

		//NUMER KLIENTA
		$isAllOk = $checker->checkCustomerData();
		$_SESSION['customerPhone'] = filter_input(INPUT_POST, 'customerPhone');
		$_SESSION['customerName'] = filter_input(INPUT_POST, 'customerName');
		
		//SPRZEDAWCA
		if ($_POST['sellerId'] == '' || (int)$_POST['sellerId'] < 1){
			$isAllOk = false;
			$_SESSION['sellerIdError'] = "Musisz wybrać sprzedawcę!";
		}
		else{
			$_SESSION['sellerId'] = filter_input(INPUT_POST, 'sellerId');
		}
		
		//NUMER PIŁY
		if ($_POST['sawNumber'] != '1' && $_POST['sawNumber'] != '2'){
			$_SESSION['sawNumberError'] = "Musisz podać prawidłowy numer piły!";
			$isAllOk = false;
		}
		else{
			$_SESSION['sawNumber'] = $_POST['sawNumber'];
		}
	
		//DATA PRZYJĘCIA ZAMÓWIENIA
		$isAllOk = $checker->checkAdmissionDate();
		
		//Data realizacji zamówienia	
		$isAllOk = $checker->checkOrderCompletionDate();
		
		//UWAGI DO ZAMÓWIENIA
		$_SESSION['orderComment'] = isset($_POST['orderComment']) ? filter_input(INPUT_POST, 'orderComment') : "";
		
		//PŁYTY - CIĘCIA - KLEJENIA
		$_SESSION['positions'] = $_POST['positions'];
		
		
		foreach($_POST['positions'] as $position){
			foreach($position as $key => $value){
				switch($key){
					case 'boardSignId':
					case 'boardSymbolId':
					case 'boardStructureId':
					case 'boardThicknessId':
						if($value=='' || (int)$value < 1){
							$isAllOk = false;
						}
						break;
					case 'amounts':
					case 'cuttingMetters':
						if($value == '' || (int)$value < 0){
							$isAllOk = false;
						}
						break;
					case 'edgeBandTypesId':
						//$_SESSION['edgeBandingAmount']++;
					case 'edgeBandsStickersId':
					case 'edgeBandingBoardSymbolsId':
						foreach($value as $subValue):
							if($subValue == '' || (int)$subValue < 1){
								$isAllOk = false;
							}
						endforeach;
						break;
					case 'edgeBandingMetters':
						foreach($value as $subValue):
							if($subValue == '' || (int)$subValue <= 0){
								$isAllOk = false;
							}
						endforeach;
						break;
				}
			}
		}
	
		if (!$isAllOk){ return FORM_DATA_MISSING;}
		
		return $this->addNewOrderToTheDatabase();
	}
	
	/*function returnCustomers(){
		$customers = array();
		if($result = $this->dbo->query("SELECT `id`, `name`, `surname`, `phone` FROM customers WHERE `id` <> 1 ORDER BY `surname`")){
			$customers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $customers;
	}*/
	
	function returnSellers(){
		$sellers = array();
		if($result = $this->dbo->query("SELECT `id`, `name` FROM workers WHERE `id` IN (SELECT `worker_id` FROM workers_stands WHERE `stand_id`= 4)")){
			$sellers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $sellers;
	}
	function returnSawLimit($sawNumber){
		if($result = $this->dbo->query("SELECT `board_limit` FROM board_limit_per_one_day WHERE `saw_number`={$sawNumber}")){
			$limit = $result->fetch(PDO::FETCH_NUM);
		}
		return $limit[0];
	}
	
	function returnBoardsSigns(){
		$boardsSigns = array();
		if($result = $this->dbo->query("SELECT `id`, `sign` FROM boards_signs")){
			$boardsSigns = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSigns;
	}
	
	function returnBoardsStructures(){
		$boardsStructures = array();
		if($result = $this->dbo->query("SELECT `id`, `structure` FROM boards_structures")){
			$boardsStructures = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsStructures;
	}
	
	function returnBoardsSymbols(){
		$boardsSymbols = array();
		if($result = $this->dbo->query("SELECT `id`, `symbol` FROM boards_symbols")){
			$boardsSymbols = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSymbols;
	}
	
	function returnBoardsThickness(){
		$boardsThickness = array();
		if($result = $this->dbo->query("SELECT `id`, `thickness` FROM boards_thickness")){
			$boardsThickness = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsThickness;
	}
	
	function returnEdgeBandStickerSymbols(){
		$edgeBandStickerSymbols = array();
		if($result = $this->dbo->query("SELECT `id`, `symbol` FROM edge_band_sticker_symbols")){
			$edgeBandStickerSymbols = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandStickerSymbols;
	}
	
	function returnEdgeBandTypes(){
		$edgeBandTypes = array();
		if($result = $this->dbo->query("SELECT `id`, `type` FROM edge_band_types")){
			$edgeBandTypes = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandTypes;
	}
	
	function returnBoardsAmoutPerDay($sawNumber, $date){
		$boardsAmount = 0;
		if($result = $this -> dbo -> query("SELECT COALESCE (SUM(`amount`), 0) FROM orders_boards WHERE `order_id` IN (SELECT `id` FROM `orders` WHERE `order_completion_date`='{$date}' AND `saw_number`={$sawNumber})")){
			$boardsAmount = $result -> fetch(PDO::FETCH_NUM);
		}
		return ($boardsAmount[0]+0);
	}
	
	function returnCuttingMettersPerDay($sawNumber, $date){
		$cuttingMetters = 0;
		if($result = $this -> dbo -> query("SELECT COALESCE (SUM(`cutting_metters`), 0) FROM orders_boards WHERE `order_id` IN (SELECT `id` FROM `orders` WHERE `order_completion_date`='{$date}' AND `saw_number`={$sawNumber})")){
			$cuttingMetters = $result -> fetch(PDO::FETCH_NUM);
		}
		return ($cuttingMetters[0]+0);
	}
	
	function returnEdgeBandingMettersPerDay($sawNumber, $date){
		$edgeBandingMetters = 0;
		if($result = $this -> dbo -> query("SELECT COALESCE (SUM(`edge_banding_metters_wz`), 0) FROM `edge_banding` WHERE `orders_boards_id` IN (SELECT `id` FROM `orders_boards` WHERE `order_id` IN (SELECT `id` FROM `orders` WHERE `order_completion_date`='{$date}' AND `saw_number`={$sawNumber}))")){
			$edgeBandingMetters = $result -> fetch(PDO::FETCH_NUM);
		}
		return ($edgeBandingMetters[0]+0);
	}
	
	
	function returnLastOrderCompletionDate($sawNumber){
		if($result = $this->dbo->query("SELECT `order_completion_date` FROM orders WHERE id= (SELECT `id` FROM `orders` WHERE `saw_number`={$sawNumber} ORDER BY `id` DESC LIMIT 1)")){
			$lastOrderCompletionDate = $result -> fetch(PDO::FETCH_NUM);
		}
		return $lastOrderCompletionDate[0];
	}
	
	function returnNotCutOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'niepocięte' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL) ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnCutButNotCompletedOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'pocięte' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` WHERE `orders_boards`.`cutting_completion_date` IS NULL) AND `orders`.`id` IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `edge_banding`.`edge_banding_metters_machine`='0') ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnCompletedOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'gotowe' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers` WHERE `orders`.`customer_id`=`customers`.`id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `cutting_completion_date` IS NULL OR `edge_banding`.`edge_banding_metters_machine`='0') ORDER BY `order_completion_date`")){
			$orders = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnOrderDetails($orderId){
		$orderDetails = array();
		if($result = $this->dbo->query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, `boards_signs`.`id` as boardSignId, boardSymbols.`symbol` as boardSymbol, boardSymbols.`id` as boardSymbolId, `boards_thickness`.`thickness`, `boards_thickness`.`id` as boardThicknessId, `boards_structures`.`structure`, `boards_structures`.`id` as boardStructureId, ob.`amount`, ob.`cutting_metters`, ob.`cutting_completion_date`, `cutting_comments`.`comment` as cuttingComment, eb.`id` as edgeBandingId, `edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_sticker_symbols`.`id` as stickerSymbolId, `edge_band_types`.`type` as edgeBandType, `edge_band_types`.`id` as edgeBandTypeId, edgeBandSymbols.`symbol` as edgeBandSymbol, edgeBandSymbols.`id` as edgeBandSymbolId, `eb`.`edge_banding_metters_wz` as wzMetters, `eb`.`edge_banding_metters_machine` as machineMetters, `eb`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment, `edge_banding_comments`.`comments` as edgeBandingComment, cuttingWorkers.`name` as cuttingWorkerName, cuttingWorkers.`id` as cuttingWorkerId, edgeBandingWorkers.`name` as edgeBandingWorkerName, edgeBandingWorkers.`id` as edgeBandingWorkerId FROM `orders_boards` ob LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=ob.`board_symbol_id` LEFT JOIN `cutting_workers` cw on cw.`orders_boards_id`=ob.`id` LEFT JOIN `workers` as cuttingWorkers ON cuttingWorkers.`id`=cw.`worker_id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=ob.`id`, `boards_signs`, `boards_thickness`, `boards_structures`, `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_banding_workers` ebw ON ebw.`edge_banding_id`=eb.`id` LEFT JOIN `workers` as edgeBandingWorkers on edgeBandingWorkers.`id`=ebw.`worker_id` LEFT JOIN `edge_banding_comments` ON eb.`id`=`edge_banding_comments`.`edge_banding_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` AND ob.`board_structure_id`=`boards_structures`.`id` AND `eb`.`orders_boards_id`=`ob`.`id` AND `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id`")){
			$orderDetails = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $orderDetails;
	}
	function returnOrderDetails2($orderId){
		$boards = array();
		if($result = $this->dbo->query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, boardSymbols.`symbol` as boardSymbol, `boards_thickness`.`thickness`, `boards_structures`.`structure`, ob.`amount`, ob.`cutting_metters`, ob.`cutting_completion_date`,`cutting_comments`.`comment` as cuttingComment, eb.`id` as edgeBandingId,`edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, edgeBandSymbols.`symbol` as edgeBandSymbol, `eb`.`edge_banding_metters_wz` as wzMetters, `eb`.`edge_banding_metters_machine` as machineMetters, `eb`.`edge_banding_completion_date` as edgeBandingDate, `edge_band_comments`.`comments` as edgeBandComment, `edge_banding_comments`.`comments` as edgeBandingComment, `workers`.`name` as cuttingWorkers, `workers`.`id` as cuttingWorkersId FROM `orders_boards` ob LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=ob.`board_symbol_id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=ob.`id`, `boards_signs`, `boards_thickness`, `boards_structures`, `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_banding_comments` ON eb.`id`=`edge_banding_comments`.`edge_banding_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types`, `cutting_workers`, `workers` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` AND ob.`board_structure_id`=`boards_structures`.`id` AND `eb`.`orders_boards_id`=`ob`.`id` AND `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id` AND `cutting_workers`.`worker_id`=`workers`.`id` AND `cutting_workers`.`orders_boards_id`=ob.`id`")){
			$boards = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}

	/*function returnOrderDetails($orderId){
		$orders = array();
		if($result = $this->dbo->query("SELECT `orders_boards`.`id` as boardId, `boards_signs`.`sign`, `boards_symbols`.`symbol`, `boards_thickness`.`thickness`, `boards_structures`.`structure`, `orders_boards`.`amount`, `orders_boards`.`cutting_metters`, `orders_boards`.`cutting_completion_date`,`cutting_comments`.`comment` as cuttingComment, `edge_banding`.`id` as edgeBandingId FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=`orders_boards`.`id`, `boards_signs`, `boards_symbols`, `boards_thickness`, `boards_structures` WHERE `orders_boards`.`order_id`={$orderId} AND `orders_boards`.`board_sign_id`=`boards_signs`.`id` AND `orders_boards`.`board_symbol_id`=`boards_symbols`.`id` AND `orders_boards`.`board_thickness_id`=`boards_thickness`.`id` AND `orders_boards`.`board_structure_id`=`boards_structures`.`id` GROUP BY boardId")){
			$boards = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $boards;
	}*/
	function findOrders($conditions){
		$query = "SELECT `orders`.`id` as orderId, `orders`.`document_number`, `workers`.`name` as sellerName, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `customers`.`id` as customerId,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as phone,`customers_temp`.`name` as tempCustomerName, `customers_temp`.`phone` as tempPhone, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `workers`.`id`=`orders`.`worker_id` AND `orders`.`customer_id`=`customers`.`id`";
		$query .= $conditions;
		$query .= " ORDER BY `orders`.`admission_date` DESC LIMIT 50";
		if(!$query = $this -> dbo -> query ($query)){
			return null;
		}
		
		if(!$result = $query -> fetchAll(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}
	
	
	/*function returnOrdersOfPeriod(){
		
		$notCutOrders = $this -> returnNotCutOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		//$cutButNotCompletedOrders = $this -> returnCutButNotCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		$completedOrders = $this -> returnCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		
		return $orders;
	}*/
	
	function returnPotentialOrderCompletionDate($sawNumber){
		$date = $this -> returnLastOrderCompletionDate($sawNumber);
		$boardsAmountOfTheDay = $this -> returnBoardsAmoutPerDay($sawNumber, $date);
		
		if($boardsAmountOfTheDay >= $_SESSION['sawLimits'][$sawNumber]){
			// zwróć datę następnego dnia jeśli jest roboczy
			$checker = new Checker();
			do{
				$date = date('Y-m-d', strtotime($date. ' +1 day'));
			}while(!$checker -> isThatDateWorkingDay($date));
		}
		return $date;
	}
	
	function showOrderAddingForm(){
		
		$customers = new Customers($this->dbo);
		
		$customerList = $customers -> returnCustomerList();
		$sellers = $this -> returnSellers();
		if(!isset($_SESSION['sawLimits'])){
			$_SESSION['sawLimits'][1] = $this -> returnSawLimit(1);
			$_SESSION['sawLimits'][2] = $this -> returnSawLimit(2);
		}
		
		$potentialOrderCompletionDates[1] = $this -> returnPotentialOrderCompletionDate(1);
		$potentialOrderCompletionDates[2] = $this -> returnPotentialOrderCompletionDate(2);
		
		//$boardsAmount = $this -> returnBoardsAmoutPerDay(1, $potentialOrderCompletionDates[1]);
		
		$boardsSigns = $this -> returnBoardsSigns();
		$boardsStructures = $this -> returnBoardsStructures();
		$boardsSymbols = $this -> returnBoardsSymbols();
		$boardsThickness = $this -> returnBoardsThickness();
		
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbols();
		$edgeBandTypes = $this -> returnEdgeBandTypes();
		//$lastOrderCompletionDate = $this -> returnLastOrderCompletionDate();
		
		include 'scripts/orderAddingFormScripts.php';
		include 'templates/orderAddingForm.php';
	}
	
	function setOrderListPeriod(){
		$today = date('Y-m-d');
		//$threeDaysAgo = date('Y-m-d', strtotime($today. '-3 days'));
		//$dayAfterTomorrow = date('Y-m-d', strtotime($today. '+2 days'));
		$threeDaysAgo = date('Y-m-d', strtotime($today. '-3 weekdays'));
		$inThreeDays = date('Y-m-d', strtotime($today. '+3 weekdays'));
		$_SESSION['dateFrom'] = $threeDaysAgo;
		$_SESSION['dateTo'] = $inThreeDays;
		
		if(isset($_POST['dateFrom']) && $_POST['dateFrom'] != '' && isset($_POST['dateTo']) && $_POST['dateTo'] != ''){
			$checker = new Checker();
			if ($checker->checkOrderListPeriodDates()){
				$_SESSION['dateFrom'] = filter_input(INPUT_POST, 'dateFrom');
				$_SESSION['dateTo'] = filter_input(INPUT_POST, 'dateTo');
			}
		}
	}
	
	function showOrderList(){
		$orderLists = array();
		//funkcja odpowiedzialna za wyświetlenie listy zleceń
		$this->setOrderListPeriod();
		$orderLists[0] = $this -> returnNotCutOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		$orderLists[1]  = $this -> returnCutButNotCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		$orderLists[2] = $this -> returnCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		
		include 'scripts/orderListForShopScripts.php';
		include 'templates/orderListForShop.php';
	}
	
	function showOrderSearchingForm(){
		$documentType = filter_input (INPUT_POST, 'documentType');
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');
		$documentBranch = filter_input (INPUT_POST, 'documentBranch');
		$customerName = filter_input (INPUT_POST, 'customerName');
		$customerPhone = filter_input (INPUT_POST, 'customerPhone');
		
		include 'scripts/orderSearchingFormForShopScripts.php';
		include 'templates/orderSearchingFormForShop.php';
	}
	
	function showSearchResult(){
		if (!isset($_POST['documentNumber']) || !isset($_POST['documentNumber']) || !isset($_POST['documentBranch']) || !isset($_POST['customerName']) || !isset($_POST['customerPhone'])){
			return FORM_DATA_MISSING;
		}
		$condition1 = "";
		$condition2 = "";
		$condition3 = "";
		if($_POST['documentNumber'] !=''){
			$_POST['documentNumber'] = str_pad($_POST['documentNumber'], 6, "0", STR_PAD_LEFT);
			$document = $_POST['documentType'] . $_POST['documentNumber'] . $_POST['documentBranch'];
			$condition1 = " AND `orders`.`document_number`='" . $document ."'";
			}
		if($_POST['customerName'] != ""){
			$condition2 = " AND (CONCAT_WS(' ', `customers`.`name`, `customers`.`surname`) LIKE '%" . $_POST['customerName'] . "%' OR `customers_temp`.`name` LIKE '%" . $_POST['customerName'] . "%')"; 
		}
		if($_POST['customerPhone'] != ""){
			$condition3 = " AND (`customers`.`phone`='" . $_POST['customerPhone'] . "' OR `customers_temp`.`phone`='" . $_POST['customerPhone'] . "')"; 
		}
		$conditions = $condition1 . $condition2 . $condition3;
		if($conditions == ""){
			include 'templates/noData.php';
		}
		else{
			if($orders = $this -> findOrders($conditions)){
				//$boards = $this -> returnOrderDetails($order -> orderId);
				include 'scripts/orderSearchResultScripts.php';
				include 'templates/orderSearchResult.php';
			}
			else{
				include 'templates/noResults.php';
			}
		}
	}
	
	function returnAmountsOfPeriod(){
		if (!isset($_POST['sawNumber']) || $_POST['sawNumber'] =='' || !isset($_POST['date']) || $_POST['date'] == ''){
			return 'Brak danych';
		}
		//$date = date('Y-m-d',  strtotime($_POST['date']));
		$amounts = array();
		$amounts['boardsAmount'] = $this -> returnBoardsAmoutPerDay($_POST['sawNumber'], $_POST['date']);
		$amounts['cuttingMetters'] = $this -> returnCuttingMettersPerDay($_POST['sawNumber'], $_POST['date']);
		$amounts['edgeBandingMetters'] = $this -> returnEdgeBandingMettersPerDay($_POST['sawNumber'], $_POST['date']);		
		
		$jsonAmounts = json_encode($amounts);
		return $jsonAmounts;
		//return $this -> returnBoardsAmoutPerDay(1, '2020-01-25');
		//return $_POST['date'];
	}
	
	function showOrderDetails(){
		if (!isset($_POST['orderId']) || $_POST['orderId'] ==''){
			return FORM_DATA_MISSING;
		}
		$orderId = intval($_POST['orderId']);
		
		$order = $this -> returnOrderDetails($orderId);
		
		include 'scripts/orderDetailsScripts.php';
		include 'templates/orderDetails.php';
	}
}
?>