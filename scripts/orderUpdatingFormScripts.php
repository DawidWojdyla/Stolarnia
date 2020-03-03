<script>
var orderId = "<?=$orderId?>";

function closeModal(modalId){
	$('#'+modalId).modal('hide');
}

function checkIfCustomerTempDataDisabled(){
		if(document.getElementById('customerIdSelect').value != '1'){
			document.getElementById('customerTempName').disabled = true;
			document.getElementById('customerTempPhone').disabled = true;
			
		}else{
			document.getElementById('customerTempName').disabled = false;
			document.getElementById('customerTempPhone').disabled = false;
		}
}

function updateCustomerData(){
	var customerId =  document.getElementById('customerId').innerHTML;
	var updatingOrderDataModalBody = "<h3>Dane klienta</h3><div style='margin-top: 20px;'><label class='control-label' for='customerIdSelect'>Wybierz klienta:</label><select style='text-align-last: center;' onchange='checkIfCustomerTempDataDisabled();' id='customerIdSelect' class='form-control' name='customerId' required><option value='1' ";
	if (customerId == '1'){
		updatingOrderDataModalBody += "selected";
	}
	
	updatingOrderDataModalBody += ">Inny (dane poniżej)</option><?PHP foreach ($customerList as $customer):?><option  value='<?=$customer->id?>'";
	if(customerId == '<?=$customer->id?>'){
		updatingOrderDataModalBody += "selected";
	}
	updatingOrderDataModalBody += "><?=$customer->surname?> <?=$customer->name?> (<?=$customer->phone?>)</option><?PHP endforeach; ?></select></div>";
	
	updatingOrderDataModalBody += "<div style='margin-top: 10px;'><label class='control-label' for='customerTempName'>Nazwa klienta:</label><input type='text' id='customerTempName' class='form-control text-center' name='customerTempName' value='";
	if (customerId == '1'){
		updatingOrderDataModalBody += document.getElementById('customerName').innerHTML + "'"; 
	}else{
		updatingOrderDataModalBody +="' disabled";
	}
	
	updatingOrderDataModalBody += " /></div><div style='margin-top: 10px; margin-bottom: 30px;'><label class='control-label' for='customerTempPhone'>Telefon: </label><input class='form-control text-center' type='tel' id='customerTempPhone' name='customerPhone' pattern='[1-9]{1}[0-9]{8}' minlength='9' maxlength='9' value='";
	
	if (customerId == '1'){
		updatingOrderDataModalBody += document.getElementById('phone').innerHTML+ "'"; 
	}else{
		updatingOrderDataModalBody +="' disabled";
	}
	updatingOrderDataModalBody += " /></div><div class='btn btn-default btn-block' onclick='updateCustomer("+customerId+");'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
	
}

function updateCustomer(lastCustomerId){
	var newCustomerId = document.getElementById('customerIdSelect').value;
	
	if(newCustomerId == '1'){
		if(lastCustomerId == '1'){
			updateCustomerTempData();
		}
		else{
			updateCustomerIdAndAddTempData();
		}
	}
	else{
		if(lastCustomerId == '1'){
			updateCustomerIdAndRemoveTempData();
		}
		else{
			updateCustomerId();
		}
	}	
}

function updateCustomerTempData(){
	var customerTempName = document.getElementById('customerTempName').value;
	var customerTempPhone = document.getElementById('customerTempPhone').value;
	
	var message = "";
	
	if(customerTempName == document.getElementById("customerName").innerHTML && customerTempPhone == document.getElementById("phone").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Dane klienta pozostają bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		if(isNaN(customerTempPhone) || customerTempPhone.length != 9){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Numer musi zawierać 9 cyfr";
			showMessage(message);
			setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
		}else{
			
			var ajaxRequest = $.ajax({
				url: "index.php?action=updateCustomerTempData",
				type: "post",
				data: { 
					'customerName' : customerTempName,
					'customerPhone' : customerTempPhone,
					'orderId' : orderId,
				}
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
						document.getElementById("customerName").innerHTML = customerTempName;
						document.getElementById("phone").innerHTML = customerTempPhone;
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
					closeModal('updatingOrderDataModal');}, 1000);
			});
		}
	}
	
}

function updateCustomerIdAndAddTempData(){
	var customerTempName = document.getElementById('customerTempName').value;
	var customerTempPhone = document.getElementById('customerTempPhone').value;
	
	var message = "";
	
	if(isNaN(customerTempPhone) || customerTempPhone.length != 9){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Numer musi składać się z 9 cyfr";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}else{
	
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCustomerIdAndAddTempData",
			type: "post",
			data: { 
				'customerName' : customerTempName,
				'customerPhone' : customerTempPhone,
				'orderId' : orderId,
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("customerName").innerHTML = customerTempName;
					document.getElementById("phone").innerHTML = customerTempPhone;
					document.getElementById('customerId').innerHTML = '1';
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
					closeModal('updatingOrderDataModal');}, 1000);
			});
	}
}


