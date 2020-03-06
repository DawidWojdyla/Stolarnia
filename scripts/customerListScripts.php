<script>

function showCustomerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	var phone = document.getElementById('phone'+id).innerHTML;
	var address = document.getElementById('address'+id).innerHTML;

	
	var inputs = "<input type='hidden' name='id' value="+id+" /><input type='hidden' name='firstName' value ='"+name+"'/><input type='hidden' name='surname' value ='"+surname+"'/><input type='hidden' name='phone' value ='"+phone+"'/><input type='hidden' name='address' value ='"+address+"'/>";
	
	var modalBody = "<div class='title1'><div>" + name + " " + surname + "</div><div class='title2'>tel. "+phone+"</div></div><form id='customerUpdatingForm' action='index.php?action=showCustomerUpdatingForm' method='post'>"+inputs+"<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-pencil\"></span> Edycja danych klienta</button></form><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;

	$('#modal').modal('show');
}

</script>