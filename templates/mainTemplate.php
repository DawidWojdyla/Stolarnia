<?php if(!isset($portal)) die();?>
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
				<a href="index.php?action=showRegistrationForm">Rejestracja</a>
			</div>
			<div id="headerUserInfoDiv">
				<?php if($portal->loggedUser): ?>
					<div>Jesteś zalogowany jako: <?=$portal->loggedUser->name?></div>
					<div><a href="index.php?action=logout">Wylogowanie</a></div>
				<?php else: ?>
					<div>Nie jesteś zalogowany.</div>
					<div><a href="index.php?action=showLoginForm">Logowanie</a></div>
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
						case 'showRegistrationForm' :
							$portal->showRegistrationForm();
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
			<p>Stopka strony</p>
		</div>
	</body>
</html>