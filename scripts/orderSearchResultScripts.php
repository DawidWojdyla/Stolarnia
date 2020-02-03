<script>

function showOrderOptions(id){
	var customer = document.getElementById('customer'+id).innerHTML;
	var documentNumber = document.getElementById('document'+id).innerHTML;
	var phone = document.getElementById('number'+id).innerHTML;

	document.getElementById('modalBody').innerHTML = "<h4><div>"+customer+"</div><div style='margin-top: 5px;'>tel. "+phone+"</div><div style='margin-top: 5px; margin-bottom :20px;'>"+documentNumber+"</div></h4><form id='showOrderDetailsForm' action='index.php?action=showOrderDetails' method='post'><input type='hidden' name='orderId' value="+id+" /><button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-expand\"></span> Przejdź do zlecenia</button></form><div class='btn btn-default btn-block' onclick='removeTheOrder("+id+");'><span class=\"glyphicon glyphicon-trash\"></span> Usuń zlecenie</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

function removeTheOrder(){
	alert('funkcja usuwania');
}

</script>