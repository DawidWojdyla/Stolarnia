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
	
	function returnBoardsAmoutForDay($sawNumber, $date){
		$boardsAmount = null;
		if($result = $this->dbo->query("SELECT SUM(`amount`) FROM orders_boards WHERE `order_id` IN (SELECT `id` FROM `orders` WHERE `order_completion_date`='{$date}' AND `saw_number`={$sawNumber})")){
			$boardsAmount = $result->fetch();
		}
		return $boardsAmount;
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
	
	/*function returnOrdersOfPeriod(){
		
		$notCutOrders = $this -> returnNotCutOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		//$cutButNotCompletedOrders = $this -> returnCutButNotCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		$completedOrders = $this -> returnCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		
		return $orders;
	}*/
	
	function showOrderAddingForm(){
		
		$customers = new Customers($this->dbo);
		
		$customerList = $customers -> returnCustomerList();
		$sellers = $this -> returnSellers();
		
		$boardsSigns = $this -> returnBoardsSigns();
		$boardsStructures = $this -> returnBoardsStructures();
		$boardsSymbols = $this -> returnBoardsSymbols();
		$boardsThickness = $this -> returnBoardsThickness();
		
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbols();
		$edgeBandTypes = $this -> returnEdgeBandTypes();
		
		include 'scripts/orderAddingFormScripts.php';
		include 'templates/orderAddingForm.php';
	}
	
	function setOrderListPeriod(){
		$today = date('Y-m-d');
		//$threeDaysAgo = date('Y-m-d', strtotime($today. '-3 days'));
		//$dayAfterTomorrow = date('Y-m-d', strtotime($today. '+2 days'));
		$fiveDaysAgo = date('Y-m-d', strtotime($today. '-3 days'));
		$inFiveDays = date('Y-m-d', strtotime($today. '+2 days'));
		$_SESSION['dateFrom'] = $fiveDaysAgo;
		$_SESSION['dateTo'] = $inFiveDays;
		
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
	
		function showOrderSearching(){
		
		include 'scripts/orderSerchingForShopScripts.php';
		include 'templates/orderSearchingFormForShop.php';
	}
}
?>