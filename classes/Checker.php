<?php
class Checker
{
	function checkDocumentNumber(){
		if ($_POST['documentType'] == '4') {
			return true;
		}
		else if (is_numeric($_POST['documentNumber']) && (int)($_POST['documentNumber']) < 999999 && (int)($_POST['documentNumber']) > 0){
			$_POST['documentNumber'] = str_pad($_POST['documentNumber'], 6, "0", STR_PAD_LEFT);
			return true;
		}
		$_SESSION ['documentNumberError'] = "Wprowadź poprawny numer dokumentu!";
		return false;
	}
	
	function checkCustomerId(){
		if (!isset($_POST['customerId']) || $_POST['customerId'] == ''){
			$_SESSION['customerIdError'] = "Musisz wybrać klienta!";
			return false;
		}
		$_SESSION['customerId'] = $_POST['customerId'];
		if ($_POST['customerId'] == '1'){
			if (!isset($_POST['orderComment']) || $_POST['orderComment'] == ''){
				$_SESSION['orderCommentError'] = "W komentarzu podaj dane klienta";
				return false;
			}
			return true;
		}
		if ((int)($_POST['customerId']) <1){
			return false;
		}
		return true;
	}
	
	function checkAdmissionDate(){
		if ($_POST['admissionDate'] ==""){
			$_SESSION['admissionDateError'] = "Musisz podać datę złożenia zamówienia";
			return false;
		}
		
		$_SESSION['admissionDate'] = filter_input(INPUT_POST, 'admissionDate');
		$dateDifference = (strtotime(date('Y-m-d')) - strtotime($_POST['admissionDate'])) / (60*60*24);
		 if ($dateDifference > 14){
			$_SESSION['admissionDateError'] = "Możesz dodać zamówienie maksymalnie sprzed 14 dni!";
			return false;
		}  
		else if ($_POST['admissionDate'] > date('Y-m-d')){
			$_SESSION['admissionDateError'] = "Nie można dodać zamówienia z przyszłości";
			return false;
		}
		return true;
	}
	
	function checkOrderCompletionDate(){
		if ($_POST['orderCompletionDate'] ==""){
			$_SESSION['orderCompletionDateError'] = "Musisz podać datę realizacji";
			return false;
		}
		$_SESSION['orderCompletionDate']  = filter_input(INPUT_POST, 'orderCompletionDate');
		if (isset($_SESSION['admissionDateError'])){
			return false;
		}
		if ($_POST['orderCompletionDate'] < $_POST['admissionDate']){
			$_SESSION['orderCompletionDateError'] = "Realizacja nie może być wcześniej niż złożenie zamówienia";
			return false;
		}
		$dateDifference = (strtotime($_POST['orderCompletionDate'] ) - strtotime($_SESSION['admissionDate'])) / (60*60*24);
		if ($dateDifference > 30){
			$_SESSION['orderCompletionDateError'] = "Termin realizacji nie może być dłuższy niż 30 dni od daty złożenia zamówienia";
			return false;
		}
		return true;			
	}
	
	function checkOrderListPeriodDates(){
		if ($_POST['dateFrom'] > $_SESSION['dateTo']){
			$_SESSION['orderListPeriodError'] = "Należy podać zakres dat w kolejności od wcześniejszej do późniejszej";
			return false;
		}
		$dateDifference = (strtotime($_POST['dateFrom'] ) - strtotime($_SESSION['dateTo'])) / (60*60*24);
		
		if($dateDifference > 30){
			$_SESSION['orderListPeriodError'] = "Zakres dat nie może być dłuższy niż 30 dni";
			return false;
		}
		return true;
	}
}

?>