<script>
	
	function carryOutTheOrder(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		
		document.getElementById('askingModal').innerHTML = "<h4>"+doc+"</h4><h5>"+name+"</h5>";
		
		document.getElementById('askingModal').innerHTML += "</br><h4>Rozpocząć realizację zlecenia?</h4><form action=index.php?action=showOrderCuttingForm method='post'><input type='hidden' name='orderId' value="+id+"></br><button class='btn btn-default btn-block' type='submit' >Tak</button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div></form>";
		
		$('#askIfToCheckoutTheOrder').modal('show');
	
	}
</script>