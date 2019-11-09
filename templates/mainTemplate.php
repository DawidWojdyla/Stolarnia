<?php if(!isset($joinery)) die();?>
<!DOCTYPE html>
<html>
		<head>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" href="css/style.css">
			<title></title>
		</head>
	<body>
		<div id="topDiv" class="scaffoldingDiv">
			<div id="headerMainDiv">
				<a href="index.php">Główna</a> |
			</div>
			<div id="headerUserInfoDiv">
				<?php if($joinery->stand): ?>
					<div>Twoje stanowisko: <?=$joinery->stand->name?></div>
					<div><a href="index.php?action=logout">Wyloguj się</a></div>
				<?php else: ?>
					<div>Nie jesteś zalogowany.</div>
					<div><a href="index.php?action=showLoginForm">Zaloguj się</a></div>
				<?php endif ?>
			</div>
		</div>
		<div id="centerDiv" class="scaffoldingDiv">
			<div id="mainContentDiv">
				<div id="mainContentDiv">
					<?php if($message): ?>
						<div class="message"><?=$message;?></div>
					<?php endif; ?>
					<?php
					switch($action):
						case 'showLoginForm' :
							include 'loginForm.php';
							break;
						case 'showOrderAddingForm' :
							switch($joinery->showOrderAddingForm()):
								case NO_PERMISSION:
									$joinery->setMessage('Brak uprawnień.');
									header('Location:index.php?action=showMain');
									return;
								case SERVER_ERROR:
									$joinery->setMessage('Błąd serwera!');
									header('Location:index.php?action=showMain');
									return;
								default:
									break;
							endswitch;
							break;
						case 'showMain':
						default:
						include 'templates/innerContentDiv.php';
					endswitch;
					?>
				</div>
			</div>
		</div>
		<div id="footerDiv" class="scaffoldingDiv">
			<p> &copy; 2019 Wszelkie prawa zastrzeżone.</p>
		</div>
	</body>
</html>