function updateCustomerIdAndRemoveTempData(){
	var newCustomerData = $("#customerIdSelect option:selected").text();
	var newCustomerId = document.getElementById('customerIdSelect').value;
	
	var splitCustomerData = newCustomerData.split("(", 2);
	var newCustomerName = splitCustomerData[0];
	var newCustomerPhone = splitCustomerData[1];
	newCustomerPhone = newCustomerPhone.slice(0, -1);
	
	var message = "";
	
	var ajaxRequest = $.ajax({
		url: "index.php?action=updateCustomerIdAndRemoveTempData",
		type: "post",
		data: { 
			'orderId' : orderId,
			'customerId' : newCustomerId
		}
	});
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				document.getElementById("customerName").innerHTML = newCustomerName;
				document.getElementById("phone").innerHTML = newCustomerPhone;
				document.getElementById('customerId').innerHTML = newCustomerId;
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
			closeModal('updatingOrderDataModal');}, 1000);
	});
}

function updateCustomerId(){
	var newCustomerData = $("#customerIdSelect option:selected").text();
	var newCustomerId = document.getElementById('customerIdSelect').value;
	
	var splitCustomerData = newCustomerData.split("(", 2);
	var newCustomerName = splitCustomerData[0];
	var newCustomerPhone = splitCustomerData[1];
	newCustomerPhone = newCustomerPhone.slice(0, -1);
	
	var message = "";
	
	var ajaxRequest = $.ajax({
		url: "index.php?action=updateCustomerId",
		type: "post",
		data: { 
			'orderId' : orderId,
			'customerId' : newCustomerId
		}
	});
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				document.getElementById("customerName").innerHTML = newCustomerName;
				document.getElementById("phone").innerHTML = newCustomerPhone;
				document.getElementById('customerId').innerHTML = newCustomerId;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
}

