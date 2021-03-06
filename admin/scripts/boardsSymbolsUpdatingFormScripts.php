<script>

function showOptions(id){
	var modalBody ="";
	
	if(document.getElementById('edgeBand'+id).innerHTML == ""){
		modalBody += "<div class='btn btn-default btn-block' onclick=\"addToNoEdgeBandSymbols('"+id+"');\"><span class=\"glyphicon glyphicon-remove\"></span> Usuń z oklein</div>";
	}
	else{
		modalBody += "<div class='btn btn-default btn-block' onclick=\"removeFromNoEdgeBandSymbols('"+id+"');\"><span class=\"glyphicon glyphicon-cd\"></span> Dodaj do oklein</div>";
	}
	
	modalBody += "<div class='btn btn-default btn-block' onclick=\"manageTypes('"+id+"');\"><span class=\"glyphicon glyphicon-link\"></span> Zarządzaj typami</div>";
	
	if(document.getElementById('hidden'+id).innerHTML == ""){
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfHide("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div>";
	}else{
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfShow("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div>";
	}
	
	modalBody += "<div class='btn btn-default btn-block' onclick=\"askIfRemove('"+id+"');\"><span class=\"glyphicon glyphicon-trash\"></span> Usuń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"></span> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
	$('#modal').modal('show');
}

function manageTypes(id) {
	var modalBody ="";
	var signs = document.getElementById('signs'+id).innerHTML;
	var signsIds = signs.split(',');
	var signsLength = signsIds.length;

	modalBody += "<select class='form-control' style='height: 100%' size='<?=count($boardSigns)?>' id='signsSelect' multiple='multiple'>";

	<?php foreach($boardSigns as $sign): ?>

	modalBody += "<option value='<?=$sign->id?>'";
	
	for(i = 0; i < signsLength; i++) {
		if (signsIds[i] == '<?=$sign->id?>') {
			modalBody += " selected";
		}
	}
	modalBody += "><?=$sign->sign?></option>";
		
	<?php endforeach;?>

	modalBody += "</select>";
	modalBody += "<div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"updateSigns('"+id+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";

	document.getElementById('modalBody').innerHTML = modalBody;
	$('#modal').modal('show');

}

function updateSigns(id) {
	var selectedSigns = $('#signsSelect').val();
	var signs = document.getElementById('signs'+id).innerHTML;
	if(selectedSigns == signs) {
		document.getElementById('modalBody').innerHTML = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
		setTimeout(function(){
			$('#modal').modal('hide');
		}, 1200);
	} else {
		saveSigns(id, selectedSigns);
	}
}

function saveSigns(id, selectedSigns) {
	var ajaxRequest = $.ajax({
		url: "index.php?action=updateSymbolSigns",
		type: "post",
		data: { 
				'symbolId' : id,
				'selectedSigns': selectedSigns
			}
	});
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany"; 			
				document.getElementById('signs'+id).innerHTML = selectedSigns.toString();
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
			$('#modal').modal('hide');
		}, 1200);
	});
}

function addToNoEdgeBandSymbols(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=addToNoEdgeBandSymbols",
			type: "post",
			data: { 'symbolId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('edgeBand'+id).innerHTML = "<span class='glyphicon glyphicon-remove'></span>";
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

function removeFromNoEdgeBandSymbols(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeSymbolFromNoEdgeBand",
			type: "post",
			data: { 'symbolId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('edgeBand'+id).innerHTML = "";
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

function removeFromHidden(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeSymbolFromHidden",
			type: "post",
			data: { 'symbolId' : id }
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz włączyć widoczność wybranego symbolu?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeFromHidden("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function hideSymbol(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=hideSymbol",
			type: "post",
			data: { 'symbolId' : id }
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz ukryć wybrany symbol?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='hideSymbol("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function removeSymbol(id){

	var ajaxRequest = $.ajax({
			url: "index.php?action=removeSymbol",
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
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie można usunąć symboli, które są w użyciu";
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybrany symbol?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeSymbol("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function checkIfSymbolExists(symbol){
	var symbols = document.getElementsByClassName("symbols");
	for (var i = 0; i < symbols.length; i++) {
		if(symbols[i].innerHTML == symbol){
			return true;
		}
	}
	return false;
}

function setNewSymbol(){
	var message = "";
	var newSymbol= document.getElementById('newSymbol').value;
	newSymbol = newSymbol.trim();
	if (newSymbol == "" || newSymbol.length < 1 || newSymbol.length > 7){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Symbol musi zawierać od 1 do 7 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		newSymbol = newSymbol.toUpperCase();
		if(checkIfSymbolExists(newSymbol)){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podany symbol już istnieje";
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){$('#modal').modal('hide');}, 1500);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=addNewSymbol",
				type: "post",
				data: {
					'newSymbol' : newSymbol
				}
			});
			ajaxRequest.done(function (response){
				if(response != 'ACTION_FAILED'){
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('lastRow').insertAdjacentHTML("beforebegin", "<tr id='"+response+"' class='pointer' onclick=\"showOptions('"+response+"');\"><td><label><span class='symbols' id='symbol"+response+"'>"+newSymbol+"</span></label></td><td id='edgeBand"+response+"'></td><td id='hidden"+response+"'></td><td style='display:none;' id='signs"+response+"'></td></tr>");
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

function addNewSymbol(){
	document.getElementById('modalBody').innerHTML = "<h4>Podaj symbol:</h4><input style='margin-top: 20px;' class='form-control text-center text-uppercase' type='text' id='newSymbol' maxlength='7' required/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewSymbol();\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	$('#modal').modal('show');
}


</script>