<?php
class Orders
{
	private $dbo = null;
	
	function __construct($dbo)
	{
		$this->dbo = $dbo;
	}
	
	function addNewOrderToTheBase(){
		if( !$this->dbo){ return SERVER_ERROR;}
		
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		if($_POST['documentType'] != '4'){
			$documentNumberString = $_POST['documentType'].$_POST['documentNumber'].$_POST['documentBranch'];
		}
		else{
			$documentNumberString = NULL;
		}
		$query  = $this->dbo -> prepare ("INSERT INTO `orders` VALUES (NULL, :documentNumber, :customerId, :workerId, :sawNumber, :admissionDate, :orderCompletionDate, 0)");
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
		
		if($_POST['orderComment'] != ''){
			$query  = $this->dbo -> prepare ("INSERT INTO `orders_comments` VALUES (@orderId, :comment)");
			$query -> bindValue (':comment', $_POST['orderComment'], PDO::PARAM_STR);
			if (!$query -> execute()){
				return SERVER_ERROR;
			}
		}
		foreach($_SESSION['positions'] as $position){
			$query  = $this->dbo -> prepare ("INSERT INTO `orders_boards` VALUES (NULL, @orderId, :boardSignId, :boardSymbolId, :boardStructureId, :boardThicknessId, :amount, :cuttingMetters, 0)");		
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
					$query  = $this->dbo -> prepare ("INSERT INTO `edge_banding` VALUES (NULL, @ordersBoardsId, :edgeBandStickerSymbolId, :edgeBandTypeId, :edgeBandingBoardSymbolId, :edgeBandingMettersWz, NULL)");
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
		$isAllOk = $checker->checkCustomerId();
		
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
					case 'boardSignsId':
					case 'boardSymbolId':
					case 'boardStructuresId':
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
		
		return $this->addNewOrderToTheBase();
	}
	
	function returnCustomers(){
		$customers = array();
		if($result = $this->dbo->query("SELECT `id`, `name`, `surname`, `phone` FROM customers ORDER BY `surname`")){
			$customers = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $customers;
	}
	
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
	
	function showOrderAddingForm(){
		
		$customers = $this -> returnCustomers();
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
	
	function showOrderListForShop(){
		//funkcja odpowiedzialna za wyświetlenie listy zleceń
	}
}
?>