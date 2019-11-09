<?php
class Order
{
	private $id;
	private $customerId;
	private $workerId;
	private $sawNumber;
	private $admissionDate;
	private $orderCompletionDate;
	private $cuttingStateId;
	private $comments;
  
	function __construct($id, $customerId, $workerId, $sawNumber, $admissionDate, $orderCompletionDate, $cuttingStateId, $comments = ""){
		$this->id 									= $id;
		$this->customerId 					= $customerId;
		$this->userId 							= $workerId;
		$this->sawNumber 					= $sawNumber;
		$this->admissionDate 			= $admissionDate;
		$this->orderCompletionDate 	= $orderCompletionDate;
		$this->cuttingStateId 				= $cuttingStateId;
		$this->comments 					= $comments;
	}
	
	function setId($id){
		$this->id = $id;
	}
	
	function setCustomerId($customerId){
		$this->customerId = $customerId;
	}
	
	function setWorkerId($workerId){
		$this->workerId = $workerId;
	}
	
	function setSawNumber($sawNumber){
		$this->sawNumber = $sawNumber;
	}
	
	function setIAdmissionDate($admissionDate){
		$this->admissionDate = $admissionDate;
	}
	
	function setCuttingStateId($cuttingStateId){
		$this->cuttingStateId = $cuttingStateId;
	}
	
	function setOrderCompletionDate($orderCompletionDate){
		$this->orderCompletionDate = $orderCompletionDate;
	}
	
	function setComments($comments){
		$this->comments = $comments;
	}
	
	function getId(){
		return $this->id;
	}
	
	function getCustomerId(){
		return $this->customerId;
	}
	
	function getWorkerId(){
		return $this->workerId;
	}
	
	function getSawNumber(){
		return $this->sawNumber;
	}
	
	function getAdmissionDate(){
		return $this->admissionDate;
	}
	
	function getOrderCompletionDate(){
		return $this->orderCompletionDate;
	}
	
	function getCuttingStateId(){
		return $this->cuttingStateId;
	}
	
	function getComments(){
		return $this->comments;
	}
	
	
}
?>