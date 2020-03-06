<script>
var customerId = "<?=$id?>";

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function showMessage(message){
	document.getElementById("updatingCustomerDataModalBody").innerHTML = message;
}

function setName(oldName){
	var message = "";
	var newName = document.getElementById('newName').value;
	newName = newName.trim();
	newName = newName.toLowerCase();
	if(newName == oldName.toLowerCase()){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Imię pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCustomerName",
			type: "post",
			data: {
				'customerName' : newName,
				'customerId' : customerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('name').innerHTML = newName;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingCustomerDataModal');}, 1000);
		});
	}
}

function updateName(){
	var name = document.getElementById('name').innerHTML;
	
	var updatingCustomerDataModalBody = "<h4>Imię:</h4><input type='text' style='margin-top: 20px;' id='newName' class='form-control text-center text-capitalize' name='newName' value='"+name+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setName('"+name+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingCustomerDataModalBody').innerHTML = updatingCustomerDataModalBody;
		
	$('#updatingCustomerDataModal').modal('show');
}

function setSurname(oldSurname){
	var message = "";
	var newSurname = document.getElementById('newSurname').value;
	newSurname = newSurname.trim();
	if (newSurname == ""){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Podane nazwisko (nazwa firmy) nie jest poprawne";
		showMessage(message);
		setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1500);
	}
	else if(newSurname == oldSurname){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Nazwisko (nazwa firmy) pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCustomerSurname",
			type: "post",
			data: {
				'customerSurname' : newSurname,
				'customerId' : customerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('surname').innerHTML = newSurname;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingCustomerDataModal');}, 1000);
		});
	}
}

function updateSurname(){
	var surname = document.getElementById('surname').innerHTML;
	
	var updatingCustomerDataModalBody = "<h4>Nazwisko (nazwa firmy):</h4><input type='text' style='margin-top: 20px;' id='newSurname' class='form-control text-center text-capitalize' name='newSurname' value='"+surname+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setSurname('"+surname+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingCustomerDataModalBody').innerHTML = updatingCustomerDataModalBody;
		
	$('#updatingCustomerDataModal').modal('show');
}


function setPhone(oldPhone){
	var message = "";
	var newPhone = document.getElementById('newPhone').value;
	if(newPhone == oldPhone){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Numer telefonu pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1000);
	}
	else if(isNaN(newPhone) || newPhone.length != 9){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Numer telefonu musi zawierać 9 cyfr";
			showMessage(message);
			setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCustomerPhone",
			type: "post",
			data: {
				'phone' : newPhone,
				'customerId' : customerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('phone').innerHTML = newPhone;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingCustomerDataModal');}, 1000);
		});
	}
}

function updatePhone(){
	var phone = document.getElementById('phone').innerHTML;
	
	var updatingCustomerDataModalBody = "<h4>Numer telefonu:</h4><input type='tel' pattern='[1-9]{1}[0-9]{8}' minlength='9' maxlength='9' style='margin-top: 20px;' id='newPhone' class='form-control text-center' name='newPhone' value='"+phone+"' /><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setPhone('"+phone+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingCustomerDataModalBody').innerHTML = updatingCustomerDataModalBody;
		
	$('#updatingCustomerDataModal').modal('show');
}


function setAddress(oldAddress){
	var message = "";
	var newAddress = document.getElementById('newAddress').value;
	newAddress = newAddress.trim();
	if(newAddress == oldAddress){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Adres pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingCustomerDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCustomerAddress",
			type: "post",
			data: {
				'address' : newAddress,
				'customerId' : customerId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					if(newAddress == ""){
						newAddress = "Brak";
					}
					document.getElementById('address').innerHTML = newAddress;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Obecnie zapisanie zmian jest niemożliwe";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingCustomerDataModal');}, 1000);
		});
	}
}

function updateAddress(){
	var address = document.getElementById('address').innerHTML;
	if(address == "Brak"){
		address = "";
	}
	var updatingCustomerDataModalBody = "<h4>Adres:</h4><textarea style='margin-top: 20px;' id='newAddress' class='form-control text-center' name='newAddress'>"+address+"</textarea><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setAddress('"+address+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingCustomerDataModalBody').innerHTML = updatingCustomerDataModalBody;
		
	$('#updatingCustomerDataModal').modal('show');
}



</script>