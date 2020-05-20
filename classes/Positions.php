<?php
class Positions
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function returnBoardsSigns(){
		$boardsSigns = array();
		if($result = $this -> dbo -> query("SELECT `id`, `sign`, `boards_signs_default_thickness`.`board_thickness_id` as thicknessId, `boards_signs_having_no_symbol`.`board_sign_id` as noSymbolSignId FROM `boards_signs` LEFT JOIN `boards_signs_having_no_symbol` ON `boards_signs_having_no_symbol`.`board_sign_id`= `boards_signs`.`id` LEFT JOIN `boards_signs_default_thickness` ON `boards_signs_default_thickness`.`board_sign_id`=`boards_signs`.`id` WHERE `id` NOT IN (SELECT `board_sign_id` FROM `boards_signs_hidden`) ORDER BY CASE WHEN `priority`<> '0' THEN CONCAT(`priority`,', ',`sign`) ELSE `sign` END")){
			$boardsSigns = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSigns;
	}
	
	function returnBoardsSignsForUpdatingForm(){
		$boardsSigns = array();
		if($result = $this -> dbo -> query("SELECT `id`, `sign`, `priority`, `boards_signs_having_no_symbol`.`board_sign_id` as noSymbolNeeded, thicknessQuery.thicknessId, thicknessQuery.thickness, `boards_signs_hidden`.`board_sign_id` as hidden FROM `boards_signs` LEFT JOIN `boards_signs_having_no_symbol` ON `boards_signs_having_no_symbol`.`board_sign_id`=`boards_signs`.`id` LEFT JOIN `boards_signs_hidden` ON `boards_signs_hidden`.`board_sign_id`=`boards_signs`.`id` LEFT JOIN (SELECT `thickness`, `boards_signs_default_thickness`.`board_thickness_id` as thicknessId, `boards_signs_default_thickness`.`board_sign_id` as signId  FROM `boards_thickness`, `boards_signs_default_thickness` WHERE `boards_thickness`.`id`=`boards_signs_default_thickness`.`board_thickness_id`) as thicknessQuery ON thicknessQuery.signId=`id` ORDER BY `sign`")){
			$boardsSigns = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSigns;
	}
	
	function returnBoardsSymbols(){
		$boardsSymbols = array();
		if($result = $this -> dbo -> query ("SELECT `id`, `symbol` FROM boards_symbols WHERE `id` NOT IN (SELECT `board_symbol_id` FROM `boards_symbols_hidden`) ORDER BY `symbol` ASC")){
			$boardsSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsSymbols;
	}
	
	function returnBoardsSymbolsForUpdatingForm(){
		$boardsSymbols = array();
		if($result = $this -> dbo -> query ("SELECT `id`, `symbol`, `boards_symbols_hidden`.`board_symbol_id` as hidden  FROM boards_symbols LEFT JOIN `boards_symbols_hidden` ON `boards_symbols_hidden`.`board_symbol_id`=`boards_symbols`.`id` WHERE `id` <> '1' ORDER BY `symbol` ASC")){
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
		if($result = $this -> dbo -> query("SELECT `id`, `thickness` FROM boards_thickness WHERE `id` NOT IN (SELECT `board_thickness_id` FROM `boards_thickness_hidden`)  ORDER BY `thickness`")){
			$boardsThickness = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsThickness;
	}
	
	function returnBoardsThicknessForUpdatingForm(){
		$boardsThickness = array();
		if($result = $this -> dbo -> query("SELECT `id`, `thickness`, `boards_thickness_hidden`.`board_thickness_id` as hidden FROM boards_thickness LEFT JOIN `boards_thickness_hidden` ON `boards_thickness_hidden`.`board_thickness_id`=`boards_thickness`.`id` ORDER BY `thickness`")){
			$boardsThickness = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $boardsThickness;
	}
	
	function returnEdgeBandStickerSymbols(){
		$edgeBandStickerSymbols = array();
		if($result = $this -> dbo -> query("SELECT `id`, `symbol` FROM edge_band_sticker_symbols WHERE `id` NOT IN (SELECT `edge_band_sticker_symbol_id` FROM `edge_band_sticker_symbols_hidden`)")){
			$edgeBandStickerSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandStickerSymbols;
	}
	
	function returnEdgeBandStickerSymbolsForUpdatingForm(){
		$edgeBandStickerSymbols = array();
		if($result = $this -> dbo -> query("SELECT `id`, `symbol`, `edge_band_sticker_symbols_hidden`.`edge_band_sticker_symbol_id` as hidden FROM `edge_band_sticker_symbols` LEFT JOIN `edge_band_sticker_symbols_hidden` ON `edge_band_sticker_symbols_hidden`.`edge_band_sticker_symbol_id`=`edge_band_sticker_symbols`.`id`")){
			$edgeBandStickerSymbols = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandStickerSymbols;
	}
	
	function returnEdgeBandTypes(){
		$edgeBandTypes = array();
		if($result = $this -> dbo -> query("SELECT `id`, `type` FROM edge_band_types WHERE `id` NOT IN (SELECT `edge_band_type_id` FROM `edge_band_types_hidden`) ORDER BY `id`<10 DESC, `type`")){
			$edgeBandTypes = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandTypes;
	}
	
	function returnEdgeBandTypesForUpdatingForm(){
		$edgeBandTypes = array();
		if($result = $this -> dbo -> query("SELECT `id`, `type`, `edge_band_types_hidden`.`edge_band_type_id` as hidden, `edge_band_types_default`.`max_thickness` FROM `edge_band_types` LEFT JOIN `edge_band_types_hidden` ON `edge_band_types_hidden`.`edge_band_type_id`=`edge_band_types`.`id` LEFT JOIN `edge_band_types_default` ON `edge_band_types_default`.`edge_band_type_id`=`edge_band_types`.`id` WHERE `type`<> 'Inny' ORDER BY `type`")){
			$edgeBandTypes = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandTypes;
	}
	
	function returnEdgeBandDefaultThickness(){
		$edgeBandTypesDefault = array();
		if($result = $this -> dbo -> query("SELECT `max_thickness`, `edge_band_type_id` FROM `edge_band_types_default`")){
			$edgeBandTypesDefault = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandTypesDefault;
	}
	
	/*function returnEdgeBandDefaultThicknessOrderedDesc(){
		$edgeBandTypesDefault = array();
		if($result = $this -> dbo -> query("SELECT `max_thickness`, `edge_band_type_id` FROM `edge_band_types_default` ORDER BY `max_thickness` DESC")){
			$edgeBandTypesDefault = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $edgeBandTypesDefault;
	}*/
	
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

		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> removeDefaultThicknessFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeFromNoSymbolsList($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeSignFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
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
	
	function	addToNoSymbolsList($sign){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_signs_having_no_symbol` VALUES (:sign)");
		$query -> bindValue (':sign', $sign, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function blockAddingSymbols(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addToNoSymbolsList($_POST['signId']);
	}
	
	function removeFromNoSymbolsList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_signs_having_no_symbol` WHERE `board_sign_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function unblockAddingSymbols(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeFromNoSymbolsList($_POST['signId']);
	}

	function removeSignIdFromHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_signs_hidden` WHERE `board_sign_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeSignFromHidden(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeSignIdFromHiddenList($_POST['signId']);
	}
	
	function	addSignToHiddenList($sign){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_signs_hidden` VALUES (:sign)");
		$query -> bindValue (':sign', $sign, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function hideSign(){
		if (!isset($_POST['signId']) || $_POST['signId'] =='' || (int)$_POST['signId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addSignToHiddenList($_POST['signId']);
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
	
	function removeThicknessIdFromHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_thickness_hidden` WHERE `board_thickness_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeThicknessFromHidden(){
		if (!isset($_POST['thicknessId']) || $_POST['thicknessId'] =='' || (int)$_POST['thicknessId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeThicknessIdFromHiddenList($_POST['thicknessId']);
	}
	
	function	addThicknessToHiddenList($thicknessId){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_thickness_hidden` VALUES (:thicknessId)");
		$query -> bindValue (':thicknessId', $thicknessId, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function hideThickness(){
		if (!isset($_POST['thicknessId']) || $_POST['thicknessId'] =='' || (int)$_POST['thicknessId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addThicknessToHiddenList($_POST['thicknessId']);
	}
	
		function removeThicknessFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_thickness` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeThickness(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> removeThicknessIdFromHiddenList($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeThicknessFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
		function	addNewThicknessToDatabase($thickness){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_thickness` VALUES (NULL, :thickness)");
		$query -> bindValue (':thickness', $thickness, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewThickness(){
		if (!isset($_POST['newThickness']) || trim($_POST['newThickness']) ==''){
			return 'ACTION_FAILED';
		}
		
		if($this -> addNewThicknessToDatabase($_POST['newThickness']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if($id = $this -> dbo -> lastInsertId()){
			return $id;
		}
		return "ACTION_FAILED";
	}
	
	function showBoardsThicknessUpdatingForm(){
		$boardsThickness = $this -> returnBoardsThicknessForUpdatingForm();
		
		include 'scripts/boardsThicknessUpdatingFormScripts.php';
		include 'templates/boardsThicknessUpdatingForm.php';
	}
	
	function removeSymbolIdFromHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_symbols_hidden` WHERE `board_symbol_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeSymbolFromHidden(){
		if (!isset($_POST['symbolId']) || $_POST['symbolId'] =='' || (int)$_POST['symbolId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeSymbolIdFromHiddenList($_POST['symbolId']);
	}
	
	function	addSymbolToHiddenList($symbolId){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_symbols_hidden` VALUES (:symbolId)");
		$query -> bindValue (':symbolId', $symbolId, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function hideSymbol(){
		if (!isset($_POST['symbolId']) || $_POST['symbolId'] =='' || (int)$_POST['symbolId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addSymbolToHiddenList($_POST['symbolId']);
	}
	
		function removeSymbolFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `boards_symbols` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeSymbol(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> removeSymbolIdFromHiddenList($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeSymbolFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function	addNewSymbolToDatabase($symbol){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `boards_symbols` VALUES (NULL, :symbol)");
		$query -> bindValue (':symbol', $symbol, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewSymbol(){
		if (!isset($_POST['newSymbol']) || trim($_POST['newSymbol']) ==''){
			return 'ACTION_FAILED';
		}
		
		if($this -> addNewSymbolToDatabase($_POST['newSymbol']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if($id = $this -> dbo -> lastInsertId()){
			return $id;
		}
		return "ACTION_FAILED";
	}
	
	function showBoardsSymbolsUpdatingForm(){
		$boardsSymbols = $this -> returnBoardsSymbolsForUpdatingForm();
		
		include 'scripts/boardsSymbolsUpdatingFormScripts.php';
		include 'templates/boardsSymbolsUpdatingForm.php';
	}
	
	function removeEdgeBandTypeIdFromHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_types_hidden` WHERE `edge_band_type_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeEdgeBandTypeFromHidden(){
		if (!isset($_POST['edgeBandTypeId']) || $_POST['edgeBandTypeId'] =='' || (int)$_POST['edgeBandTypeId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeEdgeBandTypeIdFromHiddenList($_POST['edgeBandTypeId']);
	}
	
	function	addEdgeBandTypeToHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_band_types_hidden` VALUES (:id)");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function hideEdgeBandType(){
		if (!isset($_POST['edgeBandTypeId']) || $_POST['edgeBandTypeId'] =='' || (int)$_POST['edgeBandTypeId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addEdgeBandTypeToHiddenList($_POST['edgeBandTypeId']);
	}
	
	function removeEdgeBandTypeFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_types` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeEdgeBandType(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> removeEdgeBandTypeIdFromHiddenList($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeEdgeBandTypeFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function	addNewEdgeBandTypeToDatabase($type){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_band_types` VALUES (NULL, :type)");
		$query -> bindValue (':type', $type, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewEdgeBandType(){
		if (!isset($_POST['newEdgeBandType']) || trim($_POST['newEdgeBandType']) ==''){
			return 'ACTION_FAILED';
		}
		
		if($this -> addNewEdgeBandTypeToDatabase($_POST['newEdgeBandType']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if($id = $this -> dbo -> lastInsertId()){
			return $id;
		}
		return "ACTION_FAILED";
	}
	
	function	setDefaultEdgeBandType($id, $maxThickness){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `edge_band_types_default` SET `edge_band_type_id`=:id WHERE `max_thickness`=:maxThickness");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		$query -> bindValue (':maxThickness', $maxThickness, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateDefaultEdgeBandType(){
		if (!isset($_POST['edgeBandTypeId']) || $_POST['edgeBandTypeId'] =='' || (int)$_POST['edgeBandTypeId'] < 1 || !isset($_POST['maxThickness']) || $_POST['maxThickness'] =='' ){
			return FORM_DATA_MISSING;
		}
		return $this -> setDefaultEdgeBandType($_POST['edgeBandTypeId'], $_POST['maxThickness']);
	}
	
	function showEdgeBandTypesUpdatingForm(){
		$edgeBandTypes = $this -> returnEdgeBandTypesForUpdatingForm();
		$edgeBandTypesDefault = $this -> returnEdgeBandDefaultThickness();
		
		include 'scripts/edgeBandTypesUpdatingFormScripts.php';
		include 'templates/edgeBandTypesUpdatingForm.php';
	}

	function removeEdgeBandStickerSymbolIdFromHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_sticker_symbols_hidden` WHERE `edge_band_sticker_symbol_id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeEdgeBandStickerSymbolFromHidden(){
		if (!isset($_POST['edgeBandStickerSymbolId']) || $_POST['edgeBandStickerSymbolId'] =='' || (int)$_POST['edgeBandStickerSymbolId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> removeEdgeBandStickerSymbolIdFromHiddenList($_POST['edgeBandStickerSymbolId']);
	}
	
	function	addEdgeBandStickerSymbolToHiddenList($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_band_sticker_symbols_hidden` VALUES (:id)");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function hideEdgeBandStickerSymbol(){
		if (!isset($_POST['edgeBandStickerSymbolId']) || $_POST['edgeBandStickerSymbolId'] =='' || (int)$_POST['edgeBandStickerSymbolId'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> addEdgeBandStickerSymbolToHiddenList($_POST['edgeBandStickerSymbolId']);
	}
	
	function removeEdgeBandStickerSymbolFromDatabase($id){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("DELETE FROM `edge_band_sticker_symbols` WHERE `id`=:id");
		$query -> bindValue (':id', $id, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function removeEdgeBandStickerSymbol(){
		if (!isset($_POST['id']) || $_POST['id'] =='' || (int)$_POST['id'] < 1){
			return FORM_DATA_MISSING;
		}
		
		if (!$this -> dbo -> beginTransaction()){
			return SERVER_ERROR;
		}
		
		if ($this -> removeEdgeBandStickerSymbolIdFromHiddenList($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if ($this -> removeEdgeBandStickerSymbolFromDatabase($_POST['id']) != ACTION_OK ){
			return ACTION_FAILED;
		}
		
		if(!$this -> dbo -> commit()){
			return SERVER_ERROR;
		}
		return ACTION_OK;
	}
	
	function	addNewEdgeBandStickerSymbolToDatabase($stickerSymbol){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("INSERT INTO `edge_band_sticker_symbols` VALUES (NULL, :stickerSymbol)");
		$query -> bindValue (':stickerSymbol', $stickerSymbol, PDO::PARAM_STR);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function addNewEdgeBandStickerSymbol(){
		if (!isset($_POST['newEdgeBandStickerSymbol']) || trim($_POST['newEdgeBandStickerSymbol']) ==''){
			return 'ACTION_FAILED';
		}
		
		if($this -> addNewEdgeBandStickerSymbolToDatabase($_POST['newEdgeBandStickerSymbol']) != ACTION_OK){
			return "ACTION_FAILED";
		}
		if($id = $this -> dbo -> lastInsertId()){
			return $id;
		}
		return "ACTION_FAILED";
	}
	
	function showEdgeBandStickerSymbolsUpdatingForm(){
		$edgeBandStickerSymbols = $this -> returnEdgeBandStickerSymbolsForUpdatingForm();
		
		include 'scripts/edgeBandStickerSymbolsUpdatingFormScripts.php';
		include 'templates/edgeBandStickerSymbolsUpdatingForm.php';
	}
	
	
	
	
	
	
	
}
?>