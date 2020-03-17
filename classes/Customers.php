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
		if($result = $this->dbo->query("SELECT `id`, `name`, `surname`, `phone`, `address` FROM customers LEFT JOIN `customers_addresses` ON `customers_addresses`.`customer_id`=`customers`.`id` WHERE `customers`.`id` <> 1 ORDER BY `surname`")){
			$customersList = $result->fetchAll(PDO::FETCH_OBJ);
		}
		return $customersList;
	}
	
	function showCustomerList(){
		$customerList = $this -> returnCustomerListWithAddresses();
		
		include 'scripts/customerListScripts.php';
		include 'templates/customerList.php';
	}
	
	function findCustomers($conditions){
		$query = "SELECT `id`, `name`, `surname`, `phone`, `address` FROM customers LEFT JOIN `customers_addresses` ON `customers_addresses`.`customer_id`=`customers`.`id` WHERE 1=1";
		$query .= $conditions;
		$query .= " ORDER BY `surname` LIMIT 50";
		if(!$query = $this -> dbo -> query ($query)){
			return null;
		}
		
		if(!$result = $query -> fetchAll(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}	
	
	function showSearchResult(){
		if (!isset($_POST['name']) || !isset($_POST['surname']) || !isset($_POST['phone'])){
			return FORM_DATA_MISSING;
		}
		$condition1 = "";
		$condition2 = "";
		$condition3 = "";
		if($_POST['name'] != ""){
			$condition1 = " AND `customers`.`name` LIKE '%" . $_POST['name'] . "%'";
			}
		if($_POST['surname'] != ""){
			$condition2 = " AND `customers`.`surname` LIKE '%" . $_POST['surname'] . "%'";
		}
		if($_POST['phone'] != ""){
			$condition3 = " AND `customers`.`phone`='" . $_POST['phone'] . "'"; 
		}
		$conditions = $condition1 . $condition2 . $condition3;
		if($conditions == ""){
			include 'templates/noData.php';
		}
		else{
			if($customers = $this -> findCustomers($conditions)){
				include 'scripts/customerListScripts.php';
				include 'templates/customerSearchResult.php';
			}
			else{
				include 'templates/noResults.php';
			}
		}
	}
	
	function showSearchingForm(){
		$name = filter_input (INPUT_POST, 'name');
		$surname = filter_input (INPUT_POST, 'surname');
		$phone = filter_input (INPUT_POST, 'phone');
		$address = filter_input (INPUT_POST, 'address');	
		
		include 'templates/customerSearchingForm.php';
	}
	
	function showCustomerUpdatingForm(){
		if (!isset($_POST['id']) || $_POST['id'] ==''){
			return FORM_DATA_MISSING;
		}

		$id = filter_input (INPUT_POST, 'id');
		$name = filter_input (INPUT_POST, 'firstName');
		$surname = filter_input (INPUT_POST, 'surname');	
		$phone = filter_input (INPUT_POST, 'phone');	
		$address = filter_input (INPUT_POST, 'address');	
		
		include 'scripts/customerUpdatingFormScripts.php';
		include 'templates/customerUpdatingForm.php';
	}
	
	function setCustomerName($customerId, $name){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		if ($name == "" || ctype_space($name)){
			$name = NULL;
		}else{
			$name = ucwords($name); 
		}
		$query = $this -> dbo -> prepare ("UPDATE `customers` SET `name`=:name WHERE `id`=:customerId");
		$query -> bindValue (':name', $name, PDO::PARAM_STR);
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	
	function updateCustomerName(){
		if(!isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1 || !isset($_POST['customerName'])){
			return FORM_DATA_MISSING;
		}
		return $this -> setCustomerName($_POST['customerId'], $_POST['customerName']);
	}
	
	function setCustomerSurname($customerId, $surname){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$surname = ucwords($surname);
		
		$query = $this -> dbo -> prepare ("UPDATE `customers` SET `surname`=:surname WHERE `id`=:customerId");
		$query -> bindValue (':surname', $surname, PDO::PARAM_STR);
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	
	function updateCustomerSurname(){
		if(!isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1 || !isset($_POST['customerSurname']) || $_POST['customerSurname'] == ''  ){
			return FORM_DATA_MISSING;
		}
		return $this -> setCustomerSurname($_POST['customerId'], $_POST['customerSurname']);
	}
	
	function setCustomerPhone($customerId, $phone){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this -> dbo -> prepare ("UPDATE `customers` SET `phone`=:phone WHERE `id`=:customerId");
		$query -> bindValue (':phone', $phone, PDO::PARAM_STR);
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	
	function updateCustomerPhone(){
		if(!isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1 || !isset($_POST['phone']) || $_POST['phone'] == ''  ){
			return FORM_DATA_MISSING;
		}
		return $this -> setCustomerPhone($_POST['customerId'], $_POST['phone']);
	}
	
	function setCustomerAddress($customerId, $address){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		
		$query = $this -> dbo -> prepare ("REPLACE INTO `customers_addresses` values (:customerId, :address)");
		$query -> bindValue (':address', $address, PDO::PARAM_STR);
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteCustomerAddress($customerId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `customers_addresses` WHERE `customer_id`=:customerId");
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateCustomerAddress(){
		if(!isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1 || !isset($_POST['address'])){
			return FORM_DATA_MISSING;
		}
		if($_POST['address'] == ""){
			return $this -> deleteCustomerAddress($_POST['customerId']);
		}
		return $this -> setCustomerAddress($_POST['customerId'], $_POST['address']);
	}
	
	
}
?>