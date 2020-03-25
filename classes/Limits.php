<?php
class Limits
{
	private $dbo = null;
	
	function __construct($dbo){
		$this -> dbo = $dbo;
	}
	
	function returnBoardsLimitPerDay(){
		if($result = $this -> dbo -> query("SELECT `boardsPerDay` FROM limits WHERE id='1'")){
			$limit = $result -> fetch(PDO::FETCH_NUM);
		}
		return $limit[0];
	}
	
	function returnCuttingMettersLimitPerDay(){
		if($result = $this -> dbo -> query("SELECT `cuttingMettersPerDay` FROM limits WHERE id='1'")){
			$limit = $result -> fetch(PDO::FETCH_NUM);
		}
		return $limit[0];
	}
	
	function returnEdgeBandingMettersLimitPerDay(){
		if($result = $this -> dbo -> query("SELECT `edgeBandingMettersPerDay` FROM limits WHERE id='1'")){
			$limit = $result -> fetch(PDO::FETCH_NUM);
		}
		return $limit[0];
	}
	
	function returnLimits(){
		if($result = $this -> dbo -> query("SELECT `boardsPerDay`, `cuttingMettersPerDay`, `edgeBandingMettersPerDay` FROM limits WHERE id='1'")){
			$limits = $result -> fetch(PDO::FETCH_OBJ);
		}
		return $limits;
	}
	
	function showLimitsUpdatingForm(){
		
		$limits = $this -> returnLimits();
		
		include 'scripts/limitsUpdatingFormScripts.php';
		include 'templates/limitsUpdatingForm.php';
		
	}
	
	function	setBoardsLimit($boardsAmount){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `limits` SET `boardsPerDay`=:boardsAmount WHERE `id`=1");
		$query -> bindValue (':boardsAmount', $boardsAmount, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateBoardsLimit(){
		if (!isset($_POST['boardsLimit']) || $_POST['boardsLimit'] =='' || (int)$_POST['boardsLimit'] < 1){
			return FORM_DATA_MISSING;
		}
		return $this -> setBoardsLimit($_POST['boardsLimit']);
	}
	
	function	setCuttingMettersLimit($cuttingMetters){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `limits` SET `cuttingMettersPerDay`=:cuttingMetters WHERE `id`=1");
		$query -> bindValue (':cuttingMetters', $cuttingMetters, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateCuttingMettersLimit(){
		if (!isset($_POST['cuttingMetters']) || $_POST['cuttingMetters'] =='' || (int)$_POST['cuttingMetters'] < 100){
			return FORM_DATA_MISSING;
		}
		return $this -> setCuttingMettersLimit($_POST['cuttingMetters']);
	}
	
	function	setEdgeBandingMettersLimit($edgeBandingMetters){
		if(!$this -> dbo){
			return SERVER_ERROR;
		}
		$query = $this -> dbo -> prepare ("UPDATE `limits` SET `edgeBandingMettersPerDay`=:edgeBandingMetters WHERE `id`=1");
		$query -> bindValue (':edgeBandingMetters', $edgeBandingMetters, PDO::PARAM_INT);
		if(!$query -> execute()){
			return ACTION_FAILED;
		}
		return ACTION_OK;
	}
	
	function updateEdgeBandingMettersLimit(){
		if (!isset($_POST['edgeBandingMetters']) || $_POST['edgeBandingMetters'] =='' || (int)$_POST['edgeBandingMetters'] < 100){
			return FORM_DATA_MISSING;
		}
		return $this -> setEdgeBandingMettersLimit($_POST['edgeBandingMetters']);
	}
}
?>