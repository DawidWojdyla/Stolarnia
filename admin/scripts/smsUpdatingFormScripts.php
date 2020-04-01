<script>
var activeSMSId;


function showSMSOptions(id){

	document.getElementById('modalBody').innerHTML = "<div class='btn btn-default btn-block' onclick='setActive("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Ustaw jako aktywny</div><div class='btn btn-default btn-block' onclick='updateSMSContent("+id+");'><span class=\"glyphicon glyphicon-edit\"></span> Edytuj treść</div><div class='btn btn-default btn-block' onclick='askIfRemove("+id+");'><span class=\"glyphicon glyphicon-remove\"></span> Usuń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

function removeSMS(id){
	if(activeSMSId == id){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie można usunąć aktywnego szablonu SMS.";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		var ajaxRequest = $.ajax({
				url: "index.php?action=removeSMSContent",
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

function askIfRemove(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybrany szablon SMS?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeSMS("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"> Anuluj</div>";
	
	$('#modal').modal('show');
}

function setActive(id){
	if(activeSMSId == id){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrany SMS jest już aktywny";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
				url: "index.php?action=updateActiveSMS",
				type: "post",
				data: { 'id' : id }
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
						document.getElementById('activeMark' + activeSMSId).innerHTML = "";
						document.getElementById('activeMark' + id).innerHTML = "<span class='glyphicon glyphicon-ok'></span>";
						activeSMSId = id;
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

function setContent(id){
	var message = "";
	var newContent= document.getElementById('newSMSContent').value;
	var oldContent = document.getElementById('smsContent' + id).innerHTML;
	newContent = newContent.trim();
	if(newContent == oldContent){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Treść SMSa pozostaje bez zmian";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateSMSContent",
			type: "post",
			data: {
				'sms' : newContent,
				'id' : id
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('smsContent' + id).innerHTML = newContent;
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

function updateSMSContent(id){
	var content = document.getElementById('smsContent' + id).innerHTML;

	var modalBody = "<h4>Treść wiadomości:</h4><textarea style='margin-top: 20px;' id='newSMSContent' class='form-control text-center' rows='3' maxlength='250'>"+content+"</textarea><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setContent('"+id+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
		
	$('#modal').modal('show');
}

function setNewSMSContent(){
	var message = "";
	var newContent= document.getElementById('newSMSContent').value;
	newContent = newContent.trim();
	if(newContent == ""){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Treść wiadomości SMS nie może być pusta";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=addNewSMSContent",
			type: "post",
			data: {
				'sms' : newContent,
			}
		});
		ajaxRequest.done(function (response){
			if(response != 'ACTION_FAILED'){
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				document.getElementById('lastRow').insertAdjacentHTML("beforebegin", "<tr id='"+response+"' class='pointer' onclick=\"showSMSOptions('"+response+"');\"><td><span id='smsContent"+response+"'>"+newContent+"</span></td><td id='activeMark"+response+"'></td></tr>");
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

function addNewSMS(){
	document.getElementById('modalBody').innerHTML = "<h4>Treść nowej wiadomości:</h4><textarea style='margin-top: 20px;' id='newSMSContent' class='form-control text-center' rows='3' maxlength='250'></textarea><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewSMSContent();\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	$('#modal').modal('show');
}

</script>