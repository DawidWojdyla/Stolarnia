<?php
class Orders
{
	private $dbo = null;
	
	function __construct($dbo)
	{
		$this->dbo = $dbo;
	}
	
	function addNewOrderToTheBase(){
		if( !$this->dbo) return SERVER_ERROR;
		
		$query  = $this->dbo -> prepare ("INSERT INTO `orders` VALUES (NULL, :documentNumber, :customerId, :workerId, :sawNumber, :admissionDate, :orderCompletionDate, :stateId)");
		$query -> bindValue (':customerId', $order->getCustomerId(), PDO::PARAM_INT);
		$query -> bindValue (':userId', $order->getUserId(), PDO::PARAM_INT);
		$query -> bindValue (':sawNumber', $order->getSawNumber(), PDO::PARAM_INT);
		$query -> bindValue (':admissionDate', $order->getAdmissionDate(), PDO::PARAM_STR);
		$query -> bindValue (':orderCompletionDate', $order->getOrderCompletionDate(), PDO::PARAM_STR);
		$query -> bindValue (':comments', $order->getComments(), PDO::PARAM_STR);
		
		if ($query -> execute()){
		unset($_SESSION['documentNumberSes']);
		unset($_SESSION['customerIdSes']);
		unset($_SESSION['workerIdSes']);
		unset($_SESSION['sawNumberSes']);
		unset($_SESSION['admissionDateSes']);
		unset($_SESSION['orderCompletionDateSes']);
		unset($_SESSION['commentsSes']);
		return ACTION_OK;
		}
		else return ACTION_FAILED;	
	}
	
	function addNewOrder(){
		if (!isset($_POST['documentType']) || !isset($_POST['documentNumber']) || !isset($_POST['documentBranch']) ||!isset($_POST['admissionDate']) || !isset($_POST['orderCompletionDate'])){
			//return FORM_DATA_MISSING;
		}
		$isAllOk = true;
		
		//NUMER DOKUMENTU
		$checker = new Checker();
		$isAllOk = $checker->checkDocumentNumber();
		

		//NUMER KLIENTA
		$isAllOk = $checker->checkCustomerId();
		
		//UWAGI DO ZAMÓWIENIA
		$_SESSION['orderComment'] = isset($_POST['orderComment']) ? filter_input(INPUT_POST, 'orderComment') : "";
		
		//SPRZEDAWCA
		if (!isset($_POST['workerId']) || $_POST['workerId'] == ''){
			$isAllOk = false;
			$_SESSION['workerIdError'] = "Musisz wybrać sprzedawcę!";
		}
		else{
			$_SESSION['workerId'] = filter_input(INPUT_POST, 'workerId');
		}
		
		//NUMER PIŁY
		if (!isset($_POST['sawNumber']) || $_POST['sawNumber'] != '1' && $_POST['sawNumber'] != '2'){
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
		
		
		//PŁYTY - CIĘCIA - KLEJENIA
		
	
	
		if (!$isAllOk) return FORM_DATA_MISSING;
		
		//$newOrder = new Order(NULL, $_SESSION['loggedUser']->id, $amount, $date, (int)$_POST['paymentMethod'], (int)$_POST['category'], $comment);
		//return $this->addNewExpenseToBase($newExpense);
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
		
		//include 'scripts/orderAddingFormScripts.php';
		include 'templates/orderAddingForm.php';
	}
}
?>