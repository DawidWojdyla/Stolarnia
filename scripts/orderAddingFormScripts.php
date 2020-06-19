<script>
var lastPositionId = 0;
var positionsAmount = 0;
var edgeBandingLastId = 0;

$(function(){
	checkIfDocumentNumberIsRequired();
	checkIfCustomerDataRequired();
	checkAmounts();
});

function setPotentialOrderCompletionDate(){
	if(document.getElementById('sawSelect').value == '2'){
		document.getElementById('orderCompletionDate').value = '<?=$potentialOrderCompletionDates[2]?>';
	}
	else{
		document.getElementById('orderCompletionDate').value = '<?=$potentialOrderCompletionDates[1]?>';
	}
}

function checkAmounts(){
	var sawSelect = document.getElementById('sawSelect').value;
	var orderCompletionDate = document.getElementById('orderCompletionDate').value;
	
	var ajaxRequest = $.ajax({
			url: "index.php?action=returnAmountsOfPeriod",
			type: "post",
			data: {
				sawNumber: sawSelect,
				date: orderCompletionDate
			}
		});
		
		ajaxRequest.done(function (response){
			if(response != "Brak danych"){
				var amounts = JSON.parse(response);
				document.getElementById('boardsAmount').innerHTML = amounts.boardsAmount;
				if(parseInt(amounts.boardsAmount) >= <?=$limits -> boardsPerDay?>){
					document.getElementById('boardsAmount').style.backgroundColor  = "lightcoral";
				}
				else{
					document.getElementById('boardsAmount').style.backgroundColor  = "inherit";
				}
				
				document.getElementById('cuttingMetters').innerHTML = amounts.cuttingMetters;
				if(parseInt(amounts.cuttingMetters) >= <?=$limits -> cuttingMettersPerDay?>){
					document.getElementById('cuttingMetters').style.backgroundColor  = "lightcoral";
				}
				else{
					document.getElementById('cuttingMetters').style.backgroundColor  = "inherit";
				}
				document.getElementById('edgeBandingMetters').innerHTML = amounts.edgeBandingMetters;
				if(parseInt(amounts.edgeBandingMetters) >= <?=$limits -> edgeBandingMettersPerDay?>){
					document.getElementById('edgeBandingMetters').style.backgroundColor  = "lightcoral";
				}
				else{
					document.getElementById('edgeBandingMetters').style.backgroundColor  = "inherit";
				}
			}
			
		});
		
		ajaxRequest.fail(function (){
			document.getElementById('boardsAmount').innerHTML = "Nie można pobrać";
			document.getElementById('cuttingMetters').innerHTML = "Nie można pobrać";
			document.getElementById('edgeBandingMetters').innerHTML = "Nie można pobrać";
		});
}

function checkIfDocumentNumberIsRequired(){
	if (document.getElementById('documentTypeSelect').value == '4'){
		document.getElementById('documentNumberInput').required = false;
	}
	else{
		document.getElementById('documentNumberInput').required = true;
	}
}

function checkIfCustomerDataRequired(){
		if (document.getElementById('customerIdSelect').value == '1'){
		document.getElementById('customerPhone').disabled = false;
		document.getElementById('customerName').disabled = false;

	}
	else{
		document.getElementById('customerPhone').disabled = true;
		document.getElementById('customerName').disabled = true;
	}
}

