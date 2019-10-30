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
		$message = 'WprowadŸ nazwê i has³o u¿ytkownika';
	}
	
	switch($action){
		case 'login' :
			//Obs³uga logowania
			break;
		case 'logout':
			//Obs³uga wylogowania
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
			//Uzupe³nij ciêcie
			break;
		case 'fillUpEdging':
			//Uzupe³nij oklejanie
			break;
		case 'markOrderAsReady':
			//
			break;
		default:
			include 'templates/mainTemplate.php';
	}
}	catch(Exception $e){
	//echo 'B³¹d: ' . $e->getMessage();
		exit('Portal chwilowo niedostêpny');
	}


function classLoader($className){
  if(file_exists("classes/$className.php")){
    require_once("classes/$className.php");
  }else{
	  throw new Exception("Brak pliku z definicj¹ klasy.");
	}
}

?>