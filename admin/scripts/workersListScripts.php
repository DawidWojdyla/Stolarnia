<script>

function showWorkerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	var stands = document.getElementById('stands'+id).innerHTML;
	var standsIds = document.getElementById('standsIds'+id).innerHTML;

	
	var inputs = "<input type='hidden' name='id' value="+id+" /><input type='hidden' name='firstName' value ='"+name+"'/><input type='hidden' name='surname' value ='"+surname+"'/><input type='hidden' name='stands' value ='"+stands+"'/><input type='hidden' name='standsIds' value ='"+standsIds+"'/>";
	
	document.getElementById('modalBody').innerHTML = "<div class='title1'><div>" + name + " " + surname + "</div><form id='workerUpdatingForm' action='index.php?action=showWorkerUpdatingForm' method='post'>" + inputs + "<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-pencil\"></span> Edycja danych pracownika</button></form><div class='btn btn-default btn-block' onclick='removeWorker("+id+");'><span class=\"glyphicon glyphicon-ban-circle\"></span> Dodaj do usuniętych</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

function removeWorker(id){
	
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno dodać wybranego pracownika do listy usuniętych?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"addWorkerToRemovedWorkersList('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');
}

function addWorkerToRemovedWorkersList(workerId){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=removeWorker",
		type: "post",
		data: {
			'id': workerId
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrany pracownik został dodany do usuniętych";
			document.getElementById(workerId).outerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się dodać pracownika do usuniętych";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie usunięcie pracownika nie jest możliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się usunąć pracownika";
	 });
	 
	ajaxRequest.always(function(){
		document.getElementById("modalBody").innerHTML = message;
		setTimeout(function(){
			$('#modal').modal('hide');}, 1500);
	});
}

</script>