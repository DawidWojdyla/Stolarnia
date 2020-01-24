<script>
	
	function carryOutTheOrder(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		var phone = document.getElementById('phone'+id).innerHTML;
		
		document.getElementById('askingModal').innerHTML = "<h3><div>"+name+"</div><div style='margin-top: 5px; margin-bottom :20px;'>"+doc+"</div></h3>";
		
		document.getElementById('askingModal').innerHTML += "<form action=index.php?action=showOrderCuttingForm method='post'><input type='hidden' name='orderId' value="+id+"><input type='hidden' name='orderName' value ='"+name+" ("+doc+")'/><input type='hidden' name='phone' value='"+phone+"'/><button class='btn btn-default btn-block' type='submit' ><span class=\"glyphicon glyphicon-check\"></span> Realizuj</button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div></form>";
		
		$('#askIfToCheckoutTheOrder').modal('show');
	
	}
</script>