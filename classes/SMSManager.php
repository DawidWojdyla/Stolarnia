<?php
class SMSManager
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function returnSMSList(){
		$smsList = array();
		if($result = $this -> dbo -> query("SELECT `id`, REPLACE(`message`,'%20',' ') as content FROM messages")){
			$smsList = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $smsList;
	}
	
	function returnActiveSMSId(){
		if($result = $this -> dbo -> query("SELECT `message_id` FROM `messages_active_one` WHERE id='1'")){
			$activeSMSId = $result -> fetch(PDO::FETCH_NUM);
		}
		return $activeSMSId[0];
	}
	
	function returnActiveSMSContent() {
		if($result = $this -> dbo -> query("SELECT `message` FROM `messages`, `messages_active_one` WHERE `messages`.`id`=`messages_active_one`.`message_id` AND `messages_active_one`.`id`='1'" )){
			$activeSMSContent = $result -> fetch(PDO::FETCH_NUM);
		}
		return $activeSMSContent[0];
	}
	
	function showSMSUpdatingForm(){
		
		$smsList = $this -> returnSMSList();
		$activeSMSId = $this -> returnActiveSMSId();
		
		include 'scripts/smsUpdatingFormScripts.php';
		include 'templates/smsUpdatingForm.php';
		
	}
	
	function	setSMSContent($id, $sms){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `messages` SET `message`=:sms WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		$query -> bindValue (':sms', $sms, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateSMSContent(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1 || trim($_POST['sms']) =="" ){
			return FORM_DATA_MISSING;
		}
		$sms = str_replace(' ', '%20', $_POST['sms']);
		return $this -> setSMSContent($_POST['id'], $sms);
	}
	
	function	setActiveSMS($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `messages_active_one` SET `message_id`=:id WHERE `id`=1");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateActiveSMS(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> setActiveSMS($_POST['id']);
	}
	
	function	addNewSMSContentToDatabase($sms){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `messages` VALUES (NULL, :sms)");
		$query -> bindValue (':sms', $sms, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewSMSContent(){
		if (!isset($_POST['sms']) || trim($_POST['sms']) =='' ){
			return 'ACTION_FAILED';
		}
		
		$sms = str_replace(' ', '%20', $_POST['sms']);
		
		if($this -> addNewSMSContentToDatabase($sms) != ACTION_OK){
			return "ACTION_FAILED";
		}
		return $this -> dbo -> lastInsertId();
	}
	
	function removeSMSFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `messages` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeSMSContent(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		return $this -> removeSMSFromDatabase($_POST['id']);
	}
	
}
?>