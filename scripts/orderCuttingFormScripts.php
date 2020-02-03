<script>
var isEdgeBanding = false;
var itemsToDo = 0;

function askIfShowOrderList(){
	document.getElementById('cutting-modal-body').innerHTML = "<div class='btn btn-default btn-block' onclick='window.location.href=\"index.php?action=showOrderList\"'><span class=\"glyphicon glyphicon-list-alt\"></span> Lista zleceń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	$('#cutting-modal').modal('show');
}

function showCuttingModal(boardId){
	if(document.getElementById('s'+boardId).innerHTML != 'pocięta'){ 
		var boardName = document.getElementById('board'+boardId).innerHTML;
		document.getElementById('cutting-modal-body').innerHTML = "<h4>"+boardName+"</h4><form id='boardCuttingForm' method='post'><input type='hidden' name='boardId' value="+boardId+"><table class='table table-condensed edgeBandingModalTable'><tr><td colspan='2'>Wybierz pilarza:<div><select class='inputHeight' id='sawWorkers' name='sawWorkers[]' multiple required><?PHP foreach ($sawWorkers as $sawWorker): ?><option value='<?=$sawWorker->id?>'><?=$sawWorker->name?></option><?PHP endforeach; ?></select></div></td></tr><tr><td colspan='2'><div>Uwagi (opcjonalnie)</div><div><textarea id='newC"+boardId+"' name='boardCuttingComment' rows='3' cols='25' maxlength='250' form='boardCuttingForm'></textarea></div></td></tr><tr><td colspan='2'><div class='btn btn-default btn-block' onclick='sendBoardCuttingForm("+boardId+");'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><button id='sendingButton' type='submit' style='display:none;'></button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div></td></tr></table></form>";
	}
	else{
		document.getElementById('cutting-modal-body').innerHTML ="<div class='btn btn-default btn-block' onclick='showResetCuttingForm("+boardId+");'><span class=\"glyphicon glyphicon-erase\"></span> Resetuj cięcie</div><div class='btn btn-default btn-block' onclick='addCutingComment("+boardId+");'><span class=\"glyphicon glyphicon-edit\"></span> Dodaj/edytuj uwagi</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>"
	}
	$('#cutting-modal').modal('show');
}

function showResetCuttingForm(id){
	document.getElementById('cutting-modal-body').innerHTML = "<h4>Zresetować cięcie wybranej płyty?</h4><form id='resetTheBoardCuttingForm'><input type='hidden' name='boardId' value="+id+"></form><div class='btn btn-default btn-block' onclick='resetTheBoardCutting("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function addCutingComment(id){
	document.getElementById('cutting-modal-body').innerHTML = "<h4>Uwagi:</h4><form id='addingBoardCommentForm'><input type='hidden' name='boardId' value="+id+"><div><textarea id='newC"+id+"' name='boardCuttingComment' rows='3' cols='25' maxlength='250'>"+document.getElementById('c'+id).innerHTML+"</textarea></div></form><div class='btn btn-default btn-block' onclick='sendCuttingComment("+id+");' style='margin-top: 10px;'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class='glyphicon glyphicon-remove'></span> Anuluj</div>";
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
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zresetowano cięcie płyty";
				document.getElementById('s'+id).innerHTML = "niepocięta";
				document.getElementById('c'+id).innerHTML = "";
				itemsToDo++;
				if(document.getElementById('sms').style.display != 'none'){
					document.getElementById('sms').style.display = "none";
				}
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zresetować cięcia płyty";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zresetowanie cięcia jest niemożliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
	 });
	 
	ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('cutting-modal');}, 1000);
	});
}

function sendCuttingComment(id){
	var message = "";
	var cuttingComment = document.getElementById('newC'+id).value;
	if(cuttingComment == document.getElementById('c'+id).innerHTML){
		message = "Uwagi pozostają bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('cutting-modal');}, 1000);
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
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('c'+id).innerHTML = cuttingComment;
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
				closeModal('cutting-modal');}, 1000);
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
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zmieniono status płyty";
					document.getElementById('s'+id).innerHTML = "pocięta";
					document.getElementById('c'+id).innerHTML = cuttingComment;
					itemsToDo--;
					if(itemsToDo < 1){
						if(!isEdgeBanding){
							document.getElementById('sms').style.display = "block";
						}
						else{
							//askIfShowOrderList();
							setTimeout(function(){
								askIfShowOrderList();}, 1500);
						}
					}
					break;
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zmienić statusu płyty";
					break;
				case 'FORM_DATA_MISSING': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zmienić statusu płyty";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień do zmiany statusu płyty";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zmiana statusu jest niemożliwa";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
		showMessage(message);
		setTimeout(function(){
			closeModal('cutting-modal');}, 1000);
		});
	}
}

function sendSMS(){
		location.href = "sms:+48<?=$phone?>?body=ITS%20Rzeszów.%20Państwa%20zamówienie%20zostało%20zrealizowane.%20Zapraszamy%20po%20odbiór%20od%20poniedziałku%20do%20piątku%20w%20godzinach%207-17.%20Pozdrawiamy.";
		askIfShowOrderList();
}
</script>