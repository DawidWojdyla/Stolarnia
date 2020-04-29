<?php
class Positions
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function returnBoardsSigns(){
		$boardsSigns = array();
		if($result = $this -> dbo -> query("SELECT `id`, `sign` FROM boards_signs ORDER BY CASE WHEN `priority`<> '0' THEN CONCAT(`priority`,', ',`sign`) ELSE `sign` END")){
			$boardsSigns = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSigns;
	}
	
	function returnBoardsSignsForUpdatingForm(){
		$boardsSigns = array();
		if($result = $this -> dbo -> query("SELECT `id`, `sign`, `priority`, `boards_signs_having_no_symbol`.`board_sign_id` as noSymbolNeeded, thicknessQuery.thicknessId, thicknessQuery.thickness FROM `boards_signs` LEFT JOIN `boards_signs_having_no_symbol` ON `boards_signs_having_no_symbol`.`board_sign_id`=`boards_signs`.`id` LEFT JOIN (SELECT `thickness`, `boards_signs_default_thickness`.`board_thickness_id` as thicknessId, `boards_signs_default_thickness`.`board_sign_id` as signId  FROM `boards_thickness`, `boards_signs_default_thickness` WHERE `boards_thickness`.`id`=`boards_signs_default_thickness`.`board_thickness_id`) as thicknessQuery ON thicknessQuery.signId=`id` ORDER BY `sign`")){
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
	
	function	addNewSignToDatabase($sign){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_signs` VALUES (NULL, :sign, 0)");
		$query -> bindValue (':sign', $sign, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewSign(){
		if (!isset($_POST['newSign']) || trim($_POST['newSign']) ==''){
			return 'ACTION_FAILED';
		}
		
		if($this -> addNewSignToDatabase($_POST['newSign']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if($id = $this -> dbo -> lastInsertId()){
			return $id;
		}
		return "ACTION_FAILED";
	}
	
	function removeSignFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_signs` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeSign(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		return $this -> removeSignFromDatabase($_POST['id']);
	}
	
	function setNewSignName($id, $sign){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `boards_signs` SET `sign`=:sign WHERE `id`=:id");
		$query -> bindValue (':sign', $sign, PDO::PARAM_STR);
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateSignName(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1 || !isset($_POST['sign']) || trim($_POST['sign']) ==''){
			return FORM_DATA_MISSING;
		}
		
		return $this -> setNewSignName($_POST['id'], $_POST['sign']);
		
	}
	
	function removeDefaultThicknessFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_signs_default_thickness` WHERE `board_sign_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeDefaultThickness(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeDefaultThicknessFromDatabase($_POST['signId']);
	}
	
	function setDefaultThicknessInDatabase($signId, $thicknessId){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("REPLACE INTO `boards_signs_default_thickness` VALUES (:signId, :thicknessId)");
		$query -> bindValue (':signId', $signId, PDO::PARAM_INT);
		$query -> bindValue (':thicknessId', $thicknessId, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function setDefaultThickness(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1 || !isset($_POST['thicknessId']) || $_POST['thicknessId'] == '' || (int)$_POST['thicknessId'] < 1){
			return FORM_DATA_MISSING;
		}
		
		return $this -> setDefaultThicknessInDatabase($_POST['signId'], $_POST['thicknessId']);
	}
	
	
	
	
	function setSignPriority($signId, $priority){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `boards_signs` SET `priority`=:priority WHERE `id`=:signId");
		$query -> bindValue (':signId', $signId, PDO::PARAM_INT);
		$query -> bindValue (':priority', $priority, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateSignPriority(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1 || !isset($_POST['priority']) || $_POST['priority'] == ''){
			return FORM_DATA_MISSING;
		}
		
		return $this -> setSignPriority($_POST['signId'], $_POST['priority']);
	}
	
	

	function showBoardsSignsUpdatingForm(){
		$boardsSigns = $this -> returnBoardsSignsForUpdatingForm();
		$boardsThickness = $this -> returnBoardsThickness();
		
		include 'scripts/boardsSignsUpdatingFormScripts.php';
		include 'templates/boardsSignsUpdatingForm.php';
	}
}
?>