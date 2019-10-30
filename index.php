<?php
include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{
	$joinery = new Joinery("localhost", "root", "", "Joinery");
	$action = 'showMain';
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	}
	$message = $joinery->getMessage();
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
					header('Location:index.php?action=showMain');
					return;
				case NO_LOGIN_REQUIRED :
					$joinery->setMessage('Najpierw proszę się wylogować.');
					header('Location:index.php?action=showMain');
					return;
				case ACTION_FAILED :
				case FORM_DATA_MISSING :
					$joinery->setMessage('Logowanie na stanowisko nieudane');
					break;
				default:
					$joinery->setMessage('Błąd serwera. Zalogowanie nie jest obecnie możliwe.');
			}
				header('Location:index.php?action=showLoginForm');
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
	//echo 'Błąd: ' . $e->getMessage();
	exit('Portal chwilowo niedostępny');
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