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

function setThicknessDefaultOption(id, signId){
	var thicknessSelect = document.getElementById('boardThickness'+id);
	switch(signId){
		case '1': 
			thicknessSelect.value = '13';
			break;
		case '3': 
			thicknessSelect.value = '1';
			break;
		case '4': 
			thicknessSelect.value = '9';
			break;
		case '6': 
			thicknessSelect.value = '2';
			break;
		case '7': 
			thicknessSelect.value = '6';
			break;
		default:
			thicknessSelect.value = '8';
			break;
	}
}

function checkIfSymbolSelectIsDisabled(id, signId){
	
	if(signId == '5' || signId == '6' || signId == '7'){
		document.getElementById('boardSymbol'+id).disabled = true;
	}
	else{
		document.getElementById('boardSymbol'+id).disabled = false;
	}
}

function setBoardSelectDefaultOptions(id){
	document.getElementById('boardSymbol'+id).value = '1';
	var signId = document.getElementById('boardSign'+id).value;
	setThicknessAndSymbolDefaultOption(id, signId);
	checkIfSymbolSelectIsDisabled(id, signId);

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



function addEdgeBanding(position){
	edgeBandingLastId++;
	document.getElementById('addNewEdgeBandingButton'+position).insertAdjacentHTML("beforebegin","<div style='margin-top: 10px;' class='row text-center' id='e"+edgeBandingLastId+"'><div class='col-sm-2'></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>typ</label><select name='positions[position"+lastPositionId+"][edgeBandTypesId][]' class='form-control textCenterSelect'><?PHP foreach($edgeBandTypes as $edgeBandType):?><option value='<?=$edgeBandType->id?>' <?PHP if($edgeBandType -> type == '22/08'): ?>selected<?PHP endif; ?>><?=$edgeBandType->type?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>symbol</label><select id='edgeBandingBoardSymbol"+position+"' class='form-control textCenterSelect pos"+position+"' name='positions[position"+lastPositionId+"][edgeBandingBoardSymbolsId][]'><?PHP foreach($boardsSymbols as $boardSymbol):?><option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>naklejki</label><select name='positions[position"+lastPositionId+"][edgeBandsStickersId][]' class='form-control textCenterSelect'><?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?><option value='<?=$edgeBandStickerSymbol->id?>'><?=$edgeBandStickerSymbol->symbol?></option><?PHP endforeach; ?></select></div><div style='padding-top: 5px;' class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>oklejanie [mb]</label><input name='positions[position"+lastPositionId+"][edgeBandingMetters][]' class='form-control' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-1' onclick=\"removeEdgeBanding('e"+edgeBandingLastId+"');\"><label class='addingFormSmallLabel textShadow'><span class='glyphicon glyphicon-remove pointer'></span></label></div><div class='col-sm-1'></div></div><div style='margin-top: 10px;' class='row' id='e"+edgeBandingLastId+"comment'><div class='col-sm-2'></div><div class='col-sm-2 smallerPadding text-center'><label class='addingFormSmallLabel textShadow'>uwagi:</label></div><div class='col-sm-6 noPadding'><input class='form-control' name='positions[position"+lastPositionId+"][edgeBandComments][]' autocomplete='off' type='text'/></div><div class='col-sm-2'></div></div>");

	if(document.getElementsByClassName('pos'+position).length == 1){
		document.getElementById('edgeBandingBoardSymbol'+position).value = document.getElementById('boardSymbol'+position).value;
	}
}

function addBoardInputs(){
	
	if (document.body.contains(document.getElementById("noPositionInfo"))){
		document.getElementById("noPositionInfo").remove();
	}
	lastPositionId++;
	positionsAmount++;
	
	document.getElementById('boardsInputs').insertAdjacentHTML("beforebegin", "<tr id='position"+lastPositionId+"'><td style='border-color: transparent!important; padding:0px;' colspan='2'><div class='contentContainer' style='padding: 5px;!important'><div class='textShadow'><div style='float:left;'><label> Pozycja <span class='positions' id='p"+lastPositionId+"'>"+positionsAmount+"</span></label></div><div style='float: right;'><span class='glyphicon glyphicon-remove pointer' onclick=\"removePosition('"+lastPositionId+"');\"></span></div><div style='clear: both;'></div></div><div class='container-fluid'><div class='row text-center'><div class='col-sm-1'></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>rodzaj</label><select class='form-control textCenterSelect' name='positions[position"+lastPositionId+"][boardSignId]' id='boardSign"+lastPositionId+"' onchange=\"setBoardSelectDefaultOptions('"+lastPositionId+"');\"><?PHP foreach($boardsSigns as $boardSign):?><option value='<?=$boardSign->id?>' <?PHP if($boardSign->sign == 'L'): ?>selected<?PHP endif; ?>><?=$boardSign->sign?></option><?PHP endforeach; ?></select></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>grubość</label><select class='form-control textCenterSelect' id='boardThickness"+lastPositionId+"' name='positions[position"+lastPositionId+"][boardThicknessId]'><?PHP foreach($boardsThickness as $boardThickness):?><option value='<?=$boardThickness->id?>' <?PHP if($boardThickness->thickness == '18.0'): ?>selected<?PHP endif; ?>><?=$boardThickness->thickness?></option><?PHP endforeach; ?></select></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>symbol</label><select class='form-control textCenterSelect' id='boardSymbol"+lastPositionId+"' name='positions[position"+lastPositionId+"][boardSymbolId]'><?PHP foreach($boardsSymbols as $boardSymbol):?><option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option><?PHP endforeach; ?></select></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>ilość [szt.]</label><input name='positions[position"+lastPositionId+"][amount]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-2 smallerPadding'><label class='addingFormSmallLabel textShadow'>cięcie [mb]</label><input name='positions[position"+lastPositionId+"][cuttingMetters]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' required/></div><div class='col-sm-1'></div></div><div style='margin-top: 10px;' class='row' id='addNewEdgeBandingButton"+lastPositionId+"'><div class='col-sm-2'></div><div class='col-sm-8 noPadding'><div class='btn btn-default btn-block' onclick=\"addEdgeBanding('"+lastPositionId+"');\"><span class='glyphicon glyphicon-plus'></span> Dodaj oklejanie</div></div><div class='col-sm-2'></div></div></div></div></td></tr>");
}
</script>