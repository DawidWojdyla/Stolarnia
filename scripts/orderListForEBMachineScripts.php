<script>
	
	function carryOutTheOrder(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		var phone = document.getElementById('phone'+id).innerHTML;
		var sawNumber = document.getElementById('sawNumber'+id).innerHTML;
		
		document.getElementById('askingModal').innerHTML = "<h4>"+doc+"</h4><h5>"+name+"</h5>";
		
		document.getElementById('askingModal').innerHTML += "</br><h4>Przejść do realizacji zlecenia?</h4><form action=index.php?action=showOrderEdgeBandingForm method='post'><input type='hidden' name='orderId' value="+id+"><input type='hidden' name='customerName' value ='"+name+"'/><input type='hidden' name='documentNumber' value ='"+doc+"'/><input type='hidden' name='phone' value='"+phone+"'/></br><button class='btn btn-default btn-block' type='submit' >Tak</button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'>Anuluj</div></form>";
		
		$('#askIfToCheckoutTheOrder').modal('show');
	
	}
</script>