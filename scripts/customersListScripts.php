<script>

function showCustomerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	var phone = document.getElementById('phone'+id).innerHTML;
	var address = document.getElementById('address'+id).innerHTML;

	
	var inputs = "<input type='hidden' name='id' value="+id+" /><input type='hidden' name='firstName' value ='"+name+"'/><input type='hidden' name='surname' value ='"+surname+"'/><input type='hidden' name='phone' value ='"+phone+"'/><input type='hidden' name='address' value ='"+address+"'/>";
	
	var modalBody = "<div class='title1'><div>" + name + " " + surname + "</div><div class='title2'>tel. "+phone+"</div></div><form id='customerUpdatingForm' action='index.php?action=showCustomerUpdatingForm' method='post'>"+inputs+"<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-pencil\"></span> Edycja danych klienta</button></form><div class='btn btn-default btn-block' onclick='removeCustomer("+id+");'><span class=\"glyphicon glyphicon-trash\"></span> Usuń klienta</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;

	$('#modal').modal('show');
}

function removeCustomer(id){
	
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno usunąć wybranego klienta?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeCustomerFromList('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	//$('#modal').modal('show');
}

function removeCustomerFromList(id){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=removeCustomer",
		type: "post",
		data: {
			'id': id
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrany klient został usunięty";
			document.getElementById(id).outerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się usunąć klienta";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie usunięcie klienta nie jest możliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się usunąć klienta";
	 });
	 
	ajaxRequest.always(function(){
		document.getElementById("modalBody").innerHTML = message;
		setTimeout(function(){
			$('#modal').modal('hide');}, 1500);
	});
}

</script>