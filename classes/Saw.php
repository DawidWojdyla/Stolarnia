<?php
class Saw
{
	private $dbo			 	= null;
	private $sawNumber 	= null;
	
	function __construct($dbo, $sawNumber){
		$this->dbo = $dbo;
		$this->sawNumber = $sawNumber;
	}
	
	function changeOrdersBoardsState($orderBoardId, $stateId){
		if( !$this->dbo) return SERVER_ERROR;
		
		$query = $this -> dbo -> prepare ("UPDATE `orders_boards` SET cutting_state_id=:stateId WHERE id=:orderBoardId");
		$query -> bindValue (':stateId', $stateId, PDO::PARAM_INT);
		$query -> bindValue (':orderBoardId', PDO::PARAM_INT);
		
		if ($query -> execute()) return ACTION_OK;
		else return ACTION_FAILED;	
	}
}
?>