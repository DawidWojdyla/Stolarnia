<script>

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function showMessage(message){
	document.getElementById("modalBody").innerHTML = message;
}

function updateBoardsAmount(){
	var boardsAmount = document.getElementById('boardsAmount').innerHTML;
	
	document.getElementById('modalBody').innerHTML = "<h4>Limit Ilości płyt na piłę:</h4><input type='text' style='margin-top: 20px;' id='newBoardsAmount' class='form-control text-center' value='"+boardsAmount+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setBoardsAmount('"+boardsAmount+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";

	$('#modal').modal('show');
}

function setBoardsAmount(oldBoardsAmount){
	var message = "";
	var newBoardsAmount = document.getElementById('newBoardsAmount').value;
	
	if (isNaN(newBoardsAmount) || newBoardsAmount % 1 != 0 || newBoardsAmount < 1 || newBoardsAmount > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1500);
	}
	else if(newBoardsAmount == oldBoardsAmount){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Limit ilości płyt pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateBoardsLimit",
			type: "post",
			data: { 
				'boardsLimit' : newBoardsAmount
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("boardsAmount").innerHTML = parseInt(newBoardsAmount);
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
				closeModal('modal');}, 1000);
		});
	}
}


function updateCuttingMetters(){
	var cuttingMetters = document.getElementById('cuttingMetters').innerHTML;
	
	document.getElementById('modalBody').innerHTML = "<h4>Limit ilości metrów cięcia na piłę:</h4><input type='text' style='margin-top: 20px;' id='newCuttingMetters' class='form-control text-center' value='"+cuttingMetters+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setCuttingMetters('"+cuttingMetters+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";

	$('#modal').modal('show');
}

function setCuttingMetters(oldCuttingMetters){
	var message = "";
	var newCuttingMetters = document.getElementById('newCuttingMetters').value;
	
	if (isNaN(newCuttingMetters) || newCuttingMetters % 1 != 0 || newCuttingMetters < 100 || newCuttingMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1500);
	}
	else if(newCuttingMetters == oldCuttingMetters){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Limit metrów cięcia pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCuttingMettersLimit",
			type: "post",
			data: { 
				'cuttingMetters' : newCuttingMetters
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("cuttingMetters").innerHTML = parseInt(newCuttingMetters);
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
				closeModal('modal');}, 1000);
		});
	}
}


function updateEdgeBandingMetters(){
	var edgeBandingMetters = document.getElementById('edgeBandingMetters').innerHTML;
	
	document.getElementById('modalBody').innerHTML = "<h4>Limit Ilości metrów oklejania:</h4><input type='text' style='margin-top: 20px;' id='newEdgeBandingMetters' class='form-control text-center' value='"+edgeBandingMetters+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setEdgeBandingMetters('"+edgeBandingMetters+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";

	$('#modal').modal('show');
}

function setEdgeBandingMetters(oldEdgeBandingMetters){
	var message = "";
	var newEdgeBandingMetters = document.getElementById('newEdgeBandingMetters').value;
	
	if (isNaN(newEdgeBandingMetters) || newEdgeBandingMetters % 1 != 0 || newEdgeBandingMetters < 100 || newEdgeBandingMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1500);
	}
	else if(newEdgeBandingMetters == oldEdgeBandingMetters){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Limit metrów oklejania pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('modal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateEdgeBandingMettersLimit",
			type: "post",
			data: { 
				'edgeBandingMetters' : newEdgeBandingMetters
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("edgeBandingMetters").innerHTML = parseInt(newEdgeBandingMetters);
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
				closeModal('modal');}, 1000);
		});
	}
}



</script>