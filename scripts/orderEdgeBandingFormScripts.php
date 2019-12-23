<script>

function showBoardDetails(id){
	if(document.getElementById('table'+id).style.display == 'none'){
		$('#table'+id).fadeIn("fast");
	//document.getElementById('table'+id).style.display = "table";
	}
	else{
		//document.getElementById('table'+id).style.display = "none";
		$('#table'+id).fadeOut("fast");
	}
}

function showEdgeBandingModal(edgeBandingId){
	if(document.getElementById('m'+edgeBandingId).innerHTML == '0'){ 
	
		document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Dodaj oklejanie</h4><form id='edgeBandingForm' method='post'><input type='hidden' name='edgeBandingId' value="+edgeBandingId+"><table class='table table-condensed edgeBandingModalTable'><tr><td colspan='2'>Maszyna -> metry:<div><input class='text-right' type='number' min='0.1' max='10000' step='0.01' id='newM"+edgeBandingId+"' name='edgeBandingMachineMetters' required/></div></td></tr><tr><td colspan='2'>Pracownicy:<div><select class='inputHeight' id='workers' name='workers[]' multiple required><?PHP foreach ($workers as $worker): ?><option value='<?=$worker->id?>'><?=$worker->name?></option><?PHP endforeach; ?></select></div></td></tr><tr><td colspan='2'><div>Uwagi (opcjonalnie)</div><div><textarea id='newC"+edgeBandingId+"' name='edgeBandingComment' rows='3' cols='25' maxlength='250' form='edgeBandingForm'></textarea></div></td></tr><tr><td colspan='2'><div class='btn btn-default btn-block' onclick='sendEdgeBandingForm("+edgeBandingId+");'>Zapisz</div><button id='sendingButton' type='submit' style='display:none;'></button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div></td></tr></table></form>";
	}
	else{
		document.getElementById('edgeBandingModalBody').innerHTML ="<div class='btn btn-default btn-block' onclick='showResetEdgeBandingForm("+edgeBandingId+");'>Resetuj oklejanie</div><div class='btn btn-default btn-block' onclick='updateMachineMetters("+edgeBandingId+");'>Edytuj metry</div><div class='btn btn-default btn-block' onclick='updateEdgeBandingComment("+edgeBandingId+");'>Dodaj/edytuj uwagi</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>"
	}
	$('#edgeBandingModal').modal('show');
}

function showResetEdgeBandingForm(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Czy napewno chcesz zresetować wybrane oklejanie?</h4><form id='resetEdgeBandingForm'><input type='hidden' name='edgeBandingId' value="+id+"></form><div class='btn btn-default btn-block' onclick='resetEdgeBanding("+id+");'>Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>";
}

function updateMachineMetters(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Maszyna -> metry oklejania:</h4><form id='updatingMachineMettersForm'><input type='hidden' name='edgeBandingId' value="+id+"><div><input type='number' class='text-right' min='0.1' max='10000' step='0.01' id='newM"+id+"' name='edgeBandingMachineMetters' value='"+document.getElementById('m'+id).innerHTML+"'/></div></form></br><div class='btn btn-default btn-block' onclick='sendEdgeBandingMachineMetters("+id+");'>Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>";
}

function updateEdgeBandingComment(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Uwagi:</h4><form id='updatingEdgeBandingCommentForm'><input type='hidden' name='edgeBandingId' value="+id+"><div><textarea id='newC"+id+"' name='edgeBandingComment' form='updatingEdgeBandingCommentForm' rows='3' cols='25' maxlength='250'>"+document.getElementById('c'+id).innerHTML+"</textarea></div></form></br><div class='btn btn-default btn-block' onclick='sendEdgeBandingComment("+id+");'>Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>";
}

function showMessage(message){
	document.getElementById("edgeBandingModalBody").innerHTML = message;
}

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function resetEdgeBanding(id){
	var values = $('#resetEdgeBandingForm').serialize();
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=resetEdgeBanding",
		type: "post",
		data: values
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				document.getElementById('m'+id).innerHTML = '0';
				message = "Zresetowano oklejanie";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "Nie udało się zresetować oklejania";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "Obecnie zresetowanie oklejania jest niemożliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "Nie udało się zapisać zmian";
	 });
	 
	ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('edgeBandingModal');}, 1000);
	});
}

function sendEdgeBandingMachineMetters(id){
	var message = "";
	var machineMetters = document.getElementById('newM'+id).value;
	if(machineMetters == document.getElementById('m'+id).innerHTML){
		message = "Ilość metrów pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('edgeBandingModal');}, 1000);
	}
	else{
		var values = $('#updatingMachineMettersForm').serialize();
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateEdgeBandingMachineMetters",
			type: "post",
			data: values
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "Zapisano zmiany";
					document.getElementById('m'+id).innerHTML = parseFloat(machineMetters).toFixed(2);
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('edgeBandingModal');}, 1000);
		});
	}
}

function sendEdgeBandingComment(id){
	var message = "";
	var edgeBandingComment = document.getElementById('newC'+id).value;
	if(edgeBandingComment == document.getElementById('c'+id).innerHTML){
		message = "Uwagi pozostają bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('edgeBandingModal');}, 1000);
	}
	else{
		var values = $('#updatingEdgeBandingCommentForm').serialize();
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateEdgeBandingComment",
			type: "post",
			data: values
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "Zapisano zmiany";
					document.getElementById('c'+id).innerHTML = edgeBandingComment;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('edgeBandingModal');}, 1000);
		});
	}
}

function sendEdgeBandingForm(id){
	if (document.getElementById('edgeBandingForm').elements["workers[]"].selectedIndex == -1 || document.getElementById('newM'+id).value == ''){
		document.getElementById('sendingButton').click();
	}
	else{
		var values = $('#edgeBandingForm').serialize();
		var message = "";
		var edgeBandingComment = document.getElementById('newC'+id).value;
		var machineMetters = document.getElementById('newM'+id).value;
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=setEdgeBanding",
			type: "post",
			data: values
		});
			
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "Zapisano oklejanie";
					document.getElementById('m'+id).innerHTML = machineMetters;
					document.getElementById('c'+id).innerHTML = edgeBandingComment;
					break;
				case 'ACTION_FAILED': 
					message = "Nie udało się zapisać oklejania";
					break;
				case 'FORM_DATA_MISSING': 
					message = "Nie udało się zapisać oklejania";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('edgeBandingModal');}, 1000);
		});
	}
}
</script>