<script>

$(document).ready(function(){
    $('[data-toggle="popover"]').popover({sanitize: false, html : true});   
});

var smsContent = "ITS%20Rzeszów.%20Państwa%20zamówienie%20zostało%20zrealizowane.%20Zapraszamy%20po%20odbiór%20od%20poniedziałku%20do%20piątku%20w%20godzinach%207-17.%20Pozdrawiamy."; 
<?PHP if($smsContent): ?>
	smsContent = "<?=$smsContent?>";
<?PHP endif; ?>

function sendSMS(){
	location.href = "sms:+48<?=$phone?>?body=" + smsContent;
	document.getElementById('edgeBandingModalBody').innerHTML = "<div class='btn btn-default btn-block' onclick='window.location.href=\"index.php?action=showOrderList\"'><span class=\"glyphicon glyphicon-list-alt\"></span> Lista zleceń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	$('#edgeBandingModal').modal('show');
}

function showBoardDetails(id){
	if(document.getElementById('table'+id).style.display == 'none'){
		$('#info'+id).fadeIn("fast");
		$('#table'+id).fadeIn("fast");
	}
	else{
		$('#info'+id).fadeOut("fast");
		$('#table'+id).fadeOut("fast");
	}
}

function showMoreInfo(id){
	var info = document.getElementsByClassName(id);
	for (var i = 0; i < info.length; i++) {
		if(info[i].style.display == 'none'){
			info[i].style.display = "table-row";
		}
		else{
			info[i].style.display = 'none';
		}
	}
}

function showAddingEdgeBandingForm(edgeBandingId){
		document.getElementById('edgeBandingModalBody').innerHTML = "<form id='edgeBandingForm' method='post'><input type='hidden' name='edgeBandingId' value="+edgeBandingId+"><table class='table table-condensed edgeBandingModalTable'><tr><td colspan='2' style='border-top: none'><label for='newM"+edgeBandingId+"'>Maszyna [mb]:</label><input class='form-control text-center' type='number' min='0.01' max='10000' step='0.01' lang='en' id='newM"+edgeBandingId+"' name='edgeBandingMachineMetters' required/></td></tr><tr><td colspan='2'><label for='workers'>Pracownicy:</label><select class='form-control' id='workers' name='workers[]' multiple required><?PHP foreach ($workers as $worker): ?><option value='<?=$worker->id?>'><?=$worker->name?></option><?PHP endforeach; ?></select></td></tr><tr><td colspan='2'><label for='newC"+edgeBandingId+"'>Uwagi (opcjonalnie):</label><textarea id='newC"+edgeBandingId+"' class='form-control' name='edgeBandingComment' rows='3' cols='25' maxlength='250' form='edgeBandingForm'>"+document.getElementById('c'+edgeBandingId).innerHTML+"</textarea></div></td></tr><tr><td colspan='2'><div class='btn btn-default btn-block' onclick='sendEdgeBandingForm("+edgeBandingId+");'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><button id='sendingButton' type='submit' style='display:none;'></button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div></td></tr></table></form>";
}

function showEdgeBandingModal(edgeBandingId){
	if(document.getElementById('m'+edgeBandingId).innerHTML == '0.00' ){ 
		document.getElementById('edgeBandingModalBody').innerHTML ="<div class='btn btn-default btn-block' onclick='showAddingEdgeBandingForm("+edgeBandingId+");'><span class=\"glyphicon glyphicon-plus\"></span> Dodaj oklejanie</div><div class='btn btn-default btn-block' onclick='updateEdgeBandingComment("+edgeBandingId+");'><span class=\"glyphicon glyphicon-edit\"></span> Dodaj/edytuj uwagi</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
	
	}
	else{
		document.getElementById('edgeBandingModalBody').innerHTML ="<div class='btn btn-default btn-block' onclick='showResetEdgeBandingForm("+edgeBandingId+");'><span class=\"glyphicon glyphicon-erase\"></span> Resetuj oklejanie</div><div class='btn btn-default btn-block' onclick='updateMachineMetters("+edgeBandingId+");'><span class=\"glyphicon glyphicon-pencil\"></span> Edytuj metry</div><div class='btn btn-default btn-block' onclick='updateEdgeBandingComment("+edgeBandingId+");'><span class=\"glyphicon glyphicon-edit\"></span> Dodaj/edytuj uwagi</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
	}
	$('#edgeBandingModal').modal('show');
}

