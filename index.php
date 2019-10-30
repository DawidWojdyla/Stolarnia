<?php

include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{
	$joinery = new Joinery("localhost", "root", "", "joinery");
	
	$action = 'showMain';
	if (isset($_GET['action'])){
		$action = $_GET['action'];
	}
  
   $message = $joinery -> getMessage();
	
	if(!$message && $action == 'showLoginForm'){
		$message = 'Wprowad� nazw� i has�o u�ytkownika';
	}
	
	switch($action){
		case 'login' :
			//Obs�uga logowania
			break;
		case 'logout':
			//Obs�uga wylogowania
			break;
		case 'addNewOrder':
			//Dodawanie nowego zlecenia
			break;
		case 'modifyOrder':
			//Modyfikacja zlecenia
			break;
		case 'deleteOrder':
			//Usuwanie zlecenia
			break;
		case 'addNewCustomer':
			//Dodawanie nowego klienta
			break;
		case 'modifyCustomer':
						//Modyfikuj dane klienta
			break;
		case 'fillUpCutting':
			//Uzupe�nij ci�cie
			break;
		case 'fillUpEdging':
			//Uzupe�nij oklejanie
			break;
		case 'markOrderAsReady':
			//
			break;
		default:
			include 'templates/mainTemplate.php';
	}
}	catch(Exception $e){
	//echo 'B��d: ' . $e->getMessage();
		exit('Portal chwilowo niedost�pny');
	}


function classLoader($className){
  if(file_exists("classes/$className.php")){
    require_once("classes/$className.php");
  }else{
	  throw new Exception("Brak pliku z definicj� klasy.");
	}
}

?>