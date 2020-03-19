<script>
var workerId = "<?=$id?>";

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function showMessage(message){
	document.getElementById("updatingWorkerDataModalBody").innerHTML = message;
}

function setName(oldName){
	var message = "";
	var newName = document.getElementById('newName').value;
	newName = newName.trim();
	newName = newName.toLowerCase();
	if(newName == oldName.toLowerCase()){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Imię pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingWorkerDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateWorkerName",
			type: "post",
			data: {
				'name' : newName,
				'id' : workerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('name').innerHTML = newName;
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
				closeModal('updatingWorkerDataModal');}, 1000);
		});
	}
}

function updateName(){
	var name = document.getElementById('name').innerHTML;
	
	var updatingWorkerDataModalBody = "<h4>Imię:</h4><input type='text' style='margin-top: 20px;' id='newName' class='form-control text-center text-capitalize' name='newName' value='"+name+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setName('"+name+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingWorkerDataModalBody').innerHTML = updatingWorkerDataModalBody;
		
	$('#updatingWorkerDataModal').modal('show');
}

function setSurname(oldSurname){
	var message = "";
	var newSurname = document.getElementById('newSurname').value;
	newSurname = newSurname.trim();
	if (newSurname == ""){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Podane nazwisko nie jest poprawne";
		showMessage(message);
		setTimeout(function(){closeModal('updatingWorkerDataModal');}, 1000);
	}
	else if(newSurname == oldSurname){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Nazwisko pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingWorkerDataModal');}, 1000);
	}else{
		newSurname = newSurname.toLowerCase();
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateWorkerSurname",
			type: "post",
			data: {
				'surname' : newSurname,
				'id' : workerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('surname').innerHTML = newSurname;
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
				closeModal('updatingWorkerDataModal');}, 1000);
		});
	}
}

function updateSurname(){
	var surname = document.getElementById('surname').innerHTML;
	
	var updatingWorkerDataModalBody = "<h4>Nazwisko:</h4><input type='text' style='margin-top: 20px;' id='newSurname' class='form-control text-center text-capitalize' name='newSurname' value='"+surname+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setSurname('"+surname+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingWorkerDataModalBody').innerHTML = updatingWorkerDataModalBody;
		
	$('#updatingWorkerDataModal').modal('show');
}

function setStands(oldStandsString){
	var message = "";
	var newStands = $('#newStands').val();
	if(!newStands){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Musisz zaznaczyć przynajmniej 1 stanowisko";
		showMessage(message);
		setTimeout(function(){closeModal('updatingWorkerDataModal');}, 1000);
	}else{
		newStandsString = newStands.join();
		if(newStandsString == oldStandsString){
			message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Stanowiska pozostają bez zmian";
			showMessage(message);
			setTimeout(function(){closeModal('updatingWorkerDataModal');}, 1000);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=updateWorkerStands",
				type: "post",
				data: {
					'standsString' : newStandsString,
					'id' : workerId
				}
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
						
						document.getElementById('standsIds').innerHTML = newStandsString;
						var newStandsNames = $('#newStands option:selected').toArray().map(item => item.text).join(", ");
						document.getElementById('stands').innerHTML = newStandsNames;
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
					closeModal('updatingWorkerDataModal');}, 1000);
			});
		}
	}	
}

function updateStands(){
	var stands = document.getElementById('stands').innerHTML;
	var standsIds = document.getElementById('standsIds').innerHTML;
	standsIdsArray = standsIds.split(",");

	var updatingWorkerDataModalBody = "<h4>Stanowiska:</h4><select class='form-control' id='newStands' multiple><?PHP foreach ($standsList as $stand): ?><option value='<?=$stand -> id?>'";
	
	for (var i = 0; i < standsIdsArray.length; ++i) {
		if(standsIdsArray[i] == <?=$stand -> id?>){
			updatingWorkerDataModalBody += " selected";
		}
	}
	
	updatingWorkerDataModalBody +="><?=$stand -> name?></option><?PHP endforeach; ?></select><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setStands('"+standsIds+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingWorkerDataModalBody').innerHTML = updatingWorkerDataModalBody;
		
	$('#updatingWorkerDataModal').modal('show');
}

</script>