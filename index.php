<?php
include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{
	$joinery = new Joinery("localhost", "root", "", "joinery8");
	$action = 'showLoginForm';
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	
	$message = $joinery -> getMessage();
	$delay = $joinery -> getDelay();
	
	if(!$message && $action == 'showLoginForm'){
		$message = 'Wybierz stanowisko i wprowadź hasło';
	}
	if($action == 'showLoginForm' && $joinery -> stand){
		header('Location:index.php?action=showMain');
		return;
	}
	
	switch($action){
		case 'login' :
			switch($joinery -> login()){
				case ACTION_OK :
					$joinery -> setMessage('Zalogowanie prawidłowe');
					$joinery -> setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case NO_LOGIN_REQUIRED :
					$joinery -> setMessage('Najpierw proszę się wylogować.');
					$joinery -> setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case ACTION_FAILED :
				case FORM_DATA_MISSING :
					$joinery->setMessage('Logowanie nie powiodło się.');
					$joinery->setHidingMessageDelay(3000);
					break;
				default:
					$joinery->setMessage('Błąd serwera. Zalogowanie nie jest obecnie możliwe.');
					$joinery->setHidingMessageDelay(3000);
			}
			header('Location:index.php?action=showLoginForm');
			break;
		case 'addNewOrder':
			switch ($joinery -> addNewOrder()):
				case ACTION_OK:
					$joinery->setMessage('Dodano nowe zlecenie.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case FORM_DATA_MISSING:
					$joinery->setMessage('Proszę wypełnić poprawnie wymagane pola formularza.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case ACTION_FAILED:
					$joinery->setMessage('Obecnie dodanie zlecenia nie jest możliwe.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case DOCUMENT_NUMBER_ALREADY_EXISTS:
					$joinery->setMessage('Dokument o takim numerze już istnieje w bazie.');
					break;
				case NO_PERMISSION:
					$joinery->setMessage('Brak uprawnień do dodania nowego zlecenia.');
					$joinery->setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case SERVER_ERROR:
				default:
					$joinery->setMessage('Błąd serwera!');
					$joinery->setHidingMessageDelay(3000);
			endswitch;
			header('Location:index.php?action=showOrderAddingForm');
			break;
		case 'addNewCustomer':
			switch ($joinery -> addNewCustomer()):
				case ACTION_OK:
					$joinery->setMessage('Dodano nowego klienta.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case FORM_DATA_MISSING:
					$joinery->setMessage('Proszę wypełnić poprawnie wymagane pola formularza.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case ACTION_FAILED:
					$joinery->setMessage('Obecnie dodanie klienta nie jest możliwe.');
					$joinery->setHidingMessageDelay(3000);
					break;
				case CUSTOMER_NUMBER_ALREADY_EXISTS:
					$joinery->setMessage('Klient o takim numerze telefonu już istnieje w bazie.');
					break;
				case NO_PERMISSION:
					$joinery->setMessage('Brak uprawnień do dodania nowego klienta.');
					$joinery->setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case SERVER_ERROR:
				default:
					$joinery->setMessage('Błąd serwera!');
					$joinery->setHidingMessageDelay(3000);
			endswitch;
			header('Location:index.php?action=showCustomerAddingForm');
			break;
		case 'removeCustomer':
			switch ($joinery -> removeCustomer()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'restoreCustomer':
			switch ($joinery -> restoreCustomer()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'removeOrder':
			switch ($joinery -> removeOrder()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateDocumentNumber':
			switch ($joinery -> updateDocumentNumber()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case DOCUMENT_NUMBER_ALREADY_EXISTS:
					print_r('DOCUMENT_NUMBER_ALREADY_EXISTS');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerId':
			switch ($joinery -> updateCustomerId()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerIdAndRemoveTempData':
			switch ($joinery -> updateCustomerIdAndRemoveTempData()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerTempData':
			switch ($joinery -> updateCustomerTempData()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerIdAndAddTempData':
			switch ($joinery -> updateCustomerIdAndAddTempData()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateSawNumber':
			switch ($joinery -> updateSawNumber()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateAdmissionDate':
			switch ($joinery -> updateAdmissionDate()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCompletionDate':
			switch ($joinery -> updateCompletionDate()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateSeller':
			switch ($joinery -> updateSeller()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateOrderComment':
			switch ($joinery -> updateOrderComment()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'addNewBoard':
			 print_r($joinery -> addNewBoard());
			break;	
		case 'updateBoard':
			switch ($joinery -> updateBoard()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'removeBoard':
			switch ($joinery -> removeBoard()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateBoardAmount':
			switch ($joinery -> updateBoardAmount()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCuttingMetters':
			switch ($joinery -> updateCuttingMetters()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateEdgeBanding':
			switch ($joinery -> updateEdgeBanding()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'removeEdgeBanding':
			switch ($joinery -> removeEdgeBanding()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'addNewEdgeBanding':
			 print_r($joinery -> addNewEdgeBanding());
			break;	
		case 'returnAmountsOfPeriod':
			 print_r($joinery -> returnAmountsOfPeriod());
			break;	
		case 'setTheBoardCutting':
			switch ($joinery -> setTheBoardCutting()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'setEdgeBanding':
			switch ($joinery -> setEdgeBanding()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateEdgeBandingMachineMetters':
			switch ($joinery -> updateEdgeBandingMachineMetters()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'setTheBoardComment':
			switch ($joinery -> setTheBoardComment()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateEdgeBandingComment':
			switch ($joinery -> updateEdgeBandingComment()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'resetTheBoardCutting':
			switch ($joinery -> resetTheBoardCutting()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
					break;
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'resetEdgeBanding':
			switch ($joinery -> resetEdgeBanding()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
					break;
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerName':
			switch ($joinery -> updateCustomerName()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerSurname':
			switch ($joinery -> updateCustomerSurname()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerPhone':
			switch ($joinery -> updateCustomerPhone()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateCustomerAddress':
			switch ($joinery -> updateCustomerAddress()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'logout':
			$joinery->logout();
			header('Location:index.php?action=showLoginForm');
			break;
		default:
			include 'templates/mainTemplate.php';
	}
}
catch(Exception $e){
	echo 'Błąd: ' . $e->getMessage();
	//exit('Portal chwilowo niedostępny');
}

function classLoader($name){
	if(file_exists("classes/$name.php")){
		require_once("classes/$name.php");
	}
	else{
		throw new Exception("Brak pliku z definicją klasy.");
	}
}
?>