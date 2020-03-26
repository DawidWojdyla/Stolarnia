<?php if(!isset($joineryAdmin)) die();?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ITS - stolarnia manager (ADMINISTRACJA)</title>
		
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
				 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>                        
					</button>
				</div>
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
					
				<?PHP 
						if($joineryAdmin -> loggedAdmin){
							include 'templates/menu.php';
						}
				?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if($joineryAdmin -> loggedAdmin): ?>
						<li><a><span class="glyphicon glyphicon-map-marker"></span> Twoje stanowisko: Administrator</a></li>
						<li><a href="index.php?action=logout"><span class="glyphicon glyphicon-log-out"></span> Wyloguj się</a></li>
						<?php else: ?>
						<li>Nie jesteś zalogowany.</li>
						<li><a href="index.php?action=showLoginForm"><span class="glyphicon glyphicon-log-in"></span> Zaloguj się</a></li>
					<?php endif; ?>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container">
				<div id="mainContentDiv">
					<?php if($message): ?>
					<div id="mess" class="message">
						<?=$message?><?php endif; ?>
					</div>
					<?php if($delay): ?>
					<script> setTimeout(function(){ $('#mess').fadeOut();}, <?=$delay?>); </script>
					<?php endif; ?>
					
						<?php
						switch($action):
							case 'showLoginForm' :
								include 'loginForm.php';
								break;
							case 'showWorkerAddingForm':
								switch($joineryAdmin -> showWorkerAddingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showWorkerUpdatingForm':
								switch($joineryAdmin -> showWorkerUpdatingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showStandsUpdatingForm':
								switch($joineryAdmin -> showStandsUpdatingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showWorkersList':
								switch($joineryAdmin -> showWorkersList()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showRemovedWorkersList':
								switch($joineryAdmin -> showRemovedWorkersList()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showWorkerSearchingForm':
								switch($joineryAdmin -> showWorkerSearchingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'findWorker':
								switch($joineryAdmin -> showWorkerSearchingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								switch($joineryAdmin -> showWorkerSearchResult()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showLimitsUpdatingForm':
								switch($joineryAdmin -> showLimitsUpdatingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showAdminUpdatingForm':
								switch($joineryAdmin -> showAdminUpdatingForm()):
									case NO_PERMISSION:
										$joineryAdmin -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joineryAdmin -> setMessage('Błąd serwera!');
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
			<div id="footerDiv">
				<!-- <p> &copy; 2019 Wszelkie prawa zastrzeżone.</p> -->
			</div>
		</div>
	</body>
</html>