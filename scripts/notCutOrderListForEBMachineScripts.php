<script>
	
	function showOrderDetails(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		var phone = document.getElementById('phone'+id).innerHTML;
		var comment = document.getElementById('comment'+id).innerHTML;
		var sawNumber = document.getElementById('sawNumber'+id).innerHTML;
		var seller = document.getElementById('seller'+id).innerHTML;
		var date = document.getElementById('date'+id).innerHTML;
		var term = document.getElementById('completionDate'+id).innerHTML;
		var boards = document.getElementById('boards'+id).innerHTML;
		
		var modalBody = "<h4>Szczegóły zlecenia</h4><table class='table table-condensed text-left'><tr><td>Dokument:</td><td>"+doc+"</td></tr><tr><tr><td>Klient:</td><td>"+name+"</td></tr><tr><td>Telefon:</td><td>"+phone+"</td></tr><tr><td>Data:</td><td>"+date+"</td></tr><tr><tr><td>Termin:</td><td>"+term+"</td></tr><tr><tr><tr><td>Piła:</td><td>"+sawNumber+"</td></tr><tr><td>Sprzedawca:</td><td>"+seller+"</td></tr><tr><td>Pozycje:</td><td>"+boards+"</td></tr>";
		if(comment.trim() != ""){
			modalBody += "<tr><td>Uwagi:</td><td>"+comment+"</td></tr>"
		}
		modalBody += "</table><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"> Powrót</div>";
		
		document.getElementById('modalBody').innerHTML = modalBody;
		
		$('#modal').modal('show');
	
	}
</script>