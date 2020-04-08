<script>

function showWorkerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	//var stands = document.getElementById('stands'+id).innerHTML;
	//var standsIds = document.getElementById('standsIds'+id).innerHTML;

	
	//var inputs = "<input type='hidden' name='id' value="+id+" /><input type='hidden' name='firstName' value ='"+name+"'/><input type='hidden' name='surname' value ='"+surname+"'/><input type='hidden' name='stands' value ='"+stands+"'/><input type='hidden' name='standsIds' value ='"+standsIds+"'/>";
	
	document.getElementById('modalBody').innerHTML = "<div class='title1'><div>" + name + " " + surname + "</div><div class='btn btn-default btn-block' onclick='restoreWorker("+id+");'><span class=\"glyphicon glyphicon-refresh\"></span> Przywróć pracownika</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

function restoreWorker(id){
	
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno przywrócić wybranego pracownika?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeWorkerFromRemovedWorkersList('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');
}

function removeWorkerFromRemovedWorkersList(workerId){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=restoreWorker",
		type: "post",
		data: {
			'id': workerId
		}
	});
			
	ajaxRequest.done(function (response){
		alert(response);
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrany pracownik został przywrócony";
			document.getElementById(workerId).outerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się przywrócić pracownika";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie przywrócenie pracownika nie jest możliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się przywrócić pracownika";
	 });
	 
	ajaxRequest.always(function(){
		document.getElementById("modalBody").innerHTML = message;
		setTimeout(function(){
			$('#modal').modal('hide');}, 1500);
	});
}

</script>