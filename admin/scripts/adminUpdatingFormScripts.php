<script>

function showOptions(){
	var login = document.getElementById('login').innerHTML;

	document.getElementById('modalBody').innerHTML = "<div class='title1'><div>" + login + "</div><div class='btn btn-default btn-block' onclick=\"updateLogin('"+login+"');\"><span class=\"glyphicon glyphicon-user\"></span> Zmień login</div><div class='btn btn-default btn-block' onclick=\"updatePassword('"+login+"');\"><span class=\"glyphicon glyphicon-lock\"></span> Zmień hasło</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

function updateLogin(login){
	
	document.getElementById('modalBody').innerHTML  = "<h4>Zmiana loginu:</h4><input type='text' style='margin-top: 20px;' id='newLogin' class='form-control text-center' value='"+login+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setLogin('"+login+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');
}


function setLogin(oldLogin){
	var message = "";
	var newLogin = document.getElementById('newLogin').value;
	
	newLogin = newLogin.trim();
	oldLogin = oldLogin.trim();
	
	if(newLogin == oldLogin){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Login pozostaje bez zmian";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}else if(newLogin.length < 3 || newLogin.length > 15){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Login musi zawierać od 3 do 15 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateAdminLogin",
			type: "post",
			data: {
				'login' : newLogin
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('login').innerHTML = newLogin;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){
				$('#modal').modal('hide');}, 1000);
		});
	}
}


function updatePassword(login){
	
	document.getElementById('modalBody').innerHTML  = "<h3>" + login+ "</h3><label style='margin-top: 20px;'>Podaj dotychczasowe hasło:</label><input type='password' id='oldPassword' class='form-control text-center'/><label style='margin-top: 20px;' >Podaj nowe hasło:</label><input type='password' id='newPassword' class='form-control text-center'/><label style='margin-top: 20px;'>Powtórz hasło:</label><input type='password' id='newPassword2' class='form-control text-center'/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setPassword('"+login+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');

}

function setPassword(login){
	var message = "";
	var newPassword = document.getElementById('newPassword').value;
	var newPassword2 = document.getElementById('newPassword2').value;
	var oldPassword = document.getElementById('oldPassword').value;
	
	if(newPassword != newPassword2){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Hasła nie są identyczne";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}else if(newPassword.length < 3 || newPassword.length > 15 || oldPassword.length < 3 || oldPassword.length > 15 ){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Hasło musi zawierać od 3 do 15 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}else if(newPassword == oldPassword){
			message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Podałeś dotychczasowe hasło identyczne z nowym";
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){$('#modal').modal('hide');}, 2500);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=updateAdminPassword",
				type: "post",
				data: {
					'password' : newPassword,
					'oldPassword' : oldPassword,
					'login' : login
				}
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Hasło zostało zmienione";
						break;
					case 'FORM_DATA_MISSING': 
					case 'ACTION_FAILED': 
						message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
						break;
					case 'PASSWORDS_DO_NOT_MATCH':
						message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podałeś błędne dotychczasowe hasło";
					break;
					case 'NO_PERMISSION': 
						message = "Brak uprawnień";
						break;
					default:
						message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
						break;
				}
			});
			
			ajaxRequest.fail(function (){
			  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
			 });
			 
			ajaxRequest.always(function(){
				document.getElementById('modalBody').innerHTML = message;
				setTimeout(function(){
					$('#modal').modal('hide');}, 1200);
			});
		}
}

</script>