<script>
	
	function carryOutTheOrder(id){
		
		var name = document.getElementById('name'+id).innerHTML;
		var doc = document.getElementById('document'+id).innerHTML;
		var phone = document.getElementById('phone'+id).innerHTML;
		var comment = document.getElementById('comment'+id).innerHTML;
		
		document.getElementById('askingModal').innerHTML = "<h3><div style='margin-top: 0px; margin-bottom: 5px;'>"+doc+"</div></h3><h4 style='margin-bottom: 20px;'><div>"+name+"</div>tel. "+phone+"</h4>";
		
		document.getElementById('askingModal').innerHTML += "<form action=index.php?action=showOrderEdgeBandingForm method='post'><input type='hidden' name='orderId' value="+id+"><input type='hidden' name='customerName' value ='"+name+"'/><input type='hidden' name='documentNumber' value ='"+doc+"'/><input type='hidden' name='phone' value='"+phone+"'/><input type='hidden' name='comment' value ='"+comment+"'/><button class='btn btn-default btn-block' type='submit' ><span class=\"glyphicon glyphicon-expand\"></span> Realizuj</button><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div></form>";
		
		$('#askIfToCheckoutTheOrder').modal('show');
	
	}
	
	function showOrders(date){
		$('#'+date).toggle();
		/*var ordersClass = document.getElementsByClassName(date);
		for (var i = 0; i < ordersClass.length; i++) {
			if(ordersClass[i].style.display == 'none'){
				ordersClass[i].style.display = "table-row";
			}
			else{
				ordersClass[i].style.display = 'none';
			}
		}*/
	}
</script>