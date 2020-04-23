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
		
		if(!$this -> dbo -> query("SET @orderId=LAST_INSERT_ID()")){
			return SERVER_ERROR;
		}
		
		if($_POST['customerId'] == '1'){
			$customerName = filter_input(INPUT_POST, 'customerName');
			$customerName = ucwords($customerName);
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
			$query  = $this -> dbo -> prepare ("INSERT INTO `orders_boards` VALUES (NULL, @orderId, :boardSignId, :boardSymbolId, :boardThicknessId, :amount, :cuttingMetters, NULL)");		
			$query -> bindValue (':boardSignId', $position['boardSignId'], PDO::PARAM_INT);
			if(!isset($position['boardSymbolId']) || $position['boardSymbolId'] == '0'){ 
				$query -> bindValue (':boardSymbolId', 1, PDO::PARAM_INT);
				
			}else{
				$query -> bindValue (':boardSymbolId', $position['boardSymbolId'], PDO::PARAM_INT);
			}
			
			$query -> bindValue (':boardThicknessId', $position['boardThicknessId'], PDO::PARAM_INT);
			$query -> bindValue (':amount', $position['amount'], PDO::PARAM_STR);
			$query -> bindValue (':cuttingMetters', $position['cuttingMetters'], PDO::PARAM_STR);
				
			if(!$query->execute()){
				return SERVER_ERROR;
			}
			
			if(!$this -> dbo -> query("SET @ordersBoardsId=LAST_INSERT_ID()")){
					return SERVER_ERROR;
			}
			
			if($position['boardSymbolId'] == '0' && isset($position['otherBoardSymbol']) && $position['otherBoardSymbol'] != ""){
				$query  = $this -> dbo -> prepare ("INSERT INTO `other_boards_symbols` VALUES (@ordersBoardsId, :symbol)");		
				$query -> bindValue (':symbol', $position['otherBoardSymbol'], PDO::PARAM_STR);
				if(!$query -> execute()){
					return SERVER_ERROR;
				}
			}
			
			if ($edgeBandsAmount = count($position['edgeBandTypesId'])){
				
				for($i = 0; $i < $edgeBandsAmount; $i++){
					$query  = $this -> dbo -> prepare ("INSERT INTO `edge_banding` VALUES (NULL, @ordersBoardsId, :edgeBandStickerSymbolId, :edgeBandTypeId, :edgeBandingBoardSymbolId, :edgeBandingMettersWz, 0, NULL)");
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
		$isAllOk = $checker -> checkCustomerData();
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
					case 'boardSymbolId':
						if($value = '-1'){
							$value = '1';
						}else if($value == '' || (int)$value < 0){
							$isAllOk = false;
						}
						break;
					case 'boardSignId':
					case 'boardThicknessId':
						if($value == '' || (int)$value < 1){
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
		
		return $this -> addNewOrderToTheDatabase();
	}
	
	function deleteEdgeBandCommentsHavingOrderId($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_comments` WHERE `edge_banding_id` IN (SELECT `id` FROM `edge_banding` WHERE `orders_boards_id` IN (SELECT `id` FROM `orders_boards` WHERE `order_id`=:orderId))");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function deleteEdgeBandingsHavingOrderId($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_banding` WHERE `orders_boards_id` IN (SELECT `id` FROM `orders_boards` WHERE `order_id`=:orderId)");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function deleteBoardsHavingOrderId($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `orders_boards` WHERE `order_id`=:orderId");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function deleteOrder($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `orders` WHERE `id`=:orderId");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeOrder(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> deleteEdgeBandCommentsHavingOrderId($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> deleteEdgeBandingsHavingOrderId($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> deleteBoardsHavingOrderId($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeCustomerTempData($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> deleteOrderComment($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> deleteOrder($_POST['orderId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}

	function addNewCustomerTempData($orderId, $customerName, $customerPhone){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$customerName = ucwords($customerName);
		$query  = $this -> dbo -> prepare ("INSERT INTO `customers_temp` VALUES (:orderId, :customerName, :customerPhone)");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		$query -> bindValue (':customerName',$customerName, PDO::PARAM_STR);
		$query -> bindValue (':customerPhone', $customerPhone, PDO::PARAM_STR);
		if (!$query -> execute()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function setNewCustomerTempData($orderId, $customerName, $customerPhone){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$customerName = ucwords($customerName);
		$query  = $this -> dbo -> prepare ("UPDATE `customers_temp` SET `name`=:customerName, `phone`=:customerPhone WHERE `order_id`=:orderId");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		$query -> bindValue (':customerName',$customerName, PDO::PARAM_STR);
		$query -> bindValue (':customerPhone', $customerPhone, PDO::PARAM_STR);
		if (!$query -> execute()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function removeCustomerTempData($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `customers_temp` WHERE `order_id`=:orderId");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}	
	
	function setDocumentNumber($orderId, $documentNumber){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `document_number`=:documentNumber WHERE `id`=:orderId");
		$query -> bindValue (':documentNumber', $documentNumber, PDO::PARAM_STR);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateDocumentNumber(){
		
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['documentNumber']) || $_POST['documentNumber'] == ''){
			return FORM_DATA_MISSING;
		}
		$year = filter_input(INPUT_POST, 'year');
		if($_POST['documentNumber'] == "Brak"){
			$documentNumber = NULL;
		}
		else{
			if(!is_numeric(substr($_POST['documentNumber'],2,6))){
				return ACTION_FAILED;
			}				
			$documentNumber = $_POST['documentNumber'];
		}
		
		if($this -> checkIfDocumentNumberExistsInTheDatabase($documentNumber, $year)){
			return DOCUMENT_NUMBER_ALREADY_EXISTS;
		}
			
		return $this -> setDocumentNumber($_POST['orderId'], $documentNumber);
	}
	
	function setCustomerId($orderId, $customerId){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `customer_id`=:customerId WHERE `id`=:orderId");
		$query -> bindValue (':customerId', $customerId, PDO::PARAM_INT);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateCustomerId(){
		
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1){
			return FORM_DATA_MISSING;
		}
	
		return $this -> setCustomerId($_POST['orderId'], $_POST['customerId']);
	}
	
	function updateCustomerIdAndRemoveTempData(){
		
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['customerId']) || $_POST['customerId'] == '' || ((int)($_POST['customerId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
	
		if($this -> setCustomerId($_POST['orderId'], $_POST['customerId']) != ACTION_OK){
			return SERVER_ERROR;
		}
		if($this -> removeCustomerTempData($_POST['orderId']) != ACTION_OK){
			return SERVER_ERROR;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;	
	}
	
	function updateCustomerTempData(){
		
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['customerName']) || $_POST['customerName'] == '' || !isset($_POST['customerPhone']) || $_POST['customerPhone'] == ''){
			return FORM_DATA_MISSING;
		}
		
		if($this -> setNewCustomerTempData($_POST['orderId'], $_POST['customerName'], $_POST['customerPhone']) != ACTION_OK){
			return SERVER_ERROR;
		}
		return ACTION_OK;	
	}
	
	function updateCustomerIdAndAddTempData(){
		
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['customerName']) || $_POST['customerName'] == '' || !isset($_POST['customerPhone']) || $_POST['customerPhone'] == ''){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
	
		if($this -> setCustomerId($_POST['orderId'], 1) != ACTION_OK){
			return SERVER_ERROR;
		}
		if($this -> addNewCustomerTempData($_POST['orderId'], $_POST['customerName'], $_POST['customerPhone']) != ACTION_OK){
			return SERVER_ERROR;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;	
	}
	
	function setSawNumber($orderId, $sawNumber){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `saw_number`=:sawNumber WHERE `id`=:orderId");
		$query -> bindValue (':sawNumber', $sawNumber, PDO::PARAM_INT);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateSawNumber(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['sawNumber']) || ($_POST['sawNumber'] != '1' && $_POST['sawNumber'] != '2')){
			return FORM_DATA_MISSING;
		}
			
		return $this -> setSawNumber($_POST['orderId'], $_POST['sawNumber']);
	}
	
	function setAdmissionDate($orderId, $admissionDate){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `admission_date`=:admissionDate WHERE `id`=:orderId");
		$query -> bindValue (':admissionDate', $admissionDate, PDO::PARAM_STR);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateAdmissionDate(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['admissionDate']) || $_POST['admissionDate'] == ''  ){
			return FORM_DATA_MISSING;
		}
			
		return $this -> setAdmissionDate($_POST['orderId'], $_POST['admissionDate']);
	}
	
	function setCompletionDate($orderId, $completionDate){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `order_completion_date`=:completionDate WHERE `id`=:orderId");
		$query -> bindValue (':completionDate', $completionDate, PDO::PARAM_STR);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateCompletionDate(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['completionDate']) || $_POST['completionDate'] == ''  ){
			return FORM_DATA_MISSING;
		}
			
		return $this -> setCompletionDate($_POST['orderId'], $_POST['completionDate']);
	}
	
	function setSellerId($orderId, $sellerId){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders` SET `worker_id`=:sellerId WHERE `id`=:orderId");
		$query -> bindValue (':sellerId', $sellerId, PDO::PARAM_INT);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateSeller(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['sellerId']) || $_POST['sellerId'] == '' || ((int)($_POST['sellerId'])) < 1  ){
			return FORM_DATA_MISSING;
		}	
		return $this -> setSellerId($_POST['orderId'], $_POST['sellerId']);
	}
	
	function setNewComment($orderId, $comment){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders_comments` SET `comments`=:comment WHERE `order_id`=:orderId");
		$query -> bindValue (':comment', $comment, PDO::PARAM_STR);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setNewOrderComment($orderId, $comment){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `orders_comments` VALUES (:orderId, :comment)");
		$query -> bindValue (':comment', $comment, PDO::PARAM_STR);
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteOrderComment($orderId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `orders_comments` WHERE `order_id`=:orderId");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateOrderComment(){
		if(!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['comment'])){
			return FORM_DATA_MISSING;
		}
		if($_POST['comment'] == ''){
			return $this->deleteOrderComment($_POST['orderId']);
		}
		
		return $this -> setNewOrderComment($_POST['orderId'], $_POST['comment']);
	}
	

	function setNewBoard($orderId, $boardSignId, $boardThicknessId, $boardSymbolId, $boardAmount, $cuttingMetters){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `orders_boards` VALUES (NULL, :orderId, :boardSignId, :boardSymbolId, :boardThicknessId, :boardAmount, :cuttingMetters, NULL)");
		$query -> bindValue (':orderId', $orderId, PDO::PARAM_INT);
		$query -> bindValue (':boardSignId', $boardSignId, PDO::PARAM_INT);
		$query -> bindValue (':boardSymbolId', $boardSymbolId, PDO::PARAM_INT);
		$query -> bindValue (':boardThicknessId', $boardThicknessId, PDO::PARAM_INT);
		$query -> bindValue (':boardAmount', $boardAmount, PDO::PARAM_STR);
		$query -> bindValue (':cuttingMetters', $cuttingMetters, PDO::PARAM_STR);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function addNewBoard(){
		if (!isset($_POST['orderId']) || $_POST['orderId'] == '' || ((int)($_POST['orderId'])) < 1 || !isset($_POST['boardSignId']) || $_POST['boardSignId'] == '' || !isset($_POST['boardThicknessId']) || $_POST['boardThicknessId'] == '' || !isset($_POST['boardSymbolId']) || $_POST['boardSymbolId'] == '' || !isset($_POST['boardAmount']) || $_POST['boardAmount'] == ''  || !isset($_POST['cuttingMetters']) || $_POST['cuttingMetters'] == '' || !isset($_POST['otherBoardSymbol'])){
			return "ACTION_FAILED";
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return 'ACTION_FAILED';
		}
			
		if ($this -> setNewBoard($_POST['orderId'], $_POST['boardSignId'], $_POST['boardThicknessId'], $_POST['boardSymbolId'], $_POST['boardAmount'], $_POST['cuttingMetters']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		
		if(!$boardId = $this -> dbo -> lastInsertId()){
			return "ACTION_FAILED";
		}
		
		if($_POST['otherBoardSymbol'] != ""){
			if($this -> addOtherBoardSymbol($boardId, $_POST['otherBoardSymbol'] ) != ACTION_OK ){
				return "ACTION_FAILED";
			}
		}
		
		if(!$this -> dbo -> commit()){
			return 'ACTION_FAILED';
		}
		return $boardId;
	}
	
	function deleteEdgeBandCommentsHavingBoardId($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_comments` WHERE `edge_banding_id` IN (SELECT `id` FROM `edge_banding` WHERE `orders_boards_id`=:boardId)");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function deleteEdgeBandingsHavingBoardId($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_banding` WHERE `orders_boards_id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function deleteBoard($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `orders_boards` WHERE `id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeBoard(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> deleteEdgeBandCommentsHavingBoardId($_POST['boardId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> deleteEdgeBandingsHavingBoardId($_POST['boardId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if($this -> deleteOtherBoardSymbol($_POST['boardId']) != ACTION_OK ){
				return ACTION_FAILED;
		}
		
		if ($this -> deleteBoard($_POST['boardId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
		
	function addOtherBoardSymbol($boardId, $otherBoardSymbol){
		if(!$this->dbo){
			return SERVER_ERROR;
		}
		$query  = $this -> dbo -> prepare ("REPLACE INTO `other_boards_symbols` VALUES (:boardId, :symbol)");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		$query -> bindValue (':symbol', $otherBoardSymbol, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setTheBoard($boardId, $boardSignId, $boardThicknessId, $boardSymbolId){
		
		if(!$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `board_sign_id`=:boardSignId, `board_thickness_id`=:boardThicknessId, `board_symbol_id`=:boardSymbolId WHERE `id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		$query -> bindValue (':boardSignId', $boardSignId, PDO::PARAM_INT);
		$query -> bindValue (':boardThicknessId', $boardThicknessId, PDO::PARAM_INT);
		$query -> bindValue (':boardSymbolId', $boardSymbolId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function deleteOtherBoardSymbol($boardId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `other_boards_symbols` WHERE `orders_boards_id`=:boardId");
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateBoard(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['boardSignId']) || $_POST['boardSignId'] == '' || !isset($_POST['boardThicknessId']) || $_POST['boardThicknessId'] == '' || !isset($_POST['boardSymbolId']) || $_POST['boardSymbolId'] == '' ||  !isset($_POST['otherBoardSymbol'])){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
				return SERVER_ERROR;
		}
		
		if($this -> setTheBoard($_POST['boardId'], $_POST['boardSignId'], $_POST['boardThicknessId'], $_POST['boardSymbolId']) != ACTION_OK ){
				return ACTION_FAILED;
		}
			
		if($_POST['otherBoardSymbol'] != ""){
			if($this -> addOtherBoardSymbol($_POST['boardId'], $_POST['otherBoardSymbol'] ) != ACTION_OK ){
				return ACTION_FAILED;
			}
		}else{
			if($this -> deleteOtherBoardSymbol($_POST['boardId']) != ACTION_OK ){
				return ACTION_FAILED;
			}
		}
			
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function setBoardAmount($boardId, $amount){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `amount`=:amount WHERE `id`=:boardId");
		$query -> bindValue (':amount', $amount, PDO::PARAM_STR);
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateBoardAmount(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['amount']) || $_POST['amount'] == ''  ){
			return FORM_DATA_MISSING;
		}
			
		return $this -> setBoardAmount($_POST['boardId'], $_POST['amount']);
	}
	
	function setCuttingMetters($boardId, $cuttingMetters){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET `cutting_metters`=:cuttingMetters WHERE `id`=:boardId");
		$query -> bindValue (':cuttingMetters', $cuttingMetters, PDO::PARAM_STR);
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateCuttingMetters(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['cuttingMetters']) || $_POST['cuttingMetters'] == ''  ){
			return FORM_DATA_MISSING;
		}
			
		return $this -> setCuttingMetters($_POST['boardId'], $_POST['cuttingMetters']);
	}
	
	function deleteEdgeBandComment($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_comments` WHERE `edge_banding_id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function setEdgeBandComment($edgeBandingId, $comment){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `edge_band_comments` VALUES (:edgeBandingId, :comment)");
		$query -> bindValue (':comment', $comment, PDO::PARAM_STR);
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function setEdgeBanding($edgeBandingId, $edgeBandTypeId, $edgeBandingBoardSymbolId, $edgeBandStickerSymbolId, $edgeBandingMettersWz){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `edge_banding` SET  `edge_band_sticker_symbol_id`=:edgeBandStickerSymbolId, `edge_band_type_id`=:edgeBandTypeId, `board_symbol_id`=:edgeBandingBoardSymbolId, `edge_banding_metters_wz`=:edgeBandingMettersWz WHERE `id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingMettersWz', $edgeBandingMettersWz, PDO::PARAM_STR);
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandStickerSymbolId', $edgeBandStickerSymbolId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandTypeId', $edgeBandTypeId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandingBoardSymbolId', $edgeBandingBoardSymbolId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function updateEdgeBanding(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1 || !isset($_POST['edgeBandTypeId']) || $_POST['edgeBandTypeId'] == '' || !isset($_POST['edgeBandingBoardSymbolId']) || $_POST['edgeBandingBoardSymbolId'] == '' || !isset($_POST['edgeBandStickerSymbolId']) || $_POST['edgeBandStickerSymbolId'] == ''  || !isset($_POST['edgeBandingMettersWz']) || $_POST['edgeBandingMettersWz'] == ''  || !isset($_POST['edgeBandComment'])){
			return FORM_DATA_MISSING;
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if($_POST['edgeBandComment'] == ''){
			if ($this -> deleteEdgeBandComment($_POST['edgeBandingId']) != ACTION_OK ){
				return ACTION_FAILED;
			}
		}else{
			if ($this -> setEdgeBandComment($_POST['edgeBandingId'], $_POST['edgeBandComment']) != ACTION_OK){
				return ACTION_FAILED;
			}
		}
			
		if ($this -> setEdgeBanding($_POST['edgeBandingId'], $_POST['edgeBandTypeId'], $_POST['edgeBandingBoardSymbolId'], $_POST['edgeBandStickerSymbolId'], $_POST['edgeBandingMettersWz']) != ACTION_OK){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function setNewEdgeBanding($boardId, $edgeBandTypeId, $edgeBandingBoardSymbolId, $edgeBandStickerSymbolId, $edgeBandingMettersWz){
		
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_banding` VALUES (NULL, :boardId, :edgeBandStickerSymbolId, :edgeBandTypeId, :edgeBandingBoardSymbolId, :edgeBandingMettersWz, 0, NULL)");
		$query -> bindValue (':edgeBandingMettersWz', $edgeBandingMettersWz, PDO::PARAM_STR);
		$query -> bindValue (':boardId', $boardId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandStickerSymbolId', $edgeBandStickerSymbolId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandTypeId', $edgeBandTypeId, PDO::PARAM_INT);
		$query -> bindValue (':edgeBandingBoardSymbolId', $edgeBandingBoardSymbolId, PDO::PARAM_INT);
		
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function addNewEdgeBanding(){
		if(!isset($_POST['boardId']) || $_POST['boardId'] == '' || ((int)($_POST['boardId'])) < 1 || !isset($_POST['edgeBandTypeId']) || $_POST['edgeBandTypeId'] == '' || !isset($_POST['edgeBandingBoardSymbolId']) || $_POST['edgeBandingBoardSymbolId'] == '' || !isset($_POST['edgeBandStickerSymbolId']) || $_POST['edgeBandStickerSymbolId'] == ''  || !isset($_POST['edgeBandingMettersWz']) || $_POST['edgeBandingMettersWz'] == ''  || !isset($_POST['edgeBandComment'])){
			return "ACTION_FAILED";
		}
		
		if(!$this -> dbo -> beginTransaction()){
			return "ACTION_FAILED";
		}
			
		if ($this -> setNewEdgeBanding($_POST['boardId'], $_POST['edgeBandTypeId'], $_POST['edgeBandingBoardSymbolId'], $_POST['edgeBandStickerSymbolId'], $_POST['edgeBandingMettersWz']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if(!$edgeBandingId = $this -> dbo -> lastInsertId()){
			return "ACTION_FAILED";
		}
		
		if($_POST['edgeBandComment'] != ''){
			if ($this -> setEdgeBandComment($edgeBandingId, $_POST['edgeBandComment']) != ACTION_OK){
				return 'ACTION_FAILED';
			}
		}
		
		if(!$this -> dbo -> commit()){
			return 'ACTION_FAILED';
		}
		return $edgeBandingId;
	}
	
	function removeEdgeBandingPosition($edgeBandingId){
		if( !$this->dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_banding` WHERE `id`=:edgeBandingId");
		$query -> bindValue (':edgeBandingId', $edgeBandingId, PDO::PARAM_INT);	
		if (!$query -> execute()){ 
			return ACTION_FAILED;
		}
		return ACTION_OK;	
	}
	
	function removeEdgeBanding(){
		if(!isset($_POST['edgeBandingId']) || $_POST['edgeBandingId'] == '' || ((int)($_POST['edgeBandingId'])) < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> deleteEdgeBandComment($_POST['edgeBandingId']) != ACTION_OK ){
				return ACTION_FAILED;
		}
		
		if ($this -> removeEdgeBandingPosition($_POST['edgeBandingId']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function returnBoardsSigns(){
		$boardsSigns = array();
		if($result = $this -> dbo -> query("SELECT `id`, `sign` FROM boards_signs ORDER BY CASE WHEN `id`<10 then `id` ELSE `sign` end")){
			$boardsSigns = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSigns;
	}
	
	function returnBoardsSymbols(){
		$boardsSymbols = array();
		if($result = $this -> dbo -> query ("SELECT `id`, `symbol` FROM boards_symbols ORDER BY `symbol` ASC")){
			$boardsSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSymbols;
	}
	
	function returnEdgeBandSymbols(){
		$edgeBandSymbols = array();
		if($result = $this -> dbo -> query("SELECT `id`, `symbol` FROM `boards_symbols` WHERE `id` NOT IN (SELECT `board_symbol_id` FROM `no_edge_band_boards_symbols`)")){
			$edgeBandSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandSymbols;
	}
	
	function returnBoardsThickness(){
		$boardsThickness = array();
		if($result = $this -> dbo -> query("SELECT `id`, `thickness` FROM boards_thickness")){
			$boardsThickness = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsThickness;
	}
	
	function returnEdgeBandStickerSymbols(){
		$edgeBandStickerSymbols = array();
		if($result = $this -> dbo -> query("SELECT `id`, `symbol` FROM edge_band_sticker_symbols")){
			$edgeBandStickerSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandStickerSymbols;
	}
	
	function returnEdgeBandTypes(){
		$edgeBandTypes = array();
		if($result = $this -> dbo -> query("SELECT `id`, `type` FROM edge_band_types ORDER BY `id`<10 DESC, `type`")){
			$edgeBandTypes = $result -> fetchAll(PDO::FETCH_OBJ);
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
		if($result = $this -> dbo -> query("SELECT `order_completion_date` FROM orders WHERE id=(SELECT `id` FROM `orders` WHERE `saw_number`={$sawNumber} ORDER BY `id` DESC LIMIT 1)")){
			$lastOrderCompletionDate = $result -> fetch(PDO::FETCH_NUM);
		}
		return $lastOrderCompletionDate[0];
	}
	
	function returnOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this -> dbo -> query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `orders`.`worker_id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, DATE_FORMAT(`orders`.`order_completion_date`, '%d.%m') as completionDate, `orders_comments`.`comments` as orderComment, CASE WHEN `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL) THEN 'niepocięte' WHEN `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `cutting_completion_date` IS NULL OR `edge_banding`.`edge_banding_metters_machine`='0') THEN 'gotowe' ELSE 'pocięte' END as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `workers`.`id`=`orders`.`worker_id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') ORDER BY `order_completion_date`")){
			$orders = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnNotCutOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this -> dbo -> query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `orders`.`worker_id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'niepocięte' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `workers`.`id`=`orders`.`worker_id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` IN (SELECT `order_id` FROM `orders_boards` WHERE `cutting_completion_date` IS NULL) ORDER BY `order_completion_date`")){
			$orders = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnCutButNotCompletedOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this -> dbo -> query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`worker_id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'pocięte' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `workers`.`id`=`orders`.`worker_id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` WHERE `orders_boards`.`cutting_completion_date` IS NULL) AND `orders`.`id` IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `edge_banding`.`edge_banding_metters_machine`='0') ORDER BY `order_completion_date`")){
			$orders = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
	
	function returnCompletedOrdersOfPeriod($dateFrom, $dateTo){
		$orders = array();
		if($result = $this -> dbo -> query("SELECT `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`worker_id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName, `orders`.`customer_id`, `customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment, 'gotowe' as state FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `workers`.`id`=`orders`.`worker_id` AND (`orders`.`order_completion_date` BETWEEN '{$dateFrom}' AND '{$dateTo}') AND `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `cutting_completion_date` IS NULL OR `edge_banding`.`edge_banding_metters_machine`='0') ORDER BY `order_completion_date`")){
			$orders = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}
		
	function returnLastMadeOrders($ordersAmount){
		$orders = array();
		
		if($result = $this -> dbo -> query("SELECT GREATEST(`orders_boards`.`cutting_completion_date`, COALESCE(`edge_banding`.`edge_banding_completion_date`, 0)) as lastDate, `orders`.`id` as orderId, `orders`.`document_number`, `orders`.`customer_id`, `orders`.`worker_id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as customerPhone, `customers_temp`.`name` as customerTempName, `customers_temp`.`phone` as customerTempPhone, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `orders_comments`.`comments` as orderComment FROM `orders` LEFT JOIN `orders_boards` ON `orders`.`id`=`orders_boards`.`order_id` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `customers`, `workers` WHERE `orders`.`customer_id`=`customers`.`id` AND `workers`.`id`=`orders`.`worker_id` AND `orders`.`id` NOT IN (SELECT `orders_boards`.`order_id` FROM `orders_boards` LEFT JOIN `edge_banding` ON `edge_banding`.`orders_boards_id`=`orders_boards`.`id` WHERE `cutting_completion_date` IS NULL OR `edge_banding`.`edge_banding_metters_machine`='0') GROUP BY `orders`.`id` ORDER BY lastDate DESC LIMIT {$ordersAmount}")){
			$orders = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orders;
	}	
	
	function returnOrderDetailsForUpdatingForm($orderId){
		$orderDetails = array();
		if($result = $this -> dbo -> query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, `boards_signs`.`id` as boardSignId, boardSymbols.`symbol` as boardSymbol, boardSymbols.`id` as boardSymbolId, `boards_thickness`.`thickness`, `boards_thickness`.`id` as boardThicknessId, `other_boards_symbols`.`symbol` as otherSymbol, ob.`amount`, ob.`cutting_metters`, ob.`cutting_completion_date`, ebQuery.edgeBandingId as edgeBandingId, ebQuery.stickerSymbol, ebQuery.stickerSymbolId, ebQuery.edgeBandType, ebQuery.edgeBandTypeId, ebQuery.edgeBandSymbol, ebQuery.edgeBandSymbolId, ebQuery.wzMetters, ebQuery.edgeBandComment FROM `orders_boards` ob LEFT JOIN `other_boards_symbols` ON `other_boards_symbols`.`orders_boards_id`=ob.`id` LEFT JOIN (SELECT eb.`orders_boards_id` as ebObId, eb.`id` as edgeBandingId, `edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_sticker_symbols`.`id` as stickerSymbolId, `edge_band_types`.`type` as edgeBandType, `edge_band_types`.`id` as edgeBandTypeId, edgeBandSymbols.`symbol` as edgeBandSymbol, edgeBandSymbols.`id` as edgeBandSymbolId, `eb`.`edge_banding_metters_wz` as wzMetters, `edge_band_comments`.`comments` as edgeBandComment FROM `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types` WHERE `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id`) as ebQuery ON ebQuery.ebObId=ob.`id` LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=`ob`.`board_symbol_id`, `boards_signs`, `boards_thickness` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` GROUP BY boardId, ebQuery.edgeBandingId")){
			$orderDetails = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orderDetails;
	}
	
	function returnOrderDetails($orderId){
		$orderDetails = array();
		if($result = $this -> dbo -> query("SELECT ob.`id` as boardId, `boards_signs`.`sign` as boardSign, boardSymbols.`symbol` as boardSymbol, `other_boards_symbols`.`symbol` as otherSymbol, `boards_thickness`.`thickness`, ob.`amount`, ob.`cutting_metters`, DATE_FORMAT(ob.`cutting_completion_date`, '%d-%m-%Y') as cuttingDate, DATE_FORMAT(ob.`cutting_completion_date`, '%H:%i') as cuttingTime, `cutting_comments`.`comment` as cuttingComment, GROUP_CONCAT(DISTINCT CONCAT_WS(' ', cuttingWorkers.`name`, cuttingWorkers.`surname`) SEPARATOR ',</br>') as cuttingWorkersNames, ebQuery.edgeBandingId as edgeBandingId, ebQuery.stickerSymbol, ebQuery.edgeBandType, ebQuery.edgeBandSymbol, ebQuery.wzMetters, ebQuery.machineMetters, ebQuery.edgeBandingDate, ebQuery.edgeBandingTime, ebQuery.edgeBandComment, ebQuery.edgeBandingComment, ebQuery.eBWorkers FROM `orders_boards` ob LEFT JOIN `other_boards_symbols` ON `other_boards_symbols`.`orders_boards_id`=ob.`id` LEFT JOIN (SELECT eb.`orders_boards_id` as ebObId, eb.`id` as edgeBandingId, `edge_band_sticker_symbols`.`symbol` as stickerSymbol, `edge_band_types`.`type` as edgeBandType, edgeBandSymbols.`symbol` as edgeBandSymbol, `eb`.`edge_banding_metters_wz` as wzMetters, `eb`.`edge_banding_metters_machine` as machineMetters, DATE_FORMAT(eb.`edge_banding_completion_date`, '%d-%m-%Y') as edgeBandingDate, DATE_FORMAT(eb.`edge_banding_completion_date`, '%H:%i') as edgeBandingTime,`edge_band_comments`.`comments` as edgeBandComment, `edge_banding_comments`.`comments` as edgeBandingComment, GROUP_CONCAT(DISTINCT CONCAT_WS(' ', edgeBandingWorkers.`name`, edgeBandingWorkers.`surname`) SEPARATOR ',</br>') as eBWorkers FROM `edge_banding` eb LEFT JOIN `boards_symbols` as edgeBandSymbols on edgeBandSymbols.`id`=eb.`board_symbol_id` LEFT JOIN `edge_banding_workers` ebw ON ebw.`edge_banding_id`=eb.`id` LEFT JOIN `workers` as edgeBandingWorkers on edgeBandingWorkers.`id`=ebw.`worker_id` LEFT JOIN `edge_banding_comments` ON eb.`id`=`edge_banding_comments`.`edge_banding_id` LEFT JOIN `edge_band_comments` ON `edge_band_comments`.`edge_banding_id`=eb.`id`, `edge_band_sticker_symbols`, `edge_band_types` WHERE `eb`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id` AND `eb`.`edge_band_type_id`=`edge_band_types`.`id` GROUP BY edgeBandingId) as ebQuery ON ebQuery.ebObId=ob.`id` LEFT JOIN `boards_symbols` as boardSymbols on boardSymbols.`id`=`ob`.`board_symbol_id` LEFT JOIN `cutting_workers` cw on cw.`orders_boards_id`=ob.`id` LEFT JOIN `workers` as cuttingWorkers ON cuttingWorkers.`id`=cw.`worker_id` LEFT JOIN `cutting_comments` ON `cutting_comments`.`orders_boards_id`=ob.`id`, `boards_signs`, `boards_thickness` WHERE ob.`order_id`={$orderId} AND ob.`board_sign_id`=`boards_signs`.`id` AND ob.`board_thickness_id`=`boards_thickness`.`id` GROUP BY boardId, ebQuery.edgeBandingId")){
			$orderDetails = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $orderDetails;
	}

	function findOrders($conditions){
		$query = "SELECT `orders`.`id` as orderId, `orders`.`document_number`, `workers`.`id` as sellerId, CONCAT_WS(' ', workers.`name`, workers.`surname`) as sellerName, `orders`.`saw_number`, `orders`.`admission_date`, `orders`.`order_completion_date`, `customers`.`id` as customerId,`customers`.`name` as customerName, `customers`.`surname` as customerSurname, `customers`.`phone` as phone,`customers_temp`.`name` as tempCustomerName, `customers_temp`.`phone` as tempPhone, `orders_comments`.`comments` as orderComment, MIN( IFNULL( `orders_boards`.`cutting_completion_date`, '0')) as minCuttingDate FROM `orders` LEFT JOIN `customers_temp` ON `customers_temp`.`order_id`=`orders`.`id` LEFT JOIN `orders_comments` ON `orders_comments`.`order_id`=`orders`.`id`, `orders_boards`,`customers`, `workers` WHERE `orders_boards`.`order_id`=`orders`.`id` AND `workers`.`id`=`orders`.`worker_id` AND `orders`.`customer_id`=`customers`.`id`";
		$query .= $conditions;
		$query .= " GROUP BY `orders`.`id` ORDER BY `orders`.`admission_date` DESC LIMIT 50";
		if(!$query = $this -> dbo -> query ($query)){
			return null;
		}
		
		if(!$result = $query -> fetchAll(PDO::FETCH_OBJ)){
		  return null; 
		}
		return $result;
	}
	
	function returnPotentialOrderCompletionDate($sawNumber, $boardsLimit){
		$date = $this -> returnLastOrderCompletionDate($sawNumber);
		$boardsAmountOfTheDay = $this -> returnBoardsAmoutPerDay($sawNumber, $date);
		
		if($boardsAmountOfTheDay >= $boardsLimit){
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
		$customerList = $customers -> returnCustomersList();
		
		$workers = new Workers($this -> dbo);
		$sellers = $workers -> returnSellers();

		$limits = new Limits($this -> dbo);
		$limits = $limits -> returnLimits();
		
		$potentialOrderCompletionDates[1] = $this -> returnPotentialOrderCompletionDate(1, $limits -> boardsPerDay);
		$potentialOrderCompletionDates[2] = $this -> returnPotentialOrderCompletionDate(2, $limits -> boardsPerDay);
		
		//$boardsAmount = $this -> returnBoardsAmoutPerDay(1, $potentialOrderCompletionDates[1]);
		
		$boardsSigns = $this -> returnBoardsSigns();
		$boardsSymbols = $this -> returnBoardsSymbols();
		$boardsThickness = $this -> returnBoardsThickness();
		
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbols();
		$edgeBandTypes = $this -> returnEdgeBandTypes();
		$edgeBandSymbols = $this -> returnEdgeBandSymbols();
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
		//$orderLists[0] = $this -> returnNotCutOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		//$orderLists[1]  = $this -> returnCutButNotCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		//$orderLists[2] = $this -> returnCompletedOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		$orderList = $this -> returnOrdersOfPeriod($_SESSION['dateFrom'], $_SESSION['dateTo']);
		
		include 'scripts/orderListForShopScripts.php';
		include 'templates/orderListForShop.php';
	}
	
	function showLastMadeOrders(){

		$orderList = $this -> returnLastMadeOrders(15);
		
		include 'scripts/lastMadeOrdersForShopScripts.php';
		include 'templates/lastMadeOrdersForShop.php';
	}
	
	function showOrderSearchingForm(){
		$documentType = filter_input (INPUT_POST, 'documentType');
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');
		$documentBranch = filter_input (INPUT_POST, 'documentBranch');
		$customerName = filter_input (INPUT_POST, 'customerName');
		$customerPhone = filter_input (INPUT_POST, 'customerPhone');
		
		include 'templates/orderSearchingFormForShop.php';
	}
	
	function showSearchResult(){
		if (!isset($_POST['documentType']) || !isset($_POST['documentNumber']) || !isset($_POST['documentBranch']) || !isset($_POST['customerName']) || !isset($_POST['customerPhone'])){
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

		$customerName = filter_input (INPUT_POST, 'customerName');	
		$customerId = filter_input (INPUT_POST, 'customerId');	
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');	
		$phone = filter_input (INPUT_POST, 'phone');	
		$comment = filter_input (INPUT_POST, 'comment');	
		$sellerId = filter_input (INPUT_POST, 'sellerId');	
		$sellerName = filter_input (INPUT_POST, 'sellerName');	
		$admissionDate = filter_input (INPUT_POST, 'admissionDate');	
		$completionDate = filter_input (INPUT_POST, 'completionDate');	
		$sawNumber = filter_input (INPUT_POST, 'sawNumber');	
		
		
		$customers = new Customers($this->dbo);
		$customerList = $customers -> returnCustomersList();
		
		$workers = new Workers($this -> dbo);
		$sellers = $workers -> returnSellers();
		
		$boardsSigns = $this -> returnBoardsSigns();
		$boardsSymbols = $this -> returnBoardsSymbols();
		$boardsThickness = $this -> returnBoardsThickness();
		
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbols();
		$edgeBandTypes = $this -> returnEdgeBandTypes();
		
		
		
		$orderId = intval($_POST['orderId']);
		
		$order = $this -> returnOrderDetails($orderId);
		
		include 'scripts/orderDetailsScripts.php';
		include 'templates/orderDetails.php';
	}
	
	function showOrderUpdatingForm(){
		if (!isset($_POST['orderId']) || $_POST['orderId'] ==''){
			return FORM_DATA_MISSING;
		}

		$customerName = filter_input (INPUT_POST, 'customerName');	
		$customerId = filter_input (INPUT_POST, 'customerId');	
		$documentNumber = filter_input (INPUT_POST, 'documentNumber');	
		$phone = filter_input (INPUT_POST, 'phone');	
		$comment = filter_input (INPUT_POST, 'comment');	
		$sellerId = filter_input (INPUT_POST, 'sellerId');	
		$sellerName = filter_input (INPUT_POST, 'sellerName');	
		$admissionDate = filter_input (INPUT_POST, 'admissionDate');	
		$completionDate = filter_input (INPUT_POST, 'completionDate');	
		$sawNumber = filter_input (INPUT_POST, 'sawNumber');	
		
		
		$customers = new Customers($this->dbo);
		$customerList = $customers -> returnCustomersList();
		$workers = new Workers($this -> dbo);
		$sellers = $workers -> returnSellers();
		
		$boardsSigns = $this -> returnBoardsSigns();
		$boardsSymbols = $this -> returnBoardsSymbols();
		$boardsThickness = $this -> returnBoardsThickness();
		
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbols();
		$edgeBandTypes = $this -> returnEdgeBandTypes();
		$edgeBandSymbols = $this -> returnEdgeBandSymbols();

		$orderId = intval($_POST['orderId']);
		
		$order = $this -> returnOrderDetailsForUpdatingForm($orderId);
		
		include 'scripts/orderUpdatingFormScripts.php';
		include 'templates/orderUpdatingForm.php';
	}
}
?>