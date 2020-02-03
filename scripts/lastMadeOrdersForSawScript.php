<script>
	
	function carryOutTheOrder(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		var phone = document.getElementById('phone'+id).innerHTML;
		var comment = document.getElementById('comment'+id).innerHTML;
		
		document.getElementById('askingModal').innerHTML = "<h3><div>"+name+"</div><div margin-bottom :20px;'>"+doc+"</div></h3><div style='font-size: 16px; margin-top: -10px;'>tel. "+phone+" </div>";
		
		document.getElementById('askingModal').innerHTML += "<form action=index.php?action=showOrderCuttingForm method='post'><input type='hidden' name='orderId' value="+id+"><input type='hidden' name='orderName' value ='"+name+" "+doc+"'/><input type='hidden' name='phone' value='"+phone+"'/><input type='hidden' name='comment' value ='"+comment+"'/><button class='btn btn-default btn-block' type='submit' ><span class=\"glyphicon glyphicon-expand\"></span> Realizuj</button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div></form>";
		
		$('#askIfToCheckoutTheOrder').modal('show');
	
	}
</script>