function showResetEdgeBandingForm(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Czy napewno chcesz zresetować wybrane oklejanie?</h4><form id='resetEdgeBandingForm'><input type='hidden' name='edgeBandingId' value="+id+"></form><div class='btn btn-default btn-block' onclick='resetEdgeBanding("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
}

function updateMachineMetters(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Maszyna [mb]</h4><form id='updatingMachineMettersForm'><input type='hidden' name='edgeBandingId' value="+id+"><input type='number' class='text-center form-control' min='0.01' max='10000' step='0.01' id='newM"+id+"' name='edgeBandingMachineMetters' value='"+document.getElementById('m'+id).innerHTML+"' required/></form></br><div class='btn btn-default btn-block' onclick='sendEdgeBandingMachineMetters("+id+");'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
}

function updateEdgeBandingComment(id){
	document.getElementById('edgeBandingModalBody').innerHTML = "<h4>Uwagi:</h4><form id='updatingEdgeBandingCommentForm'><input type='hidden' name='edgeBandingId' value="+id+"><textarea id='newC"+id+"' class='form-control' name='edgeBandingComment' form='updatingEdgeBandingCommentForm' rows='3' cols='25' maxlength='250'>"+document.getElementById('c'+id).innerHTML+"</textarea></div></form></br><div class='btn btn-default btn-block' onclick='sendEdgeBandingComment("+id+");'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
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
				document.getElementById('m'+id).innerHTML = '0.00';
				document.getElementById('c'+id).innerHTML = '';
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zresetowano oklejanie";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zresetować oklejania";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zresetowanie oklejania jest niemożliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
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
	machineMetters = machineMetters.replace(",", ".");
	if(machineMetters == document.getElementById('m'+id).innerHTML){
		message = "Ilość metrów pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('edgeBandingModal');}, 1000);
	}
	else{
		document.getElementById('newM'+id).value = machineMetters;
		var values = $('#updatingMachineMettersForm').serialize();
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateEdgeBandingMachineMetters",
			type: "post",
			data: values
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('m'+id).innerHTML = parseFloat(machineMetters).toFixed(2);
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
			showMessage(message);
			setTimeout(function(){
				closeModal('edgeBandingModal');}, 1000);
		});
	}
}

function sendEdgeBandingComment(id){
	var message = "";
	var edgeBandingComment = document.getElementById('newC'+id).value;
	edgeBandingComment = edgeBandingComment.trim();
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
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('c'+id).innerHTML = edgeBandingComment;
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
		var message = "";
		var edgeBandingComment = document.getElementById('newC'+id).value;
		edgeBandingComment = edgeBandingComment.trim();
		var machineMetters = document.getElementById('newM'+id).value;
		machineMetters = machineMetters.replace(",", ".");
		document.getElementById('newM'+id).value = machineMetters;
		var values = $('#edgeBandingForm').serialize();
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=setEdgeBanding",
			type: "post",
			data: values
		});
			
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano oklejanie";
					document.getElementById('m'+id).innerHTML = parseFloat(machineMetters).toFixed(2);	
					document.getElementById('c'+id).innerHTML = edgeBandingComment;
					break;
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać oklejania";
					break;
				case 'FORM_DATA_MISSING': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać oklejania";
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
		showMessage(message);
		setTimeout(function(){
			closeModal('edgeBandingModal');}, 1000);
		});
	}
}
</script>