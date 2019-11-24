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

</script>