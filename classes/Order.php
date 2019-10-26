<?php
class Order
{
	private $id;
	private $customerId;
	private $userId;
	private $sawNumber;
	private $admissionDate;
	private $orderCompletionDate;
	private $comments;
  
	function __construct($id, $customerId, $userId, $sawNumber, $admissionDate, $orderCompletionDate, $comments = ""){
		$this->id 									= $id;
		$this->customerId 					= $customerId;
		$this->userId 							= $userId;
		$this->sawNumber 					= $sawNumber;
		$this->admissionDate 			= $admissionDate;
		$this->orderCompletionDate 	= $orderCompletionDate;
		$this->comments 					= $comments;
	}
	
	function setId($id){
		$this->id = $id;
	}
	
	function setCustomerId($customerId){
		$this->customerId = $customerId;
	}
	
	function setUserId($userId){
		$this->userId = $userId;
	}
	
	function setSawNumber($sawNumber){
		$this->sawNumber = $sawNumber;
	}
	
	function setIAdmissionDate($admissionDate){
		$this->admissionDate = $admissionDate;
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
	
	function getUserId(){
		return $this->userId;
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
	
	function getComments(){
		return $this->comments;
	}
	
	
}
?>