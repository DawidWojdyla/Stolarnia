<?php
class Joinery extends MyDB
{
	public $stand = null;
	
	function __construct($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8'){
		$this->dbo = $this->initDB($host, $user, $password, $dbName, $dbType= 'mysql', $charset ='utf8');
		$this->stand = $this->getActualStand();
	}
	
	function getActualStand(){
		if(isset($_SESSION['stand'])){
			return $_SESSION['stand'];
		}
		else{ 
			return null;
		}
	}
	
	function setMessage($message){
		$_SESSION['message'] = $message;
	}
  
	function getMessage(){
		if(isset($_SESSION['message'])){
			$message = $_SESSION['message'];
			unset($_SESSION['message']);
			return $message;
		}
		else{
			return null;
		}
	}
	
	function hideMessageAfterTime($delay){
		$_SESSION['delay'] = $delay;
	}
	
	function getDelay(){
		if(isset($_SESSION['delay'])){
			$delay = $_SESSION['delay'];
			unset($_SESSION['delay']);
			return $delay;
		}
		else{
			return null;
		}
	}
	
	function login(){
		if( !$this->dbo) return SERVER_ERROR;
		if ($this->stand) return NO_LOGIN_REQUIRED;
		 
		if (!isset($_POST["standId"]) || !isset($_POST["password"])) return FORM_DATA_MISSING;
		
		$standId = (intval)(filter_input(INPUT_POST, 'standId'));
		$password = filter_input(INPUT_POST, 'password');
		
		$passwordLength = mb_strlen($password, 'utf8');
		
		if($standId < 1 || $standId > 4 || $passwordLength < 2 || $passwordLength > 10){
			return ACTION_FAILED;
		}
		
		$query = "SELECT `name`, `password` FROM `stands` WHERE `id`=:standId";
		$PDOstatement = $this->dbo->prepare($query);
		$PDOstatement -> bindValue(':standId', $standId, PDO::PARAM_INT);

		if(!$PDOstatement->execute()){
			return SERVER_ERROR;
		}
		
		if(!$result = $PDOstatement->fetch(PDO::FETCH_NUM)){
		  return ACTION_FAILED;
		}
		
		 //if(!password_verify($password, $result[1])){
			//return ACTION_FAILED;
		 //}
		 if($password != $result[1]){
			 return ACTION_FAILED;
		 }
		 
		$_SESSION['stand'] = new Stand($standId, $result[0]);
		
		return ACTION_OK;
	}
	  
	function logout(){
		if($this->stand == 4 && isset($_SESSION['sawLimits'])){
			unset($_SESSION['sawLimits']);
		}
		$this->stand = null;
		if (isset($_SESSION['stand']))
		unset($_SESSION['stand']);
	}
	
	function addNewOrder(){
		if ($this->stand->id != 4)  return NO_PERMISSION;
		$orders = new Orders ($this->dbo);
		return $orders -> addNewOrder();
	}
	
