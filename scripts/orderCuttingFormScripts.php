<script>

function showCuttingModal(boardId){
	if(document.getElementById('s'+boardId).innerHTML != 'pocięta'){ 
		var boardName = document.getElementById('board'+boardId).innerHTML;
		document.getElementById('cutting-modal-body').innerHTML = "<h4>"+boardName+"</h4><form id='boardCuttingForm' method='post'><input type='hidden' name='boardId' value="+boardId+"><table class='table'><tr><td colspan='2'>Wybierz pilarza:<div><select class='inputHeight' id='sawWorkers' name='sawWorkers[]' multiple required><?PHP foreach ($sawWorkers as $sawWorker): ?><option value='<?=$sawWorker->id?>'><?=$sawWorker->name?></option><?PHP endforeach; ?></select></div></td></tr><tr><td colspan='2'><div>Uwagi (opcjonalnie)</div><div><textarea id='newC"+boardId+"' name='boardCuttingComment' rows='3' cols='25' maxlength='250' form='boardCuttingForm'></textarea></div></td></tr><tr><td colspan='2'><div class='btn btn-default btn-block' onclick='sendBoardCuttingForm("+boardId+");'>Zapisz</div><button id='sendingButton' type='submit' style='display:none;'></button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div></td></tr></table></form>";
	}
	else{
		document.getElementById('cutting-modal-body').innerHTML ="<h4>Płyta jest oznaczona jako pocięta. Co chcesz zrobić?</h4><div class='btn btn-default btn-block' onclick='showResetCuttingForm("+boardId+");'>Resetuj cięcie</div><div class='btn btn-default btn-block' onclick='addCutingComment("+boardId+");'>Dodaj/edytuj uwagi</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>"
	}
	$('#cutting-modal').modal('show');
}

function showResetCuttingForm(id){
	document.getElementById('cutting-modal-body').innerHTML = "<h4>Czy napewno chcesz zresetować cięcie wybranej płyty?</h4><form id='resetTheBoardCuttingForm'><input type='hidden' name='boardId' value="+id+"></form><div class='btn btn-default btn-block' onclick='resetTheBoardCutting("+id+");'>Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>";
}

function addCutingComment(id){
	document.getElementById('cutting-modal-body').innerHTML = "<h4>Uwagi:</h4><form id='addingBoardCommentForm'><input type='hidden' name='boardId' value="+id+"><div><textarea id='newC"+id+"' name='boardCuttingComment' rows='3' cols='25' maxlength='250'>"+document.getElementById('c'+id).innerHTML+"</textarea></div></form><div class='btn btn-default btn-block' onclick='sendCuttingComment("+id+");'>Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div>";
}

function showMessage(message){
	document.getElementById("cutting-modal-body").innerHTML = message;
}

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function resetTheBoardCutting(id){
	var values = $('#resetTheBoardCuttingForm').serialize();
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=resetTheBoardCutting",
		type: "post",
		data: values
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "Zresetowano cięcie płyty";
				document.getElementById('s'+id).innerHTML = "niepocięta";
				document.getElementById('c'+id).innerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "Nie udało się zresetować cięcia płyty";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "Obecnie zresetowanie cięcia jest niemożliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "Nie udało się zapisać zmian";
	 });
	 
	ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('cutting-modal');}, 1500);
	});
}

function sendCuttingComment(id){
	var message = "";
	var cuttingComment = document.getElementById('newC'+id).value;
	if(cuttingComment == document.getElementById('c'+id).innerHTML){
		message = "Uwagi pozostają bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('cutting-modal');}, 1500);
	}
	else{
		var values = $('#addingBoardCommentForm').serialize();
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=setTheBoardComment",
			type: "post",
			data: values
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "Zapisano zmiany";
					document.getElementById('c'+id).innerHTML = cuttingComment;
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
				closeModal('cutting-modal');}, 1500);
		});
	}
}

function sendBoardCuttingForm(id){
	if (document.getElementById('boardCuttingForm').elements["sawWorkers[]"].selectedIndex == -1){
		document.getElementById('sendingButton').click();
	}
	else{
		var values = $('#boardCuttingForm').serialize();
		var message = "";
		var cuttingComment = document.getElementById('newC'+id).value;
		
		var ajaxRequest = $.ajax({
			url: "index.php?action=setTheBoardCutting",
			type: "post",
			data: values
		});
			
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "Zmieniono status płyty";
					document.getElementById('s'+id).innerHTML = "pocięta";
					document.getElementById('c'+id).innerHTML = cuttingComment;
					break;
				case 'ACTION_FAILED': 
					message = "Nie udało się zmienić statusu płyty";
					  //alert('tutaj');
					break;
				case 'FORM_DATA_MISSING': 
					message = "Nie udało się zmienić statusu płyty";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień do zmiany statusu płyty";
					break;
				default:
					message = "Obecnie zmiana statusu jest niemożliwa";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('cutting-modal');}, 1500);
		});
	}
}
</script>