function updateDocumentNumber(){
	
	var documentType = null;
	var documentNumber = null;
	var documentBranch = null;
	var updatingOrderDataModalBody = "";
	var doc= document.getElementById("documentNumber").innerHTML;
	
	if(doc != "Brak"){
		documentType = doc.substr(0, 2);
		documentNumber = doc.substr(2, 6);
		documentBranch = doc.substr(2, 6);
	}		
	
	updatingOrderDataModalBody = "<h3>Numer dokumentu</h3><div style='margin-top: 20px; min-width: 160px;' class='fullWidth'><select id='documentTypeSelect' style='max-width: 80px; display:inline;' class='form-control' name='documentType' required><option value='PA' ";
		if(documentType == 'PA'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">PA</option><option value='FV' ";
		if(documentType == 'FV'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">FV</option><option value='RV' ";
		if(documentType == 'RV'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">RV</option><option value='4' ";
		if(documentType == null){
			updatingOrderDataModalBody +="selected";
		}
		updatingOrderDataModalBody += ">Brak</option></select>  <input id='documentNumberInput' style='max-width: 120px; display : inline; '  class='form-control text-center' type='text' pattern='\d*' maxlength='6' name='documentNumber' required value='"+documentNumber+"'/>  <select style='max-width: 80px; display : inline;' class='form-control' id='documentBranch' name='documentBranch' required><option value='RZ' ";
		if(documentBranch == 'RZ'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">RZ</option><option value='RA' ";
		if(documentBranch == 'RA'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">RA</option><option value='KR' ";
		if(documentBranch == 'KR'){
			updatingOrderDataModalBody +="selected";
		}
		updatingOrderDataModalBody += ">KR</option><option value='KR' ";
		if(documentBranch == 'KR'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">KR</option><option value='NS' ";
		if(documentBranch == 'NS'){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += ">NS</option><option value='5' ";
		if(documentBranch == null){
			updatingOrderDataModalBody += "selected";
		}
		updatingOrderDataModalBody += "></option></select></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='updateDocument();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		

		document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
		$('#updatingOrderDataModal').modal('show');
		
}

function showMessage(message){
	document.getElementById("updatingOrderDataModalBody").innerHTML = message;
}

function updateDocument(){
	var message = "";
	var documentNumber = "";
	var docType = document.getElementById('documentTypeSelect').value;
	var docNum = document.getElementById('documentNumberInput').value;
	var docBranch= document.getElementById('documentBranch').value;
	var year = document.getElementById('admissionDate').innerHTML;
	year = year.substr(0, 4);
	
	if (docType == '4'){
		documentNumber = 'Brak';
	}
	else{
		documentNumber = docType + docNum.padStart(6, "0") + docBranch;
	}
	

	if(documentNumber == document.getElementById("documentNumber").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Numer dokumentu pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateDocumentNumber",
			type: "post",
			data: { 
				'documentNumber' : documentNumber,
				'orderId' : orderId,
				'year' : year 
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("documentNumber").innerHTML = documentNumber;
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'DOCUMENT_NUMBER_ALREADY_EXISTS': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Dokument o podanym numerze już istnieje";
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}

}

function setSawNumber(){
	var message = "";
	var newSawNumber = document.getElementById('sawSelect').value;
	
	if(newSawNumber == document.getElementById("sawNumber").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Numer piły pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateSawNumber",
			type: "post",
			data: { 
				'sawNumber' : newSawNumber,
				'orderId' : orderId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("sawNumber").innerHTML = newSawNumber;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}

function updateSawNumber(){
	var sawNumber = document.getElementById('sawNumber').innerHTML;
	
	updatingOrderDataModalBody = "<h3>Numer piły</h3><select style='margin-top: 20px; text-align-last: center;' class='form-control' id='sawSelect' name='sawNumber' required><option value='1' ";
	if(sawNumber == '1'){
		updatingOrderDataModalBody += "selected ";
	}
	updatingOrderDataModalBody +=">1</option><option value='2' ";
	if(sawNumber == '2'){
		updatingOrderDataModalBody += "selected ";
	}
	updatingOrderDataModalBody +=">2</option></select><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='setSawNumber();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setAdmissionDate(){
	var message = "";
	var newAdmissionDate = document.getElementById('newAdmissionDate').value;
	
	if(newAdmissionDate == document.getElementById("admissionDate").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Data pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		
		var date = new Date(newAdmissionDate);
		var today = new Date();
		if(today < date){
			message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Data nie może być z przyszłości";
			showMessage(message);
			setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
			
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=updateAdmissionDate",
				type: "post",
				data: { 
					'admissionDate' : newAdmissionDate,
					'orderId' : orderId
				}
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
						document.getElementById("admissionDate").innerHTML = newAdmissionDate;
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
					closeModal('updatingOrderDataModal');}, 1000);
			});
		}
	}
}

function updateAdmissionDate(){
	var admissionDate = document.getElementById('admissionDate').innerHTML;
	updatingOrderDataModalBody = "<h3>Data złożenia zlecenia</h3><div style='margin-top: 20px;'><input class='form-control text-center' type='date' id='newAdmissionDate' name='admissionDate' required value='"+admissionDate+"'/></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='setAdmissionDate();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setCompletionDate(){
	var message = "";
	var newCompletionDate = document.getElementById('newCompletionDate').value;
	var admissionDate = document.getElementById('admissionDate').innerHTML;
	
	if(newCompletionDate == document.getElementById("completionDate").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Data pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var completionDate = new Date(newCompletionDate);
		admissionDate = new Date(admissionDate);
		if(completionDate < admissionDate){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Realizacja zlecenia nie może być przed jego złożeniem";
			showMessage(message);
			setTimeout(function(){closeModal('updatingOrderDataModal');}, 2000);
			
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=updateCompletionDate",
				type: "post",
				data: { 
					'completionDate' : newCompletionDate,
					'orderId' : orderId
				}
			});
			ajaxRequest.done(function (response){
				switch(response){
					case 'ACTION_OK': 
						message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
						document.getElementById("completionDate").innerHTML = newCompletionDate;
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
					closeModal('updatingOrderDataModal');}, 1000);
			});
		}
	}
}

function updateCompletionDate(){
	var completionDate = document.getElementById('completionDate').innerHTML;
	updatingOrderDataModalBody = "<h3>Data realizacji zlecenia</h3><div style='margin-top: 20px;'><input class='form-control text-center' type='date' id='newCompletionDate' name='completionDate' required value='"+completionDate+"'/></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='setCompletionDate();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setNewSeller(){
	var message = "";	
	var newSellerName = $("#sellerSelect option:selected").text();
	var newSellerId = document.getElementById('sellerSelect').value;
	
	if(newSellerId == document.getElementById("sellerId").innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Sprzedawca pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateSeller",
			type: "post",
			data: { 
				'sellerId' : newSellerId,
				'orderId' : orderId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("sellerId").innerHTML = newSellerId;
					document.getElementById("sellerName").innerHTML = newSellerName;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}

function updateSeller(){
	var sellerName = document.getElementById('sellerName').innerHTML;
	var sellerId = document.getElementById('sellerId').innerHTML;

	updatingOrderDataModalBody = "<h3>Sprzedawca:</h3><select style='margin-top: 20px; text-align-last: center;' class='form-control' id='sellerSelect'>";
		<?PHP foreach ($sellers as $seller): ?>
	updatingOrderDataModalBody += "<option value='<?=$seller->id?>'";
	if (sellerId == <?=$seller->id?>){
		updatingOrderDataModalBody += " selected";
	}
	updatingOrderDataModalBody += "><?=$seller->name?></option>";
		<?PHP endforeach; ?>
	updatingOrderDataModalBody += "</select><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='setNewSeller();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setComment(oldComment){
	var message = "";
	var newComment = document.getElementById('newComment').value;
	if(newComment == oldComment){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Uwagi pozostają bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateOrderComment",
			type: "post",
			data: {
				'comment' : newComment,
				'orderId' : orderId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					if(newComment == ""){
						newComment = "Brak";
					}
					document.getElementById('comment').innerHTML = newComment;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}

function updateComment(){
	var comment = document.getElementById('comment').innerHTML;
	if(comment == "Brak"){
		comment = "";
	}
	var updatingOrderDataModalBody = "<h3>Uwagi:</h3><textarea style='margin-top: 20px;' id='newComment' class='form-control text-center' name='newComment'>"+comment+"</textarea><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setComment('"+comment+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setAmount(boardId){
	var message = "";
	var newAmount = document.getElementById('amount').value;
	
	newAmount = newAmount.replace(",", ".");

	if (isNaN(newAmount) || newAmount % 0.5 != 0 || newAmount < 0.5 || newAmount > 1000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2000);
	}
	else if(newAmount == document.getElementById("amount"+boardId).innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> ilość pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateBoardAmount",
			type: "post",
			data: { 
				'amount' : newAmount,
				'boardId' : boardId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("amount"+boardId).innerHTML = newAmount;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}


function updateBoardAmount(boardId){
	var amount = document.getElementById('amount'+boardId).innerHTML;
	
	var updatingOrderDataModalBody = "<h3>Ilość sztuk</h3><input style='margin-top: 20px;' type='text' id='amount' name='amount' class='form-control text-center' min='0.5' max='1000' step='0.5' value='"+amount+"'/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setAmount('"+boardId+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setCuttingMetters(boardId){
	var message = "";
	var newCuttingMetters = document.getElementById('cuttingMetters').value;
	
	newCuttingMetters = newCuttingMetters.replace(",", ".");
	
	if (isNaN(newCuttingMetters) || newCuttingMetters % 0.5 != 0 || newCuttingMetters < 0.5 || newCuttingMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2000);
	}
	else if(newCuttingMetters == document.getElementById("cuttingMetters"+boardId).innerHTML){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> ilość pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}
	else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateCuttingMetters",
			type: "post",
			data: { 
				'cuttingMetters' : newCuttingMetters,
				'boardId' : boardId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("cuttingMetters"+boardId).innerHTML = parseFloat(newCuttingMetters).toFixed(1);
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}


function updateCuttingMetters(boardId){
	var cuttingMetters = document.getElementById('cuttingMetters'+boardId).innerHTML;
	
	var updatingOrderDataModalBody = "<h3>Ilość metrów cięcia</h3><input type='text' style='margin-top: 20px;' id='cuttingMetters' name='cuttingMetters' class='form-control text-center' min='0.5' max='10000' step='0.5' value='"+cuttingMetters+"'/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setCuttingMetters('"+boardId+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}


function setEdgeBanding(edgeBandingId){
	var message = "";
	
	var eBTypeId = document.getElementById('eBTypeId'+edgeBandingId).innerHTML;
	var eBSymbolId = document.getElementById('eBSymbolId'+edgeBandingId).innerHTML;
	var stickerSymbolId = document.getElementById('stickerSymbolId'+edgeBandingId).innerHTML;
	var eBWzMetters = document.getElementById('eBWzMetters'+edgeBandingId).innerHTML;
	var eBComment= document.getElementById('eBComment'+edgeBandingId).innerText;
	
	var newEBTypeId = document.getElementById('eBType').value;
	var newEBSymbolId = document.getElementById('eBSymbol').value;
	var newStickerSymbolId = document.getElementById('eBSticker').value;
	var newEBWzMetters = document.getElementById('eBWzMetters').value;
	var newEBComment = document.getElementById('eBComment').value;
	
	newEBWzMetters = newEBWzMetters.replace(",", ".");
	
	if(eBTypeId == newEBTypeId && eBSymbolId == newEBSymbolId && stickerSymbolId == newStickerSymbolId && eBWzMetters == newEBWzMetters && eBComment == newEBComment){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Okleina pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	} else if (isNaN(newEBWzMetters) || newEBWzMetters % 0.5 != 0 || newEBWzMetters < 0.5 || newEBWzMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość metrów oklejania nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateEdgeBanding",
			type: "post",
			data: { 
				'edgeBandTypeId' : newEBTypeId,
				'edgeBandingBoardSymbolId' : newEBSymbolId,
				'edgeBandStickerSymbolId' : newStickerSymbolId,
				'edgeBandingMettersWz' : newEBWzMetters,
				'edgeBandComment' : newEBComment,
				'edgeBandingId' : edgeBandingId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById("eBWzMetters"+edgeBandingId).innerHTML = parseFloat(newEBWzMetters).toFixed(1);
					document.getElementById('eBType'+edgeBandingId).innerHTML = $("#eBType option:selected").text();
					document.getElementById('eBTypeId'+edgeBandingId).innerHTML = newEBTypeId;
					document.getElementById('eBSymbol'+edgeBandingId).innerHTML = $("#eBSymbol option:selected").text();
					document.getElementById('eBSymbolId'+edgeBandingId).innerHTML = newEBSymbolId;
					document.getElementById('stickerSymbol'+edgeBandingId).innerHTML = $("#eBSticker option:selected").text();
					document.getElementById('stickerSymbolId'+edgeBandingId).innerHTML = newStickerSymbolId;
					document.getElementById('eBComment'+edgeBandingId).innerHTML = newEBComment;
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}


function updateEdgeBanding(edgeBandingId){
	var eBTypeId = document.getElementById('eBTypeId'+edgeBandingId).innerHTML;
	var eBSymbolId = document.getElementById('eBSymbolId'+edgeBandingId).innerHTML;
	var stickerSymbolId = document.getElementById('stickerSymbolId'+edgeBandingId).innerHTML;
	var eBWzMetters = document.getElementById('eBWzMetters'+edgeBandingId).innerHTML;
	var eBComment= document.getElementById('eBComment'+edgeBandingId).innerHTML;
	
	var updatingOrderDataModalBody = "<h3>Okleina</h3><div style='margin-top: 20px;' class='row text-center'><div class='col-sm-3'><label for='eBType'>typ</label><select style='text-align-last: center;' id='eBType' class='form-control'>";
		<?PHP foreach($edgeBandTypes as $edgeBandType):?>
		updatingOrderDataModalBody += "<option value='<?=$edgeBandType->id?>'";

		if(<?=$edgeBandType->id?> == eBTypeId){
			updatingOrderDataModalBody += " selected";
		}	
		updatingOrderDataModalBody += "><?=$edgeBandType->type?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBSymbol'>symbol</label><select style='text-align-last: center;'  id='eBSymbol' class='form-control'>";
		<?PHP foreach($boardsSymbols as $boardSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSymbol->id?>'";
		if(<?=$boardSymbol->id?> == eBSymbolId){
			updatingOrderDataModalBody += " selected";
		}
		updatingOrderDataModalBody += "><?=$boardSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBSticker'>naklejki</label><select style='text-align-last: center;' id='eBSticker' class='form-control'>";
		<?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$edgeBandStickerSymbol->id?>'";
		if(<?=$edgeBandStickerSymbol->id?> == stickerSymbolId){
			updatingOrderDataModalBody += " selected";
		}			
		updatingOrderDataModalBody += "><?=$edgeBandStickerSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBWzMetters'>metry</label><div><input id='eBWzMetters' class='form-control text-center' type='text' min='0.5' max='10000' step='0.5' required value='"+eBWzMetters+"'/></div></div></div><div style='margin-top: 10px;' class='row'><div class='col-sm-3'><label for='newEBComment'>okleina uwagi:</label></div><div class='col-sm-9'><input id='eBComment' type='text' class='form-control text-center' value='"+eBComment+"'/></div></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setEdgeBanding('"+edgeBandingId+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function setBoard(boardId){
	var message = "";
	
	var boardSignId = document.getElementById('boardSignId'+boardId).innerHTML;
	var boardThicknessId = document.getElementById('boardThicknessId'+boardId).innerHTML;
	var boardSymbolId = document.getElementById('boardSymbolId'+boardId).innerHTML;
	var boardStructureId = document.getElementById('boardStructureId'+boardId).innerHTML;
	
	var newBoardSignId = document.getElementById('boardSign').value;
	var newBoardThicknessId = document.getElementById('boardThickness').value;
	var newBoardSymbolId = document.getElementById('boardSymbol').value;
	var newBoardStructureId = document.getElementById('boardStructure').value;
	
	
	if(boardSignId == newBoardSignId && boardThicknessId == newBoardThicknessId && boardSymbolId == newBoardSymbolId && boardStructureId == newBoardStructureId){
		message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Pozycja pozostaje bez zmian";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 1000);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=updateBoard",
			type: "post",
			data: { 
				'boardSignId' : newBoardSignId,
				'boardThicknessId' : newBoardThicknessId,
				'boardSymbolId' : newBoardSymbolId,
				'boardStructureId' : newBoardStructureId,
				'boardId' : boardId
			}
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";	
					document.getElementById('boardSignId'+boardId).innerHTML = newBoardSignId;
					document.getElementById('boardSign'+boardId).innerHTML = $("#boardSign option:selected").text();
					document.getElementById('boardThicknessId'+boardId).innerHTML = newBoardThicknessId;
					document.getElementById('boardThickness'+boardId).innerHTML = parseFloat($("#boardThickness option:selected").text());
					document.getElementById('boardSymbolId'+boardId).innerHTML = newBoardSymbolId;
					document.getElementById('boardSymbol'+boardId).innerHTML = $("#boardSymbol option:selected").text();
					document.getElementById('boardStructureId'+boardId).innerHTML = newBoardStructureId;
					document.getElementById('boardStructure'+boardId).innerHTML = $("#boardStructure option:selected").text();
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
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}


function updateBoard(boardId){
	var boardSignId = document.getElementById('boardSignId'+boardId).innerHTML;
	var boardThicknessId = document.getElementById('boardThicknessId'+boardId).innerHTML;
	var boardSymbolId = document.getElementById('boardSymbolId'+boardId).innerHTML;
	var boardStructureId = document.getElementById('boardStructureId'+boardId).innerHTML;
	
	
	var updatingOrderDataModalBody = "<h3>Zmiana płyty</h3><div style='margin-top: 20px;' class='row text-center'><div class='col-sm-3'><label for='boardSign'>rodzaj</label><select style='text-align-last: center;' id='boardSign' class='form-control'>";
		<?PHP foreach($boardsSigns as $boardSign):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSign->id?>'";

		if(<?=$boardSign->id?> == boardSignId){
			updatingOrderDataModalBody += " selected";
		}	
		updatingOrderDataModalBody += "><?=$boardSign->sign?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardThickness'>grubość</label><select style='text-align-last: center;'  id='boardThickness' class='form-control'>";
		<?PHP foreach($boardsThickness as $boardThickness):?>
		updatingOrderDataModalBody += "<option value='<?=$boardThickness->id?>'";
		if(<?=$boardThickness->id?> == boardThicknessId){
			updatingOrderDataModalBody += " selected";
		}
		updatingOrderDataModalBody += "><?=$boardThickness->thickness?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardSymbol'>symbol</label><select style='text-align-last: center;' id='boardSymbol' class='form-control'>";
		<?PHP foreach($boardsSymbols as $boardSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSymbol->id?>'";
		if(<?=$boardSymbol->id?> == boardSymbolId){
			updatingOrderDataModalBody += " selected";
		}			
		updatingOrderDataModalBody += "><?=$boardSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardStructure'>struktura</label><select style='text-align-last: center;'  id='boardStructure' class='form-control'>";
		<?PHP foreach($boardsStructures as $boardStructure):?>
		updatingOrderDataModalBody += "<option value='<?=$boardStructure->id?>'";
		if(<?=$boardStructure->id?> == boardStructureId){
			updatingOrderDataModalBody += " selected";
		}
		updatingOrderDataModalBody += "><?=$boardStructure->structure?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setBoard('"+boardId+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function removeEBPosition(edgeBandingId){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=removeEdgeBanding",
		type: "post",
		data: {
			'edgeBandingId': edgeBandingId
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrana pozycja oklejania została usunięta";
			document.getElementById("eB"+edgeBandingId).outerHTML = "";
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
			closeModal('updatingOrderDataModal');}, 1000);
	});
}

function removeEdgeBanding(edgeBandingId){
	document.getElementById('updatingOrderDataModalBody').innerHTML = "<h4>Czy napewno usunąć wybraną pozycję oklejania?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeEBPosition('"+edgeBandingId+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#updatingOrderDataModal').modal('show');
}

function setNewEdgeBanding(boardId){
	var message = "";
	
	var eBTypeId = document.getElementById('eBType').value;
	var eBSymbolId = document.getElementById('eBSymbol').value;
	var stickerSymbolId = document.getElementById('eBSticker').value;
	var eBWzMetters = document.getElementById('eBWzMetters').value;
	var eBComment = document.getElementById('eBComment').value;
	
	eBWzMetters = eBWzMetters.replace(",", ".");

	if (eBWzMetters == "" || isNaN(eBWzMetters) || eBWzMetters % 0.5 != 0 || eBWzMetters < 0.5 || eBWzMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Wartość metrów oklejania nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=addNewEdgeBanding",
			type: "post",
			data: { 
				'edgeBandTypeId' : eBTypeId,
				'edgeBandingBoardSymbolId' : eBSymbolId,
				'edgeBandStickerSymbolId' : stickerSymbolId,
				'edgeBandingMettersWz' : eBWzMetters,
				'edgeBandComment' : eBComment,
				'boardId' : boardId
			}
		});
		ajaxRequest.done(function (response){
			if(response != 'ACTION_FAILED'){
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				
				var edgeBandingTDContent = "<tr class='board"+boardId+"' id='eB"+response+"'><td style='border-top: none;'></td><td><div style='float: left;'><span id='eBTypeId"+response+"' style='display: none;'>"+eBTypeId+"</span><span id='eBType"+response+"'>"+$("#eBType option:selected").text()+"</span><span id='eBSymbolId"+response+"' style='display: none;'>"+eBSymbolId+"</span> <span id='eBSymbol"+response+"'>"+$("#eBSymbol option:selected").text()+"</span> (<span id='stickerSymbolId"+response+"' style='display: none;'>"+stickerSymbolId+"</span><span id='stickerSymbol"+response+"'>"+$("#eBSticker option:selected").text()+"</span>) -> <span id='eBWzMetters"+response+"'>"+(parseFloat(eBWzMetters)).toString()+"</span> [mb]</div><div class='text-right noPadding'><span style='font-size: 10px; cursor: pointer; padding:4px 8px;' onclick='removeEdgeBanding("+response+");' class='glyphicon glyphicon-remove btn btn-default noMargin'></span> <span onclick='updateEdgeBanding("+response+");' style='font-size: 10px; cursor: pointer; padding:4px 8px;' class='glyphicon glyphicon-pencil btn btn-default noMargin'></span></div><div id='eBComment"+response+"' style='font-style: italic;'>";
	
				if(eBComment != ""){
					edgeBandingTDContent += eBComment;
				}
				edgeBandingTDContent += "</div></td></tr>";
				$(edgeBandingTDContent).insertAfter($("tr.board"+boardId+":last"));

			}else{
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}

function addNewEdgeBanding(boardId){
	var updatingOrderDataModalBody = "<h3>Dodaj oklejanie</h3><div style='margin-top: 20px;' class='row text-center'><div class='col-sm-3'><label for='eBType'>typ</label><select style='text-align-last: center;' id='eBType' class='form-control'>";
		<?PHP foreach($edgeBandTypes as $edgeBandType):?>
		updatingOrderDataModalBody += "<option value='<?=$edgeBandType->id?>'><?=$edgeBandType->type?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBSymbol'>symbol</label><select style='text-align-last: center;'  id='eBSymbol' class='form-control'>";
		<?PHP foreach($boardsSymbols as $boardSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBSticker'>naklejki</label><select style='text-align-last: center;' id='eBSticker' class='form-control'>";
		<?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$edgeBandStickerSymbol->id?>'><?=$edgeBandStickerSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='eBWzMetters'>metry</label><div><input id='eBWzMetters' class='form-control text-center' type='text' min='0.5' max='10000' step='0.5' required /></div></div></div><div style='margin-top: 10px;' class='row'><div class='col-sm-3'><label for='newEBComment'>okleina uwagi:</label></div><div class='col-sm-9'><input id='eBComment' type='text' class='form-control text-center' /></div></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewEdgeBanding('"+boardId+"');\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

function removeBoardPosition(boardId){
	var message = "";
		
	var ajaxRequest = $.ajax({
		url: "index.php?action=removeBoard",
		type: "post",
		data: {
			'boardId': boardId
		}
	});
			
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Wybrana pozycja została usunięta";
			$('.board'+boardId).remove();
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
			closeModal('updatingOrderDataModal');}, 1000);
	});
}

function removePosition(boardId){
	document.getElementById('updatingOrderDataModalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybraną pozycję razem z całą jej zawartością?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"removeBoardPosition('"+boardId+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
		
	$('#updatingOrderDataModal').modal('show');
}

function setNewBoard(){
	var message = "";
	
	var boardSignId = document.getElementById('boardSign').value;
	var boardThicknessId = document.getElementById('boardThickness').value;
	var boardSymbolId = document.getElementById('boardSymbol').value;
	var boardStructureId = document.getElementById('boardStructure').value;
	var boardAmount = document.getElementById('amount').value;
	var cuttingMetters = document.getElementById('cuttingMetters').value;
	
	boardAmount = boardAmount.replace(",", ".");
	cuttingMetters = cuttingMetters.replace(",", ".");
	
	
	if (isNaN(boardAmount) || boardAmount % 0.5 != 0 || boardAmount < 0.5 || boardAmount > 1000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość ilości sztuk nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2500);
	}else if (isNaN(cuttingMetters) || cuttingMetters % 0.5 != 0 || cuttingMetters < 0.5 || cuttingMetters > 10000){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość metrów cięcia nie jest poprawna";
		showMessage(message);
		setTimeout(function(){closeModal('updatingOrderDataModal');}, 2500);
	}else{
		var ajaxRequest = $.ajax({
			url: "index.php?action=addNewBoard",
			type: "post",
			data: { 
				'boardSignId' : boardSignId,
				'boardThicknessId' : boardThicknessId,
				'boardSymbolId' : boardSymbolId,
				'boardStructureId' : boardStructureId,
				'boardAmount' : boardAmount,
				'cuttingMetters' : cuttingMetters,
				'orderId' : orderId
			}
		});
					
		ajaxRequest.done(function (response){
			if(response != 'ACTION_FAILED'){
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				
				var newBoardContent = "<tr class='board"+response+"' id='"+response+"'><td class='text-center' style='vertical-align: middle; border-top: 2px solid white;'><h3 class='noPadding noMargin' style='float: left; margin-top: 0px;'><span id='boardSignId"+response+"' style='display: none;'>"+boardSignId+"</span><span id='boardThicknessId"+response+"' style='display: none;'>"+boardThicknessId+"</span><span id='boardSymbolId"+response+"' style='display: none;'>"+boardSymbolId+"</span><span id='boardStructureId"+response+"' style='display: none;'>"+boardStructureId+"</span><span style='margin-left: 20px;' id='boardSign"+response+"'>"+$("#boardSign option:selected").text()+"</span><span id='boardThickness"+response+"'>"+parseFloat($("#boardThickness option:selected").text())+"</span> - <span id='boardSymbol"+response+"'>"+$("#boardSymbol option:selected").text()+"</span> <span id='boardStructure"+response+"'>"+$("#boardStructure option:selected").text()+"</span></h3><div class='noPadding noMargin' style='float: right;'><span onclick='removePosition("+response+");' style='cursor: pointer; font-size: 10px; padding:4px 8px;' class='glyphicon glyphicon-remove btn btn-default'></span><span onclick='updateBoard("+response+");' style='cursor: pointer; font-size: 10px; padding:4px 8px;' class='glyphicon glyphicon-pencil top btn btn-default'></span></div><div style='clear: both;'></div></td><td style='border-top: 2px solid white; padding-bottom: 0px; border-bottom: 1px solid transparent!important;'><div style='padding-bottom: 6px;'><div style='float: left;'>ilość: <span id='amount"+response+"'>"+(parseFloat(boardAmount)).toString()+"</span> [szt.]</div><div class='text-right noPadding'><span onclick='updateBoardAmount("+response+");' style='font-size: 10px; cursor: pointer; padding:4px 8px;' class='glyphicon glyphicon-pencil btn btn-default noMargin'></span></div></div><div style='border-top: 1px solid #ddd; padding-top: 7px; padding-bottom: 6px;'><div style='float: left;'>cięcie: <span id='cuttingMetters"+response+"'>"+(parseFloat(cuttingMetters)).toString()+"</span> [mb]</div><div class='text-right noPadding'><span onclick='updateCuttingMetters("+response+");' style='font-size: 10px; cursor: pointer; padding:4px 8px;' class='glyphicon glyphicon-pencil btn btn-default noMargin'></span></div></div><div style='margin: 0px auto 3px auto; padding:4px 8px;' class='btn btn-default btn-block' onclick='addNewEdgeBanding("+response+");'><span style='font-size: 10px;' class='glyphicon glyphicon-plus'></span> Oklejanie</div></td></tr>";
				
				document.getElementById('newPositionAddingButton').insertAdjacentHTML("beforebegin", newBoardContent);

			}else{
				message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
			}

		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			showMessage(message);
			setTimeout(function(){
				closeModal('updatingOrderDataModal');}, 1000);
		});
	}
}

function addNewBoard(){
	var updatingOrderDataModalBody = "<h3>Dodaj nową pozycję</h3><div style='margin-top: 20px;' class='row text-center'><div class='col-sm-3'><label for='boardSign'>rodzaj</label><select style='text-align-last: center;' id='boardSign' class='form-control'>";
		<?PHP foreach($boardsSigns as $boardSign):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSign->id?>'";

		if('<?=$boardSign -> sign?>' == 'L'){
			updatingOrderDataModalBody += " selected";
		}	
		updatingOrderDataModalBody += "><?=$boardSign->sign?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardThickness'>grubość</label><select style='text-align-last: center;'  id='boardThickness' class='form-control'>";
		<?PHP foreach($boardsThickness as $boardThickness):?>
		updatingOrderDataModalBody += "<option value='<?=$boardThickness->id?>'";
		if('<?=$boardThickness -> thickness?>' == '18.0'){
			updatingOrderDataModalBody += " selected";
		}
		updatingOrderDataModalBody += "><?=$boardThickness->thickness?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardSymbol'>symbol</label><select style='text-align-last: center;' id='boardSymbol' class='form-control'>";
		<?PHP foreach($boardsSymbols as $boardSymbol):?>
		updatingOrderDataModalBody += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div><div class='col-sm-3'><label for='boardStructure'>struktura</label><select style='text-align-last: center;'  id='boardStructure' class='form-control'>";
		<?PHP foreach($boardsStructures as $boardStructure):?>
		updatingOrderDataModalBody += "<option value='<?=$boardStructure->id?>'><?=$boardStructure->structure?></option>";
		<?PHP endforeach; ?>
		updatingOrderDataModalBody += "</select></div></div><div class='row' style='margin-top: 20px;'><div class='col-sm-3'><label for='amount'>ilość [szt.]</label></div><div class='col-sm-9'><input type='text' id='amount' name='amount' class='form-control text-center' min='0.5' max='1000' step='0.5' /></div></div><div class='row' style='margin-top: 15px;'><div class='col-sm-3'><label for='cuttingMetters'>cięcie [mb]</label></div><div class='col-sm-9'><input type='text' id='cuttingMetters' name='cuttingMetters' class='form-control text-center' min='0.5' max='10000' step='0.5' /></div></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='setNewBoard();'><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	document.getElementById('updatingOrderDataModalBody').innerHTML = updatingOrderDataModalBody;
		
	$('#updatingOrderDataModal').modal('show');
}

</script>