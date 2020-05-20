<script>

function showOptions(id){
	
	var modalBody = "";
	
	if(document.getElementById('hidden'+id).innerHTML == ""){
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfHide("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div>";
	}else{
		modalBody += "<div class='btn btn-default btn-block' onclick='askIfShow("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div>";
	}
	modalBody += "<div class='btn btn-default btn-block' onclick=\"askIfRemove('"+id+"');\"><span class=\"glyphicon glyphicon-trash\"></span> Usuń</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-menu-left\"></span> Powrót</div>";
	
	document.getElementById('modalBody').innerHTML = modalBody;
	$('#modal').modal('show');
}


function removeFromHidden(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=removeEdgeBandStickerSymbolFromHidden",
			type: "post",
			data: { 'edgeBandStickerSymbolId' : id }
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz włączyć widoczność wybranego symbolu okleiny?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeFromHidden("+id+");'><span class=\"glyphicon glyphicon-eye-open\"></span> Włącz widoczność</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function hideEdgeBandStickerSymbol(id){
		var ajaxRequest = $.ajax({
			url: "index.php?action=hideEdgeBandStickerSymbol",
			type: "post",
			data: { 'edgeBandStickerSymbolId' : id }
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz ukryć wybrany symbol okleiny?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='hideEdgeBandStickerSymbol("+id+");'><span class=\"glyphicon glyphicon-eye-close\"></span> Ukryj</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function removeEdgeBandStickerSymbol(id){

	var ajaxRequest = $.ajax({
			url: "index.php?action=removeEdgeBandStickerSymbol",
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
	document.getElementById('modalBody').innerHTML = "<h4>Czy napewno chcesz usunąć wybrany symbol okleiny?</h4><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick='removeEdgeBandStickerSymbol("+id+");'><span class=\"glyphicon glyphicon-ok\"></span> Tak</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
}

function checkIfEdgeBandStickerSymbolExists(stickerSymbol){
	var stickers = document.getElementsByClassName("stickerSymbols");
	for (var i = 0; i < stickers.length; i++) {
		if(stickers[i].innerHTML == stickerSymbol){
			return true;
		}
	}
	return false;
}

function setNewEdgeBandStickerSymbol(){
	var message = "";
	var newEdgeBandStickerSymbol = document.getElementById('newEdgeBandStickerSymbol').value;
	newEdgeBandStickerSymbol = newEdgeBandStickerSymbol.trim();
	if (newEdgeBandStickerSymbol == "" || newEdgeBandStickerSymbol.length > 10){
		message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Symbol okleiny musi zawierać od 1 do 10 znaków";
		document.getElementById('modalBody').innerHTML = message;
		setTimeout(function(){$('#modal').modal('hide');}, 1500);
	}else{
		
		if(checkIfEdgeBandStickerSymbolExists(newEdgeBandStickerSymbol)){
			message = "<span class=\"glyphicon glyphicon-floppy-remove\"></span> Podany symbol okleiny już istnieje";
			document.getElementById('modalBody').innerHTML = message;
			setTimeout(function(){$('#modal').modal('hide');}, 1500);
		}else{
			var ajaxRequest = $.ajax({
				url: "index.php?action=addNewEdgeBandStickerSymbol",
				type: "post",
				data: {
					'newEdgeBandStickerSymbol' : newEdgeBandStickerSymbol
				}
			});
			ajaxRequest.done(function (response){
				if(response != 'ACTION_FAILED'){
					message = "<span class=\"glyphicon glyphicon-floppy-saved\"></span> Zapisano zmiany";
					document.getElementById('lastRow').insertAdjacentHTML("beforebegin", "<tr id='"+response+"' class='pointer' onclick=\"showOptions('"+response+"');\"><td><label><span class='stickerSymbols' id='stickerSymbol"+response+"'>"+newEdgeBandStickerSymbol+"</span></label></td><td id='hidden"+response+"'></td></tr>");
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

function addNewEdgeBandStickerSymbol(){
	document.getElementById('modalBody').innerHTML = "<h4>Podaj nowy symbol okleiny:</h4><input style='margin-top: 20px;' class='form-control text-center' type='text' id='newEdgeBandStickerSymbol' maxlength='10' required/><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"setNewEdgeBandStickerSymbol();\"><span class=\"glyphicon glyphicon-floppy-disk\"></span> Zapisz</div><div class='btn btn-default btn-block' data-dismiss='modal' type='button'><span class=\"glyphicon glyphicon-remove\"></span> Anuluj</div>";
	
	$('#modal').modal('show');
}


</script>