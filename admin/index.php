<?php
include '../constants.php';
spl_autoload_register('classLoader');

session_start();

try{
	$joineryAdmin = new JoineryAdmin("localhost", "root", "", "joinery8");
	$action = 'showLoginForm';
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	
	$message = $joineryAdmin -> getMessage();
	$delay = $joineryAdmin -> getHidingMessageDelay();
	
	if(!$message && $action == 'showLoginForm'){
		$message = 'wprowadź login oraz hasło';
	}
	if($action == 'showLoginForm' && $joineryAdmin -> loggedAdmin){
		header('Location:index.php?action=showMain');
		return;
	}
	
	switch($action){
		case 'login' :
			switch($joineryAdmin->login()){
				case ACTION_OK :
					$joineryAdmin -> setMessage('Zalogowanie prawidłowe');
					$joineryAdmin -> setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case NO_LOGIN_REQUIRED :
					$joineryAdmin -> setMessage('Najpierw proszę się wylogować.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case ACTION_FAILED :
				case FORM_DATA_MISSING :
					$joineryAdmin -> setMessage('Logowanie nie powiodło się.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					break;
				default:
					$joineryAdmin -> setMessage('Błąd serwera. Zalogowanie nie jest obecnie możliwe.');
					$joineryAdmin -> setHidingMessageDelay(3000);
			}
			header('Location:index.php?action=showLoginForm');
			break;
		case 'logout':
			$joineryAdmin->logout();
			header('Location:index.php?action=showLoginForm');
			break;
		case 'addNewWorker':
			switch ($joineryAdmin -> addNewWorker()):
				case ACTION_OK:
					$joineryAdmin -> setMessage('Dodano nowego pracownika.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					break;
				case FORM_DATA_MISSING:
					$joineryAdmin -> setMessage('Proszę wypełnić poprawnie wymagane pola formularza.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					break;
				case ACTION_FAILED:
					$joineryAdmin -> setMessage('Obecnie dodanie pracownika nie jest możliwe.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					break;
				case WORKER_NAME_ALREADY_EXISTS:
					$joineryAdmin -> setMessage('Pracownik o takim imieniu i nazwisku już istnieje w bazie.');
					break;
				case NO_PERMISSION:
					$joineryAdmin -> setMessage('Brak uprawnień do dodania nowego pracownika.');
					$joineryAdmin -> setHidingMessageDelay(3000);
					header('Location:index.php?action=showMain');
					return;
				case SERVER_ERROR:
				default:
					$joineryAdmin -> setMessage('Błąd serwera!');
					$joineryAdmin -> setHidingMessageDelay(3000);
			endswitch;
			header('Location:index.php?action=showWorkerAddingForm');
			break;
		case 'updateWorkerName':
			switch ($joineryAdmin -> updateWorkerName()):
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
		case 'updateWorkerSurname':
			switch ($joineryAdmin -> updateWorkerSurname()):
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
		case 'updateWorkerStands':
			switch ($joineryAdmin -> updateWorkerStands()):
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
		case 'removeWorker':
			switch ($joineryAdmin -> removeWorker()):
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
		case 'restoreWorker':
			switch ($joineryAdmin -> restoreWorker()):
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
		case 'updateStandName':
			switch ($joineryAdmin -> updateStandName()):
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
		case 'updateStandPassword':
			switch ($joineryAdmin -> updateStandPassword()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case PASSWORDS_DO_NOT_MATCH:
					print_r('PASSWORDS_DO_NOT_MATCH');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateBoardsLimit':
			switch ($joineryAdmin -> updateBoardsLimit()):
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
		case 'updateCuttingMettersLimit':
			switch ($joineryAdmin -> updateCuttingMettersLimit()):
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
		case 'updateEdgeBandingMettersLimit':
			switch ($joineryAdmin -> updateEdgeBandingMettersLimit()):
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
		case 'updateAdminLogin':
			switch ($joineryAdmin -> updateAdminLogin()):
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
		case 'updateAdminPassword':
			switch ($joineryAdmin -> updateAdminPassword()):
				case ACTION_OK:
					print_r('ACTION_OK');
					break;
				case ACTION_FAILED:
					print_r('ACTION_FAILED');
					break;
				case FORM_DATA_MISSING:
					print_r('FORM_DATA_MISSING');
					break;
				case PASSWORDS_DO_NOT_MATCH:
					print_r('PASSWORDS_DO_NOT_MATCH');
					break;
				case NO_PERMISSION:
					print_r('NO_PERMISSION');
				case SERVER_ERROR:
				default:
					print_r('SERVER_ERROR');
			endswitch;
			break;
		case 'updateSMSContent':	
			switch ($joineryAdmin -> updateSMSContent()):
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
		case 'updateActiveSMS':	
			switch ($joineryAdmin -> updateActiveSMS()):
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
		case 'removeSMSContent':	
			switch ($joineryAdmin -> removeSMSContent()):
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
		case 'addNewSMSContent':
			 print_r($joineryAdmin -> addNewSMSContent());
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
	if(file_exists("../classes/$name.php")){
		require_once("../classes/$name.php");
	}
	else{
		throw new Exception("Brak pliku z definicją klasy.");
	}
}
?>