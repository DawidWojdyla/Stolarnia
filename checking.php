<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>ITS - stolarnia manager</title>
		</head>
	<body>
	<?PHP  print_r($_POST);?>
	a tutaj tylko positions:</br></br></br>
	<?PHP print_r($_POST['positions']); ?>
	
	</br></br>
	
	<?PHP 
		foreach($_POST['positions'] as $position){
			foreach($position as $key => $value){
				switch($key){
					case 'boardSignsId':
						echo $key.' -> '.$value.'</br>';
						break;
					case 'boardThicknessesId':
							echo $key.' -> '.$value.'</br>';
						break;
					case 'boardSymbolsId':
							echo $key.' -> '.$value.'</br>';
						break;
					case 'boardStructuresId':
						echo $key.' -> '.$value.'</br>';
						break;
					case 'amounts':
						echo $key.' -> '.$value.'</br>';
						break;
					case 'cuttingMetters':
						echo $key.' -> '.$value.'</br>';
						break;
					case 'edgeBandTypesId':
						echo 'tablica: </br>';
						print_r($key);
						echo '</br>';
						foreach($value as $subKey):
							print_r($subKey);
							echo '  </br>';
						endforeach;
						echo 'koniec tablicy</br>';
						break;
					case 'edgeBandingBoardSymbolsId':
						echo 'tablica: </br>';
						print_r($key);
						echo '</br>';
						foreach($value as $subKey):
							print_r($subKey);
							echo '  </br>';
						endforeach;
						echo 'koniec tablicy</br>';
						break;
					case 'edgeBandsStickersId':
						echo 'tablica: </br>';
						print_r($key);
						echo '</br>';
						foreach($value as $subKey):
							print_r($subKey);
							echo '  </br>';
						endforeach;
						echo 'koniec tablicy</br>';
						break;
					case 'edgeBandingMetters':
						echo 'tablica: </br>';
						print_r($key);
						echo '</br>';
						foreach($value as $subKey):
							print_r($subKey);
							echo '  </br>';
						endforeach;
						echo 'koniec tablicy</br>';
						break;
					case 'edgeBandComment':
						echo 'tablica: </br>';
						print_r($key);
						echo '</br>';
						foreach($value as $subKey):
							print_r($subKey);
							echo '  </br>';
						endforeach;
						echo 'koniec tablicy</br>';
						break;
						
				}
				
			}
		}
	?>
	<?PHP echo '</br></br>';
	
	foreach($_POST['positions'] as $position){
		
		echo 'BOARD SIGN ----> ';
		print_r($position['boardSignId']);
		}
	
	
	?>
	</body>
	</html>