function setSymbolSelectOptionsAccordingToSignType(id, typeId){
	var symbolSelect = "";
	symbolSelect += "<select class='form-control textCenterSelect smallerPadding' id='boardSymbol"+id+"' name='positions[position"+id+"][boardSymbolId]' onchange=\"addOtherBoardSymbolIfNeeded('"+id+"');\">";
	<?PHP foreach($boardsSymbols as $boardSymbol): ?>
	switch(typeId){
		case '1':
			if('<?=$boardSymbol -> melamine?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '2':
			if('<?=$boardSymbol -> worktops?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '3':
			if('<?=$boardSymbol -> mdf?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '4':
			if('<?=$boardSymbol -> hdf?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '5':
			if('<?=$boardSymbol -> veneer?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '6':
			if('<?=$boardSymbol -> acrylic?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		case '7':
			if('<?=$boardSymbol -> glossy?>' != ''){
				symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			}
			break;
		default:
			symbolSelect += "<option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option>";
			break;
	}
	
	<?PHP endforeach; ?>
	symbolSelect += "<option value='-1'>+ inny</option></select>";
	
	document.getElementById("symbolSelect"+id).innerHTML = symbolSelect;
}

function setBoardSelectDefaultOptions(id){
	var signType = "";
	var signId = document.getElementById('boardSign'+id).value;
	var thicknessSelect = document.getElementById('boardThickness'+id);
	var symbolSelect = document.getElementById('boardSymbol'+id);
	
	<?PHP foreach($boardsSigns as $sign): ?>
		
		if(signId == "<?=$sign -> id?>"){
			signType = "<?=$sign -> type_id?>";
			<?PHP if ($sign -> thicknessId): ?>
				thicknessSelect.value =  "<?=$sign -> thicknessId?>";
			<?PHP else: ?>
				thicknessSelect.value =  "8";
			<?PHP endif; ?>
			
			<?PHP if ($sign -> noSymbolSignId): ?>
				symbolSelect.value = '1';
				symbolSelect.disabled = true;
			<?PHP else: ?>
				symbolSelect.disabled = false;
			<?PHP endif; ?>
		}
		
	<?PHP endforeach; ?>
	if(!symbolSelect.disabled){
		setSymbolSelectOptionsAccordingToSignType(id, signType);
	}

}

function sendOrderAddingForm(){
	if(positionsAmount){
		document.getElementById('sendingButton').click();
	}
	else if (!document.body.contains(document.getElementById("noPositionInfo"))){
		document.getElementById('boardsInputs').insertAdjacentHTML("beforebegin", "<tr id='noPositionInfo' class='danger text-center'><td colspan='2'>Najpierw dodaj przynajmniej jedną pozycję do zamówienia!</td></tr>");
	}
}
function removePosition(id){
	var idNum = parseInt(id);
	document.getElementById('position'+id).remove();
	positionsAmount--;
	
	var positions = document.getElementsByClassName("positions");
	for (var i = 0; i < positions.length; i++) {
		positions[i].innerHTML = parseInt(i+1);
	}
}

function removeEdgeBanding(id){
	document.getElementById(id).remove();
	document.getElementById(id+'comment').remove();
}

function addBoardSymbolToSelect(id){
	var symbolSelect = document.getElementById('boardSymbol'+id);
	var newSymbol = document.getElementById('newBoardSymbol').value;
	newSymbol = newSymbol.trim();
	
	if(newSymbol != ""){
		newSymbol = newSymbol.toUpperCase();
		if($("#boardSymbol" + id + " option[value='0']").length == 0){
			var option = document.createElement("option");
			option.value = '0';
			option.text = newSymbol;
			symbolSelect.add(option);
		}else{
			$('#boardSymbol' + id).find('option[value="0"]').text(newSymbol);
		}
		document.getElementById('otherBoardSymbol'+id).value = newSymbol;
		symbolSelect.value = '0';
		$('#modal').modal('hide');
		
	}else{
		symbolSelect.value = '1';
		document.getElementById('modalBody').innerHTML = "Symbol płyty nie może być pusty";
		setTimeout(function(){ $('#modal').modal('hide'); }, 1200);
	}
}

function closeAddingOtherSymbolForm(id){
	document.getElementById('boardSymbol'+id).value = '1';
	$('#modal').modal('hide');
}

function addOtherBoardSymbolIfNeeded(id){
	 if(document.getElementById('boardSymbol'+id).value == '-1'){	
		document.getElementById('modalBody').innerHTML = "<h4>Podaj symbol płyty:</h4><div style='margin-top: 20px;'><input class='form-control text-center text-uppercase' type='text' id='newBoardSymbol' maxlength='15' required/></div><div style='margin-top: 20px;' class='btn btn-default btn-block' onclick=\"addBoardSymbolToSelect('"+id+"');\"><span class=\"glyphicon glyphicon-ok\"></span> Dodaj</div><div class='btn btn-default btn-block' onclick=\"closeAddingOtherSymbolForm('"+id+"');\"><span class=\"glyphicon glyphicon-remove\"></span>Anuluj</div>";
		
		$('#modal').modal('show');
		 
	 }
}

function addEdgeBanding(position){
	var thicknessSelect = document.getElementById('boardThickness'+position);
	var thickness = thicknessSelect.options[thicknessSelect.selectedIndex].text;
	thickness = parseInt(thickness);
	if(thickness < 8){
		document.getElementById('modalBody').innerHTML = "<h4><span class='glyphicon glyphicon-ban-circle'></span> Grubość płyty musi być co najmniej 8mm!</h4>";
		$('#modal').modal('show');
		setTimeout(function(){ $('#modal').modal('hide'); }, 3000);
		
	}else{
		edgeBandingLastId++;
		document.getElementById('addNewEdgeBandingButton'+position).insertAdjacentHTML("beforebegin","<div style='margin-top: 10px;' class='row text-center' id='e"+edgeBandingLastId+"'><div class='col-sm-2'></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>typ</label><select  id='edgeBandType"+edgeBandingLastId+"' name='positions[position"+position+"][edgeBandTypesId][]' class='form-control textCenterSelect'><?PHP foreach($edgeBandTypes as $edgeBandType):?><option value='<?=$edgeBandType->id?>'><?=$edgeBandType->type?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>symbol</label><select id='edgeBandingBoardSymbol"+edgeBandingLastId+"' class='form-control textCenterSelect smallerPadding pos"+position+"' name='positions[position"+position+"][edgeBandingBoardSymbolsId][]'><?PHP foreach($edgeBandSymbols as $edgeBandSymbol):?><option value='<?=$edgeBandSymbol->id?>'><?=$edgeBandSymbol->symbol?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>naklejki</label><select name='positions[position"+position+"][edgeBandsStickersId][]' class='form-control textCenterSelect smallerPadding'><?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?><option value='<?=$edgeBandStickerSymbol->id?>'><?=$edgeBandStickerSymbol->symbol?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>oklejanie [mb]</label><input name='positions[position"+position+"][edgeBandingMetters][]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-1' onclick=\"removeEdgeBanding('e"+edgeBandingLastId+"');\"><label class='addingFormSmallLabel textShadow'><span class='glyphicon glyphicon-remove pointer'></span></label></div><div class='col-sm-1'></div></div><div style='margin-top: 10px;' class='row' id='e"+edgeBandingLastId+"comment'><div class='col-sm-2'></div><div class='col-sm-2 smallerPadding text-center'><label class='addingFormSmallLabel textShadow'>uwagi:</label></div><div class='col-sm-6 noPadding'><input class='form-control' name='positions[position"+position+"][edgeBandComments][]' autocomplete='off' type='text'/></div><div class='col-sm-2'></div></div>");

		document.getElementById('edgeBandingBoardSymbol'+edgeBandingLastId).value = document.getElementById('boardSymbol'+position).value;
			
		<?PHP $i = 0; foreach($edgeBandDefaultTypes as $defaultEdgeBand): ?>
			<?PHP if($i): ?>else <?PHP endif; ?>if(thickness <= <?=$defaultEdgeBand -> max_thickness?>){
			document.getElementById('edgeBandType'+edgeBandingLastId).value = '<?=$defaultEdgeBand -> edge_band_type_id?>';
		}
		<?PHP $i++; endforeach; ?>
	}
}

function addBoardInputs(){
	
	if (document.body.contains(document.getElementById("noPositionInfo"))){
		document.getElementById("noPositionInfo").remove();
	}
	lastPositionId++;
	positionsAmount++;
	
	document.getElementById('boardsInputs').insertAdjacentHTML("beforebegin", "<tr id='position"+lastPositionId+"'><td style='border-color: transparent!important; padding:0px;' colspan='2'><div class='contentContainer' style='padding: 5px;!important'><div class='textShadow'><div style='float:left;'><label> Pozycja <span class='positions' id='p"+lastPositionId+"'>"+positionsAmount+"</span></label></div><div style='float: right;'><span class='glyphicon glyphicon-remove pointer' onclick=\"removePosition('"+lastPositionId+"');\"></span></div><div style='clear: both;'></div></div><div class='container-fluid'><div class='row text-center'><div class='col-sm-1'></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>rodzaj</label><select class='form-control textCenterSelect' name='positions[position"+lastPositionId+"][boardSignId]' id='boardSign"+lastPositionId+"' onchange=\"setBoardSelectDefaultOptions('"+lastPositionId+"');\"><?PHP foreach($boardsSigns as $boardSign):?><option value='<?=$boardSign->id?>' <?PHP if($boardSign->sign == 'L'): ?>selected<?PHP endif; ?>><?=$boardSign->sign?></option><?PHP endforeach; ?></select></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>grubość</label><select class='form-control textCenterSelect' id='boardThickness"+lastPositionId+"' name='positions[position"+lastPositionId+"][boardThicknessId]'><?PHP foreach($boardsThickness as $boardThickness):?><option value='<?=$boardThickness->id?>' <?PHP if($boardThickness -> thickness == '18.0'): ?>selected<?PHP endif; ?>><?=$boardThickness->thickness?></option><?PHP endforeach; ?></select></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>symbol</label><div id='symbolSelect"+lastPositionId+"'></div><input type='hidden' id='otherBoardSymbol"+lastPositionId+"' name='positions[position"+lastPositionId+"][otherBoardSymbol]' value=''/></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>ilość [szt.]</label><input name='positions[position"+lastPositionId+"][amount]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>cięcie [mb]</label><input name='positions[position"+lastPositionId+"][cuttingMetters]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-1'></div></div><div style='margin-top: 10px;' class='row' id='addNewEdgeBandingButton"+lastPositionId+"'><div class='col-sm-2'></div><div class='col-sm-8 noPadding'><div class='btn btn-default btn-block' onclick=\"addEdgeBanding('"+lastPositionId+"');\"><span class='glyphicon glyphicon-plus'></span> Dodaj oklejanie</div></div><div class='col-sm-2'></div></div></div></div></td></tr>");
	
	setSymbolSelectOptionsAccordingToSignType(lastPositionId, "1");
}
</script>