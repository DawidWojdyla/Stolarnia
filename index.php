<?php
include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{
	$joinery = new Joinery("localhost", "root", "", "joinery");
	$action = 'showMain';
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	
	$message = $joinery->getMessage();
	$delay = $joinery -> getDelay();
	
	if(!$message && $action == 'showLoginForm'){
		$message = 'Wybierz stanowisko i wprowadź hasło';
	}
	if($action == 'showLoginForm' && $joinery->stand){
		$joinery->setMessage('Najpierw proszę się wylogować.');
		header('Location:index.php?action=showMain');
		return;
	}
	
	switch($action){
		case 'login' :
			switch($joinery->login()){
				case ACTION_OK :
					$joinery->setMessage('Zalogowanie prawidłowe');
					$joinery->hideMessageAfterTime(3000);
					header('Location:index.php?action=showMain');
					return;
				case NO_LOGIN_REQUIRED :
					$joinery->setMessage('Najpierw proszę się wylogować.');
					$joinery->hideMessageAfterTime(3000);
					header('Location:index.php?action=showMain');
					return;
				case ACTION_FAILED :
				case FORM_DATA_MISSING :
					$joinery->setMessage('Logowanie nie powiodło się.');
					$joinery->hideMessageAfterTime(3000);
					break;
				default:
					$joinery->setMessage('Błąd serwera. Zalogowanie nie jest obecnie możliwe.');
					$joinery->hideMessageAfterTime(3000);
			}
				header('Location:index.php?action=showLoginForm');
			break;
		case 'addNewOrder':
			switch ($joinery -> addNewOrder()):
				case ACTION_OK:
					$joinery->setMessage('Dodano nowe zlecenie.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case FORM_DATA_MISSING:
					$joinery->setMessage('Proszę wypełnić poprawnie wymagane pola formularza.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case ACTION_FAILED:
					$joinery->setMessage('Obecnie dodanie zlecenia nie jest możliwe.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case DOCUMENT_NUMBER_ALREADY_EXISTS:
					$joinery->setMessage('Dokument o takim numerze już istnieje w bazie.');
					break;
				case NO_PERMISSION:
					$joinery->setMessage('Brak uprawnień do dodania nowego zlecenia.');
					$joinery->hideMessageAfterTime(3000);
					header('Location:index.php?action=showMain');
					return;
				case SERVER_ERROR:
				default:
					$joinery->setMessage('Błąd serwera!');
					$joinery->hideMessageAfterTime(3000);
			endswitch;
			header('Location:index.php?action=showOrderAddingForm');
			break;
		case 'addNewCustomer':
			switch ($joinery -> addNewCustomer()):
				case ACTION_OK:
					$joinery->setMessage('Dodano nowego klienta.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case FORM_DATA_MISSING:
					$joinery->setMessage('Proszę wypełnić poprawnie wymagane pola formularza.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case ACTION_FAILED:
					$joinery->setMessage('Obecnie dodanie klienta nie jest możliwe.');
					$joinery->hideMessageAfterTime(3000);
					break;
				case CUSTOMER_NUMBER_ALREADY_EXISTS:
					$joinery->setMessage('Klient o takim numerze telefonu już istnieje w bazie.');
					break;
				case NO_PERMISSION:
					$joinery->setMessage('Brak uprawnień do dodania nowego klienta.');
					$joinery->hideMessageAfterTime(3000);
					header('Location:index.php?action=showMain');
					return;
				case SERVER_ERROR:
				default:
					$joinery->setMessage('Błąd serwera!');
					$joinery->hideMessageAfterTime(3000);
			endswitch;
			header('Location:index.php?action=showCustomerAddingForm');
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
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'logout':
			$joinery->logout();
			header('Location:index.php?action=showMain');
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