<script>

function showOrderOptions(id){
	var customer = document.getElementById('customer'+id).innerHTML;
	var customerId = document.getElementById('customerId'+id).innerHTML;
	var documentNumber = document.getElementById('document'+id).innerHTML;
	var phone = document.getElementById('number'+id).innerHTML;
	var comment = document.getElementById('comment'+id).innerHTML;
	var sellerId = document.getElementById('sellerId'+id).innerHTML;
	var sellerName = document.getElementById('sellerName'+id).innerHTML;
	var admissionDate = document.getElementById('admissionDate'+id).innerHTML;
	var completionDate = document.getElementById('completionDate'+id).innerHTML;
	var sawNumber = document.getElementById('sawNumber'+id).innerHTML;
	var minCuttingDate = document.getElementById('minCuttingDate'+id).innerHTML;
	
	var inputs = "<input type='hidden' name='orderId' value="+id+" /><input type='hidden' name='sawNumber' value ='"+sawNumber+"'/><input type='hidden' name='completionDate' value ='"+completionDate+"'/><input type='hidden' name='sellerName' value ='"+sellerName+"'/><input type='hidden' name='admissionDate' value ='"+admissionDate+"'/><input type='hidden' name='sellerId' value ='"+sellerId+"'/><input type='hidden' name='customerName' value ='"+customer+"'/><input type='hidden' name='customerId' value='"+customerId+"'/><input type='hidden' name='documentNumber' value ='"+documentNumber+"'/><input type='hidden' name='phone' value='"+phone+"'/><input type='hidden' name='comment' value ='"+comment+"'/>";
	
	var modalBody = "<h4><div>"+customer+"</div><div style='margin-top: 5px;'>tel. "+phone+"</div><div style='margin-top: 5px; margin-bottom :20px;'>"+documentNumber+"</div></h4><form id='showOrderDetailsForm' action='index.php?action=showOrderDetails' method='post'>"+inputs+"<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-zoom-in\"></span> Szczegóły zlecenia</button></form>";
	
	if(minCuttingDate == '0'){
		modalBody += "<form id='orderUpdatingForm' action='index.php?action=showOrderUpdatingForm' method='post'>"+inputs+"<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-pencil\"></span> Edycja zlecenia</button></form><div class='btn btn-default btn-block' onclick='removeTheOrder("+id+");'><span class=\"glyphicon glyphicon-trash\"></span> Usuń zlecenie</div>";
	}
	modalBody += "<div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;

	$('#modal').modal('show');
}

function removeTheOrder(id){
	
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno usunąć wybrane zlecenie?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeOrderFromDatabase('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#modal').modal('show');
}

function removeOrderFromDatabase(orderId){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=removeOrder",
		type: "post",
		data: {
			'orderId': orderId
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrane zlecenie zostało usunięte";
			document.getElementById(orderId).outerHTML = "";
				break;
			case 'FORM_DATA_MISSING': 
			case 'ACTION_FAILED': 
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się usunąć zlecenia";
				break;
			case 'NO_PERMISSION': 
				message = "Brak uprawnień";
				break;
			default:
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie usunięcie zlecenia nie jest możliwe";
				break;
		}
		});
		
	ajaxRequest.fail(function (){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się usunąć zlecenia";
	 });
	 
	ajaxRequest.always(function(){
		document.getElementById("modalBody").innerHTML = message;
		setTimeout(function(){
			$('#modal').modal('hide');}, 1500);
	});
}

</script>