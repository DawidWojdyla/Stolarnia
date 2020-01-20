<?php
class Customers
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}
	
	function showCustomerAddingForm(){
		include 'scripts/customerAddingFormScripts.php';
		include 'templates/customerAddingForm.php';
	}
	
	function checkIfCustomerPhoneExistsInTheDatabase($phone){
		$query = $this->dbo->prepare("SELECT `id` FROM `customers` WHERE `phone`=:phone");
		$query -> bindValue (':phone', $phone, PDO::PARAM_STR);
		$query -> execute();
		if ($query -> rowCount()){
			return true;
		}
		return false;
	}
	
	function addNewCustomerToTheDatabase(){
		if(!$this->dbo){ return SERVER_ERROR;}
		
		if(!$this->dbo->beginTransaction()){
			return SERVER_ERROR;
		}
		if($this->checkIfCustomerPhoneExistsInTheDatabase($_POST['customerPhone'])){
			return CUSTOMER_NUMBER_ALREADY_EXISTS;
		}
		
		$query  = $this->dbo -> prepare ("INSERT INTO `customers` VALUES (NULL, :name, :surname, :phone)");
		$query -> bindValue (':name', $_SESSION['customerName'], PDO::PARAM_STR);
		$query -> bindValue (':surname', $_SESSION['customerSurname'], PDO::PARAM_STR);
		$query -> bindValue (':phone', $_SESSION['customerPhone'], PDO::PARAM_STR);
		
		if (!$query -> execute()){
			return SERVER_ERROR;
		}
		if($_SESSION['customerAddress']){
			if(!$this->dbo->query("SET @customerId=LAST_INSERT_ID()")){
				return SERVER_ERROR;
			}
			$query  = $this->dbo -> prepare ("INSERT INTO `customers_addresses` VALUES (@customerId, :address)");
			$query -> bindValue (':address', $_SESSION['customerAddress'], PDO::PARAM_STR);
			if (!$query -> execute()){
				return SERVER_ERROR;
			}
		}
		if(!$this->dbo->commit()){
			return SERVER_ERROR;
		}	
		unset($_SESSION['customerName']);
		unset($_SESSION['customerSurname']);
		unset($_SESSION['customerPhone']);
		unset($_SESSION['customerAddress']);
		
		return ACTION_OK;
	}
	
	function addNewCustomer(){
		
		if(!$this->dbo) return SERVER_ERROR;
		
		if($customerSurname = filter_input(INPUT_POST, 'customerSurname')){
			$_SESSION['customerSurname'] = $customerSurname;
		}
		
		if($customerName = filter_input(INPUT_POST, 'customerName')){
			$_SESSION['customerName'] = $customerName;
		}
		if($customerPhone = filter_input(INPUT_POST, 'customerPhone')){
			$_SESSION['customerPhone'] = $customerPhone;
		}
		if($customerAddress = filter_input(INPUT_POST, 'customerAddress')){
			$_SESSION['customerAddress'] = $customerAddress;
		}
		
		
		if (!isset($_POST["customerSurname"]) || $_POST["customerSurname"] == ""){
			return FORM_DATA_MISSING;
		}

		if(!preg_match("/^[1-9]{1}[0-9]{8}$/", $customerPhone)){
			$_SESSION['customerPhoneError'] = 'Format numeru telefonu jest nieprawid³owy.';
			return FORM_DATA_MISSING;
		}
		
		return $this->addNewCustomerToTheDatabase();
	}
	
	function returnCustomerList(){
		$customersList = array();
		if($result = $this->dbo->query("SELECT `id`, `name`, `surname`, `phone` FROM customers WHERE `id` <> 1 ORDER BY `surname`")){
			$customersList = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $customersList;
	}
	function returnCustomerListWithAddresses(){
		$customersList = array();
		if($result = $this->dbo->query("SELECT `id`, `name`, `surname`, `phone`, `address` FROM customers LEFT JOIN `customers_addresses` ON `customers_addresses`.`customer_id`=`customers`.`id`WHERE `customers`.`id` <> 1 ORDER BY `surname`")){
			$customersList = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $customersList;
	}
	
	function showCustomerList(){
		$customerList = $this->returnCustomerListWithAddresses();
		
		//include 'scripts/customerListScripts.php';
		include 'templates/customerList.php';
	}
	
}
?>