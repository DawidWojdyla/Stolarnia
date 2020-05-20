<script>

function showOptions(id){
	
	var modalBody = "<div class='btn btn-default btn-block' onclick='showNameUpdatingForm("+id+");'><span class=\"glyphicon glyphicon-edit\"></span> Nazwa</div><div class='btn btn-default btn-block' onclick='showDefaultThicknessUpdatingForm("+id+");'><span class=\"glyphicon glyphicon-resize-vertical\"></span> Standardowa grubość</div>";
	
	if(document.getElementById('symbol'+id).innerHTML == "<span class=\"glyphicon glyphicon-ban-circle\"></span>"){
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfUnblockAddingSymbols("+id+");'><span class=\"glyphicon glyphicon-ok-circle\"></span> Odblokuj symbole</div>";
	}else{
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfBlockAddingSymbols("+id+");'><span class=\"glyphicon glyphicon-ban-circle\"></span> Zablokuj symbole</div>";
	}
	modalBody += "<div class='btn btn-default btn-block' onclick='showPriorityUpdatingForm("+id+");'><span class=\"glyphicon glyphicon-sort-by-order\"></span> Priorytet</div>";
	
	if(document.getElementById('hidden'+id).innerHTML == ""){
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfHide("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div>";
	}else{
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfShow("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div>";
	}
	
	modalBody += "<div class='btn btn-default btn-block' onclick=\"askIfRemove('"+id+"');\"><span class=\"glyphicon glyphicon-trash\"></span> Usuń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"></span> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
	$('#modal').modal('show');
}


function removeFromHidden(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeSignFromHidden",
			type: "post",
			data: { 'signId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('hidden'+id).innerHTML = "";
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
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfShow(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz włączyć widoczność wybranego rodzaju?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeFromHidden("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function hideSign(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=hideSign",
			type: "post",
			data: { 'signId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('hidden'+id).innerHTML = "<span class=\"glyphicon glyphicon-eye-close\"></span>";
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
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfHide(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz ukryć wybrany rodzaj?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='hideSign("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}


function unblockAddingSymbols(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=unblockAddingSymbols",
			type: "post",
			data: { 'signId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('symbol'+id).innerHTML = "<span class=\"glyphicon glyphicon-ok\"></span>";
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
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfUnblockAddingSymbols(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz odblokować możliwość dodawania symboli?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='unblockAddingSymbols("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Odblokuj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function blockAddingSymbols(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=blockAddingSymbols",
			type: "post",
			data: { 'signId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('symbol'+id).innerHTML = "<span class=\"glyphicon glyphicon-ban-circle\"></span>";
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
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfBlockAddingSymbols(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz zablokować możliwość dodawania symboli?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='blockAddingSymbols("+id+");'><span class=\"glyphicon glyphicon-ban-circle\"></span> Zablokuj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}


function setDefaultThickness(id){
	var message = "";
	var oldThicknessId = document.getElementById('thicknessId'+id).innerHTML;
	var newThicknessId = document.getElementById('thicknessSelect').value;
	if(oldThicknessId == newThicknessId || (oldThicknessId == "" && newThicknessId == '8')){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Standardowa grubość pozostaje bez zmian";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1200);
	}else if(newThicknessId == '8' || newThicknessId == ""){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeDefaultThickness",
			type: "post",
			data: {
				'signId' : id
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('thickness'+id).innerHTML = "-";
					document.getElementById('thicknessId'+id).innerHTML = "";
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
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=setDefaultThickness",
			type: "post",
			data: {
				'thicknessId' : newThicknessId,
				'signId' : id
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('thickness'+id).innerHTML = $("#thicknessSelect option:selected").text();
					document.getElementById('thicknessId'+id).innerHTML = newThicknessId;
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

function showDefaultThicknessUpdatingForm(id){
	var thicknessId = document.getElementById('thicknessId'+id).innerHTML;
	
	var modalBody ="<h4>Ustaw standardową grubość</h4><div style='margin-top: 20px;'><select style='text-align-last: center;' id='thicknessSelect' class='form-control'><option value=''";
	if (thicknessId == ''){
		modalBody += "selected";
	}
	modalBody += ">Brak (18mm)</option><?PHP foreach ($boardsThickness as $thickness):?><option value='<?=$thickness -> id?>'";
	if(thicknessId == '<?=$thickness -> id?>'){
		modalBody += "selected";
	}
	modalBody += "><?=$thickness -> thickness?></option><?PHP endforeach; ?></select><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setDefaultThickness('"+id+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	document.getElementById('modalBody').innerHTML = modalBody;
}

function updateSignName(id){
	var message = "";
	var newSignName= document.getElementById('newSignName').value;
	var oldSignName= document.getElementById('sign'+id).innerHTML;
	newSignName = newSignName.trim();
	newSignName = newSignName.toUpperCase();
	if(newSignName == oldSignName.trim()){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Rodzaj pozostaje bez zmian";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1200);
	}else if(checkIfSignExists(newSignName)){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Rodzaj o takiej nazwie już istnieje";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1200);
	}else if(newSignName == "" || newSignName.length > 5){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Rodzaj musi zawierać od 1 do 5 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateSignName",
			type: "post",
			data: {
				'sign' : newSignName,
				'id' : id
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('sign'+id).innerHTML = newSignName;
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

function showNameUpdatingForm(id){
	var name = document.getElementById('sign' + id).innerHTML;

	var modalBody = "<h4>Nazwa</h4><input type='text' style='margin-top: 20px;' id='newSignName' class='form-control text-center text-uppercase' maxlength='5' minlength='1' value='"+name+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"updateSignName('"+id+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
}

function removeSign(id){

	var ajaxRequest = $.ajax({
			url: "index.php?action=removeSign",
			type: "post",
			data: { 'id' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById(id).outerHTML = "";
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie można usunąć typów, które są w użyciu";
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

function askIfRemove(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybrany rodzaj?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeSign("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function checkIfSignExists(sign){
	var signs = document.getElementsByClassName("signs");
	for (var i = 0; i < signs.length; i++) {
		if(signs[i].innerHTML == sign){
			return true;
		}
	}
	return false;
}

function setNewSign(){
	var message = "";
	var newSign= document.getElementById('newSign').value;
	newSign = newSign.trim();
	if(newSign == "" || newSign.length > 5){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Rodzaj musi zawierać od 1 do 5 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		newSign = newSign.toUpperCase();
		if(checkIfSignExists(newSign)){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podany rodzaj już istnieje";
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){$('#modal').modal('hide');}, 1500);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=addNewSign",
				type: "post",
				data: {
					'newSign' : newSign,
				}
			});
			ajaxRequest.done(function (response){
				if(response != 'ACTION_FAILED'){
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('lastRow').insertAdjacentHTML("beforebegin", "<tr id='"+response+"' class='pointer' onclick=\"showOptions('"+response+"');\"><td><label><span class='signs' id='sign"+response+"'>"+newSign+"</span></label></td><td><span id='thickness"+response+"'>-</span><span style='display: none;' id='thicknessId"+response+"'></span></td><td id='symbol"+response+"'><span class='glyphicon glyphicon-ok'></span></td><td><span id='priority"+response+"'>-</span></td></tr>");
				}else{
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
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
}

function addNewSign(){
	document.getElementById('modalBody').innerHTML = "<h4>Podaj nazwę:</h4><input type='text' style='margin-top: 20px;' id='newSign' class='form-control text-center text-uppercase' maxlength='5' minlength='1' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewSign();\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	$('#modal').modal('show');
}

function updatePriority(id){
	var message = "";
	var newPriority = document.getElementById('prioritySelect').value;
	var oldPriority = document.getElementById('priority' + id).innerHTML;
	if(newPriority == oldPriority){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Priorytet pozostaje bez zmian";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1200);
	}else{
		if(newPriority == '-'){
			newPriority = '0';
		}
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateSignPriority",
			type: "post",
			data: {
				'priority' : newPriority,
				'signId' : id
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					if(newPriority == '0'){
						newPriority = '-';
					}
					document.getElementById('priority' + id).innerHTML = newPriority;
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

function showPriorityUpdatingForm(id){
	var priority = document.getElementById('priority' + id).innerHTML;

	var modalBody = "<h4>Priorytet</h4><select id='prioritySelect' class='form-control' style='margin-top: 20px; text-align-last: center;'><option value='-'";
	if(priority == "-"){
		modalBody += " selected";
	}
	modalBody += ">Brak</option>";
	for(var i = 1; i < 6; i++){
		modalBody += "<option value='"+i+"'";
		if(priority == i){
			modalBody += " selected";
		}
		modalBody += ">"+i+"</option>";
	}
	
	modalBody += "</select><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"updatePriority('"+id+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
}



</script>