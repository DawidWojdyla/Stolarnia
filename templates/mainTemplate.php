<?php if(!isset($joinery)) die();?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ITS - stolarnia manager</title>
		
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
		
		<link rel="stylesheet" type="text/css" href="css/style.css">
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
						if($joinery -> stand):
							switch($joinery -> stand -> id): 
								case 1:
								case 2: include 'templates/sawMenu.php';
									break;
								case 3: include 'templates/edgeBandingMenu.php';
									break;
								case 4: include 'templates/shopMenu.php';
									break;
							endswitch; ?>
				<?PHP 
						endif; ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<?php if($joinery -> stand): ?>
						<li><a href="index.php?action=showMain"><span class="glyphicon glyphicon-map-marker"></span> Twoje stanowisko: <?=$joinery -> stand -> name?></a></li>
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
								switch($joinery -> showLoginForm()):
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showShopMain' :
								switch($joinery -> showShopMain()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderAddingForm' :
								switch($joinery -> showOrderAddingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showLastMadeOrders' :
								switch($joinery -> showLastMadeOrders()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showCustomerAddingForm' :
								switch($joinery -> showCustomerAddingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderCuttingForm' :
								switch($joinery -> showOrderCuttingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderEdgeBandingForm' :
								switch($joinery -> showOrderEdgeBandingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderList' :
								switch($joinery -> showOrderList()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showNotCutOrderList' :
								switch($joinery -> showNotCutOrderList()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderSearchingForm' :
								switch($joinery -> showOrderSearchingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showCustomerSearchingForm' :
								switch($joinery -> showCustomerSearchingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderDetails' :
								switch($joinery -> showOrderDetails()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showOrderUpdatingForm' :
								switch($joinery -> showOrderUpdatingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'findTheOrder':
								switch($joinery -> showOrderSearchingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								switch($joinery -> showSearchResult()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'findTheCustomer':
								switch($joinery -> showCustomerSearchingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								switch($joinery -> showCustomerSearchResult()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showCustomersList':
								switch($joinery -> showCustomersList()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showRemovedCustomersList':
								switch($joinery -> showRemovedCustomersList()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showCustomerUpdatingForm' :
								switch($joinery -> showCustomerUpdatingForm()):
									case NO_PERMISSION:
										$joinery -> setMessage('Brak uprawnień.');
										header('Location:index.php?action=showMain');
										return;
									case SERVER_ERROR:
										$joinery -> setMessage('Błąd serwera!');
										header('Location:index.php?action=showMain');
										return;
									default:
										break;
								endswitch;
								break;
							case 'showMain':
								if(isset($joinery -> stand -> id)){
									switch($joinery -> stand -> id):
										case 1:
										case 2:
										case 3: 
											header('Location:index.php?action=showOrderList');
											return;
										case 4:
											header('Location:index.php?action=showShopMain');
											return;
										default:
										 'templates/innerContentDiv.php';
									endswitch;
								}
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