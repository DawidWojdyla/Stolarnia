<script>

function showCustomerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	var phone = document.getElementById('phone'+id).innerHTML;
	
	var modalBody = "<div class='title1'><div>" + name + " " + surname + "</div><div class='title2'>tel. "+phone+"</div></div><div class='btn btn-default btn-block' onclick='restoreCustomer("+id+");'><span class=\"glyphicon glyphicon-refresh\"></span> Przywróć klienta</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;

	$('#modal').modal('show');
}


function restoreCustomer(id){
	
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno przywrócić wybranego klienta?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeCustomerFromRemovedCustomersList('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');
}

function removeCustomerFromRemovedCustomersList(id){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=restoreCustomer",
		type: "post",
		data: {
			'id': id
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrany klient został przywrócony";
			document.getElementById(id).outerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się przywrócić klienta";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie przywrócenie klienta nie jest możliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się przywrócić klienta";
	 });
	 
	ajaxRequest.always(function(){
		document.getElementById("modalBody").innerHTML = message;
		setTimeout(function(){
			$('#modal').modal('hide');}, 1500);
	});
}

</script>