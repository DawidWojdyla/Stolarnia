<?php
class Orders
{
	private $dbo = null;
	
	function __construct($dbo)
	{
		$this->dbo = $dbo;
	}
	
	function addNewOrderToTheBase{
		if( !$this->dbo) return SERVER_ERROR;
		
		$query  = $this->dbo -> prepare ("INSERT INTO `orders` VALUES (NULL, :customerId, :userId, :sawNumber, :admissionDate, :orderCompletionDate, :comments)");
		$query -> bindValue (':customerId', $order->getCustomerId(), PDO::PARAM_INT);
		$query -> bindValue (':userId', $order->getUserId(), PDO::PARAM_INT);
		$query -> bindValue (':sawNumber', $order->getSawNumber(), PDO::PARAM_INT);
		$query -> bindValue (':admissionDate', $order->getAdmissionDate(), PDO::PARAM_STR);
		$query -> bindValue (':orderCompletionDate', $order->getOrderCompletionDate(), PDO::PARAM_STR);
		$query -> bindValue (':comments', $order->getComments(), PDO::PARAM_STR);
		
		if ($query -> execute()){
		unset($_SESSION['customerIdSes']);
		unset($_SESSION['userIdSes']);
		unset($_SESSION['sawNumberSes']);
		unset($_SESSION['admissionDateSes']);
		unset($_SESSION['orderCompletionDateSes']);
		unset($_SESSION['commentsSes']);
		return ACTION_OK;
		}
		else return ACTION_FAILED;	
	}
}
?>