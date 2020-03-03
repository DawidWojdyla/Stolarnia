<script>

function sendDatesOfOrderList(){
	var dateFrom =  new Date(document.getElementById('dateFrom').value);
	var dateTo = new Date(document.getElementById('dateTo').value);
	
	if(dateFrom > dateTo){
		alert( "Należy podać zakres dat w kolejności od wcześniejszej do późniejszej");
	}
	else{
		var diffTime = Math.abs(dateTo - dateFrom);
		var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
		
		if(diffDays > 30){
			//document.getElementById('dateError').innerHTML = "Zakres dat nie może być większy od 30 dni";
			alert( "Zakres dat nie może być większy od 30 dni");
		}
		else{
			document.getElementById('orderListDatesForm').submit();
		}
	}
}

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

	document.getElementById('modalBody').innerHTML = "<h4><div>"+customer+"</div><div style='margin-top: 5px;'>tel. "+phone+"</div><div style='margin-top: 5px; margin-bottom :20px;'>"+documentNumber+"</div></h4><form id='showOrderDetailsForm' action='index.php?action=showOrderDetails' method='post'><input type='hidden' name='orderId' value="+id+" /><input type='hidden' name='sawNumber' value ='"+sawNumber+"'/><input type='hidden' name='completionDate' value ='"+completionDate+"'/><input type='hidden' name='sellerName' value ='"+sellerName+"'/><input type='hidden' name='admissionDate' value ='"+admissionDate+"'/><input type='hidden' name='sellerId' value ='"+sellerId+"'/><input type='hidden' name='customerName' value ='"+customer+"'/><input type='hidden' name='customerId' value='"+customerId+"'/><input type='hidden' name='documentNumber' value ='"+documentNumber+"'/><input type='hidden' name='phone' value='"+phone+"'/><input type='hidden' name='comment' value ='"+comment+"'/><button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-zoom-in\"></span> Szczegóły zlecenia</button></form><div class='btn btn-default btn-block' onclick='removeTheOrder("+id+");'><span class=\"glyphicon glyphicon-trash\"></span> Usuń zlecenie</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

</script>