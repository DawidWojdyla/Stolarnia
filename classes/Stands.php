<?php
class Stands
{
	private $dbo = null;
	
	function __construct($dbo){
		$this->dbo = $dbo;
	}

	function returnStandsList(){
		$standsList = array();
		if($result = $this -> dbo -> query("SELECT `id`, `name` FROM stands")){
			$standsList = $result -> fetchAll(PDO::FETCH_OBJ);
		}
		return $standsList;
	}
}
?>