	function showOrderAddingForm(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 4)  return NO_PERMISSION;
		$orders = new Orders ($this->dbo);
		return $orders->showOrderAddingForm();
	}
	
	
	function showCustomerList(){
		if(!$this->dbo) { return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$customers = new Customers ($this->dbo);
		return $customers->showCustomerList();
	}
	
	function showCustomerAddingForm(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$customers = new Customers ($this->dbo);
		return $customers->showCustomerAddingForm();
	}
	
	function addNewCustomer(){
		if ($this->stand->id != 4){  return NO_PERMISSION; }
		$customers = new Customers ($this->dbo);
		return $customers -> addNewCustomer();
	}
	
	function updateDocumentNumber(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateDocumentNumber();
	}
	
	function updateCustomerId(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCustomerId();
	}
	
	function updateCustomerIdAndRemoveTempData(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCustomerIdAndRemoveTempData();
	}
	
	function updateCustomerTempData(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCustomerTempData();
	}
	
	function updateCustomerIdAndAddTempData(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCustomerIdAndAddTempData();
	}
	
	function updateSawNumber(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateSawNumber();
	}
	
	function updateAdmissionDate(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateAdmissionDate();
	}
	
	function updateCompletionDate(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCompletionDate();
	}
	
	function updateSeller(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateSeller();
	}
	
	function updateOrderComment(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateOrderComment();
	}
	
	function addNewBoard(){
		if(!$this->dbo){ return "ACTION_FAILED"; }
		if ($this->stand->id != 4){ return "ACTION_FAILED"; }
		$orders = new Orders ($this->dbo);
		return $orders -> addNewBoard();
	}
	
	function removeBoard(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> removeBoard();
	}
	
	function updateBoard(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateBoard();
	}
	
	function updateBoardAmount(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateBoardAmount();
	}
	
	function updateCuttingMetters(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateCuttingMetters();
	}
	
	function addNewEdgeBanding(){
		if(!$this->dbo){ return "ACTION_FAILED"; }
		if ($this->stand->id != 4){ return "ACTION_FAILED"; }
		$orders = new Orders ($this->dbo);
		return $orders -> addNewEdgeBanding();
	}
	
	function updateEdgeBanding(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> updateEdgeBanding();
	}
	
	function removeEdgeBanding(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this->dbo);
		return $orders -> removeEdgeBanding();
	}
	
	function setTheBoardCutting(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 1 && $this->stand->id != 2){ return NO_PERMISSION; }
		$saw = new Saw ($this->dbo, $this->stand->id);
		return $saw -> setTheBoardCutting();
		
	}
	
	function setEdgeBanding(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 3){ return NO_PERMISSION; }
		$ebMachine = new EdgeBandingMachine($this->dbo);
		return $ebMachine -> setEdgeBanding();
		
	}
	
	function resetEdgeBanding(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 3){ return NO_PERMISSION; }
		$ebMachine = new EdgeBandingMachine($this->dbo);
		return $ebMachine -> resetEdgeBanding();
	}
	
	function updateEdgeBandingMachineMetters(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 3){ return NO_PERMISSION; }
		$ebMachine = new EdgeBandingMachine ($this->dbo);
		return $ebMachine -> updateEdgeBandingMachineMetters();
	}
	
	function updateEdgeBandingComment(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 3){ return NO_PERMISSION; }
		$ebMachine = new EdgeBandingMachine ($this->dbo);
		return $ebMachine -> updateEdgeBandingComment();
	}
	
	function setTheBoardComment(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 1 && $this->stand->id != 2){ return NO_PERMISSION; }
		$saw = new Saw ($this->dbo, $this->stand->id);
		return $saw -> setTheBoardComment();
	}
	
	function resetTheBoardCutting(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 1 && $this->stand->id != 2){ return NO_PERMISSION; }
		$saw = new Saw ($this->dbo, $this->stand->id);
		return $saw->resetTheBoardCutting();
	}
	
		function showOrderCuttingForm(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 1 && $this->stand->id != 2 ) { return NO_PERMISSION; }
		$saw = new Saw ($this->dbo, $this->stand->id);
		return $saw->showOrderCuttingForm();
	}
	
	function showOrderEdgeBandingForm(){
		if(!$this->dbo) return SERVER_ERROR;
		if ($this->stand->id != 3 ) { return NO_PERMISSION; }
		$edgeBandingMachine = new EdgeBandingMachine ($this->dbo);
		return $edgeBandingMachine->showOrderEdgeBandingForm();
	}
	
	function showLastMadeOrders(){
		if(!$this->dbo){ return SERVER_ERROR; }
		
		switch ($this->stand->id){
			case 1: 
			case 2:
				$saw = new Saw ($this->dbo, $this->stand->id);
				return $saw -> showLastMadeOrders();
			case 3: 
				$eBMachine = new EdgeBandingMachine($this->dbo);
				return $eBMachine -> showLastMadeOrders();
			case 4: 
				$orders = new Orders ($this->dbo);
				return $orders -> showLastMadeOrders();
			Default: 
			return NO_PERMISSION;
		}
	}	
	
	function showOrderList(){
		if(!$this->dbo){ return SERVER_ERROR; }
		
		switch ($this->stand->id){
			case 1: 
			case 2:
				$saw = new Saw ($this->dbo, $this->stand->id);
				return $saw -> showOrderList();
			case 3: 
				$eBMachine = new EdgeBandingMachine($this->dbo);
				return $eBMachine -> showOrderList();
			case 4: 
				$orders = new Orders ($this->dbo);
				return $orders->showOrderList();
			Default: 
			return NO_PERMISSION;
		}
	}

	
	function showOrderSearchingForm(){
		if(!$this->dbo){ return SERVER_ERROR; }
		
		switch ($this->stand->id){
			case 1:
			case 2:
				$saw = new Saw($this->dbo, $this->stand->id);
				return $saw->showOrderSearchingForm(); 
			case 3:
				$edgeBandingMachine = new  EdgeBandingMachine($this->dbo);
				$edgeBandingMachine -> showOrderSearchingForm();
				break;
			case 4:
				$orders = new Orders($this->dbo);
				return $orders -> showOrderSearchingForm();
			default: 
				return NO_PERMISSION;
		}
	}
	
	function showSearchResult(){
		if(!$this->dbo){ return SERVER_ERROR; }
		
		switch ($this->stand->id){
			case 1:
			case 2:
				$saw = new Saw($this->dbo, $this->stand->id);
				return $saw->showSearchResult(); 
			case 3:
				$edgeBandingMachine = new  EdgeBandingMachine($this->dbo);
				$edgeBandingMachine -> showSearchResult();
				break;
			case 4:
				$orders = new Orders($this->dbo);
				return $orders->showSearchResult();
			default: 
				return NO_PERMISSION;
		}
	}
	
	function showOrderDetails(){
		if(!$this -> dbo) { return SERVER_ERROR; }
		if ($this -> stand -> id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this -> dbo);
		return $orders -> showOrderDetails();
	}
	
	function showOrderUpdatingForm(){
		if(!$this -> dbo) { return SERVER_ERROR; }
		if ($this -> stand -> id != 4){ return NO_PERMISSION; }
		$orders = new Orders ($this -> dbo);
		return $orders -> showOrderUpdatingForm();
	}
	
	function returnAmountsOfPeriod(){
		if(!$this->dbo){ return SERVER_ERROR; }
		if ($this->stand->id != 4){  return NO_PERMISSION; }
		$orders = new Orders($this->dbo);
		return $orders->returnAmountsOfPeriod();
	}
  
}
?>