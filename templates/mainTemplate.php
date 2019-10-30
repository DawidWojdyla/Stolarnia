<?php if(!isset($joinery)) die();?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Stolarnia ITS</title>
	</head>
	<body>
		<div id="topDiv" class="scafoldingDiv">
			<div id="headerMainDiv">
				<a href="index.php">Główna</a> |
				<?php 
				
					//Tutaj będzie kod wczytujący odpowiednie pozycje menu w zależności od dostępu
					
				?>
			</div>
			<div id="headerUserInfoDiv">
				<?php if($joinery->loggedUser): ?>
				<div>Jesteś zalogowany jako: <?=$joinery->loggedUser->name?></div>
				<div><a href="index.php?action=logout">Wylogowanie</a></div>
				<?php else: ?>
				<div>Nie jesteś zalogowany.</div>
				<div><a href="index.php?action=showLoginForm">Logowanie</a></div>
				<?php endif ?>
			</div>
		</div>
		<div id="centerDiv" class="scafoldingDiv">
			<div id="mainContentDiv">
				<?php if($message): ?>
				<div class="komunikat"><?=$message;?></div>
				<?php endif; ?>
				<?php
					switch($action):
						case 'showLoginForm' :
						//Wyświetlenie formularza logowania
							break;
						case 'showAddingNewOrderForm' :
						//Wyświetlenie formularza dodawania zlecenia
							break;
						case 'showOrderList':
						//Pokaż Listę Zleceń
							break;
						case 'showSearchForm' :
							//Wyświetlenie formularza wyszukiwania
							break;
						case 'showCustomerList':
						//Pokaż Listę Zleceń
							break;
						case 'showCuttingForm':
						//Pokaż Formularz Cięcia
							break;
						case 'showEdgeBandingForm':
							//Pokaż Formularz klejenia
							break;
						case 'showMain':
						default:
						include 'templates/innerContentDiv.php';
					endswitch;
				?>
			</div>
		</div>
		<div id="footerDiv" class="scafoldingDiv">
			<p>ITS 2019. Wszystkie prawa zastrzeżone.</p>
		</div>
	</body>
</html>