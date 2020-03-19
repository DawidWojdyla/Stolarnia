<script>

function showWorkerOptions(id){
	var name = document.getElementById('name'+id).innerHTML;
	var surname = document.getElementById('surname'+id).innerHTML;
	var stands = document.getElementById('stands'+id).innerHTML;
	var standsIds = document.getElementById('standsIds'+id).innerHTML;

	
	var inputs = "<input type='hidden' name='id' value="+id+" /><input type='hidden' name='firstName' value ='"+name+"'/><input type='hidden' name='surname' value ='"+surname+"'/><input type='hidden' name='stands' value ='"+stands+"'/><input type='hidden' name='standsIds' value ='"+standsIds+"'/>";
	
	document.getElementById('modalBody').innerHTML = "<div class='title1'><div>" + name + " " + surname + "</div><form id='workerUpdatingForm' action='index.php?action=showWorkerUpdatingForm' method='post'>" + inputs + "<button type='submit' class='btn btn-default btn-block'><span class=\"glyphicon glyphicon-pencil\"></span> Edycja danych pracownika</button></form><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";

	$('#modal').modal('show');
}

</script>