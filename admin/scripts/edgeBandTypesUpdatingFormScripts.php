<script>

var edgeBandsDefault = [];
var maxThickness = [];
	<?PHP foreach ($edgeBandTypesDefault as $defaultType): ?>
	edgeBandsDefault.push( '<?=$defaultType -> edge_band_type_id ?>');
	maxThickness.push('<?=$defaultType -> max_thickness ?>'); 
	<?PHP endforeach; ?>

function showOptions(id){
	
	var modalBody = "";
	
	if(document.getElementById('hidden'+id).innerHTML == ""){
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfHide("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div>";
	}else{
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfShow("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div>";
	}
	if(document.getElementById('edgeBand'+id).innerHTML != "Pasek"){
	
		modalBody += "<div class='btn btn-default btn-block' onclick=\"askIfRemove('"+id+"');\"><span class=\"glyphicon glyphicon-trash\"></span> Usuń</div>";
	}
	
	if(document.getElementById('default'+id).innerHTML == ""){
	
		modalBody += "<div class='btn btn-default btn-block' onclick=\"askIfSetAsDefault('"+id+"');\"><span class=\"glyphicon glyphicon-flag\"></span> Ustaw jako standard</div>";
	}
	
	modalBody += "<div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"></span> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
	$('#modal').modal('show');
}

function setAsDefault(id, defaultTypeNumber){
	var ajaxRequest = $.ajax({
		url: "index.php?action=updateDefaultEdgeBandType",
		type: "post",
		data: { 'edgeBandTypeId' : id,
					'maxThickness' : maxThickness[defaultTypeNumber]
				}
	});
	ajaxRequest.done(function (response){
		switch(response){
			case 'ACTION_OK': 
				message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
				document.getElementById('default'+id).innerHTML = "<span class=\"glyphicon glyphicon-flag\"></span>";
				document.getElementById('default'+edgeBandsDefault[defaultTypeNumber]).innerHTML = "";
				edgeBandsDefault[defaultTypeNumber] = id;
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
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){
				$('#modal').modal('hide');}, 1200);
		});
}

function chooseMaxThickness(id){
	var type = document.getElementById("edgeBand"+id).innerHTML;
	if (type != "Pasek"){
		var thickness = type.split('/')[0];
		for (var i = 0; i < maxThickness.length; i++) {
			if(thickness - 2 <= maxThickness[i]){
				setAsDefault(id, i);
				break;
			}
		 }
	}else{
		setAsDefault(id, 2);
	}
}

function askIfSetAsDefault(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno ustawić wybrany typ jako standardowy?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='chooseMaxThickness("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}


function removeFromHidden(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeEdgeBandTypeFromHidden",
			type: "post",
			data: { 'edgeBandTypeId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('hidden'+id).innerHTML = "";
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
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfShow(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz włączyć widoczność wybranego typu?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeFromHidden("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function hideEdgeBandType(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=hideEdgeBandType",
			type: "post",
			data: { 'edgeBandTypeId' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('hidden'+id).innerHTML = "<span class=\"glyphicon glyphicon-eye-close\"></span>";
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
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){
				$('#modal').modal('hide');}, 1200);
		});
}

function askIfHide(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz ukryć wybrany typ?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='hideEdgeBandType("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function removeEdgeBandType(id){

	var ajaxRequest = $.ajax({
			url: "index.php?action=removeEdgeBandType",
			type: "post",
			data: { 'id' : id }
		});
		ajaxRequest.done(function (response){
			switch(response){
				case 'ACTION_OK': 
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById(id).outerHTML = "";
					break;
				case 'FORM_DATA_MISSING': 
				case 'ACTION_FAILED': 
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
					break;
				case 'NO_PERMISSION': 
					message = "Brak uprawnień";
					break;
				default:
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie można usunąć typów, które są w użyciu";
					break;
			}
		});
		
		ajaxRequest.fail(function (){
		  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
		 });
		 
		ajaxRequest.always(function(){
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){
				$('#modal').modal('hide');}, 1500);
		});
}

function askIfRemove(id){
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybrany typ?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeEdgeBandType("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function checkIfEdgeBandTypeExists(type){
	var types = document.getElementsByClassName("edgeBands");
	for (var i = 0; i < types.length; i++) {
		if(types[i].innerHTML == type){
			return true;
		}
	}
	return false;
}

function setNewEdgeBandType(){
	var message = "";
	var newEdgeBandType = document.getElementById('newEdgeBandType').value;
	newEdgeBandType = newEdgeBandType.trim();
	if (newEdgeBandType == "" || newEdgeBandType.length < 4 || newEdgeBandType.length > 6 || !newEdgeBandType.match(/^[0-9]{2}\/[0-9\,\.]{1,3}$/)){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podana wartość jest niepoprawna";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 2000);
	}else{
		
		if(checkIfEdgeBandTypeExists(newEdgeBandType)){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podany typ już istnieje";
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){$('#modal').modal('hide');}, 1500);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=addNewEdgeBandType",
				type: "post",
				data: {
					'newEdgeBandType' : newEdgeBandType
				}
			});
			ajaxRequest.done(function (response){
				if(response != 'ACTION_FAILED'){
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('lastRow').insertAdjacentHTML("beforebegin", "<tr id='"+response+"' class='pointer' onclick=\"showOptions('"+response+"');\"><td><label><span class='edgeBands' id='edgeBand"+response+"'>"+newEdgeBandType+"</span></label></td><td id='hidden"+response+"'></td><td id='default"+response+"'></td></tr>");
				}else{
					message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
				}
			});
			
			ajaxRequest.fail(function (){
			  message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Nie udało się zapisać zmian";
			 });
			 
			ajaxRequest.always(function(){
				document.getElementById('modalBody').innerHTML = message;
				setTimeout(function(){
					$('#modal').modal('hide');}, 1000);
			});
		}
	}
}

function addNewEdgeBandType(){
	document.getElementById('modalBody').innerHTML = "<h4>Podaj nowy typ okleiny (wysokość / grubość):</h4><input style='margin-top: 20px;' class='form-control text-center' type='text' id='newEdgeBandType' maxlength='6' required/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewEdgeBandType();\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	$('#modal').modal('show');
}


</script>