<script>
var lastPositionId = 0;
var positionsAmount = 0;
var edgeBandingLastId = 0;

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
		//document.getElementById('customerPhone').value = "";
		//document.getElementById('customerName').value = "";
		document.getElementById('customerPhone').disabled = true;
		document.getElementById('customerName').disabled = true;
	}
}

function checkIfNextSelectsAreDisabled(id){
	if(document.getElementById('boardSign'+id).value == '2'){
		document.getElementById('boardSymbol'+id).value = '1';
		document.getElementById('boardStructure'+id).value = '1';
		document.getElementById('boardSymbol'+id).disabled = true;
		document.getElementById('boardStructure'+id).disabled = true;
	}
	else{
		document.getElementById('boardSymbol'+id).disabled = false;
		document.getElementById('boardStructure'+id).disabled = false;
	}
}

function sendOrderAddingForm(){
	if(positionsAmount){
		document.getElementById('sendingButton').click();
	}
	else if (!document.contains(document.getElementById("noPositionInfo"))){
		document.getElementById('boardsInputs').insertAdjacentHTML("beforebegin", "<tr id='noPositionInfo' class='danger text-center'><td colspan='2'>Najpierw dodaj przynajmniej jedną pozycję do zamówienia!</td></tr>");
	}
}
function removePosition(id){
	var idNum = parseInt(id);
	document.getElementById('position'+id).remove();
	positionsAmount--;
	
	var positions = document.getElementsByClassName("positions");
	for (var i = 0; i < positions.length; i++) {
		positions[i].innerHTML = "Pozycja "+parseInt(i+1) + ")";
	}
}

function removeEdgeBanding(id){
	document.getElementById(id).remove();
	document.getElementById(id+'comment').remove();
}



function addEdgeBanding(position){
	edgeBandingLastId++;
	document.getElementById('addNewEdgeBandingButton'+position).insertAdjacentHTML("beforebegin","<tr id='e"+edgeBandingLastId+"'><td colspan='4' style='padding-top: 10px;' ><div onclick=\"removeEdgeBanding('e"+edgeBandingLastId+"');\" class='inline pointer'><span class='glyphicon glyphicon-remove'></span></div><select name='positions[position"+lastPositionId+"][edgeBandTypesId][]' class='inputHeight'><?PHP foreach($edgeBandTypes as $edgeBandType):?><option value='<?=$edgeBandType->id?>'><?=$edgeBandType->type?></option><?PHP endforeach; ?></select><select id='edgeBandingBoardSymbol"+position+"' class='inputHeight pos"+position+"' name='positions[position"+lastPositionId+"][edgeBandingBoardSymbolsId][]'><?PHP foreach($boardsSymbols as $boardSymbol):?><option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option><?PHP endforeach; ?></select>(<select name='positions[position"+lastPositionId+"][edgeBandsStickersId][]' class='inputHeight'><?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?><option value='<?=$edgeBandStickerSymbol->id?>'><?=$edgeBandStickerSymbol->symbol?></option><?PHP endforeach; ?></select>)<div class='inline-block'>-><input name='positions[position"+lastPositionId+"][edgeBandingMetters][]' class='szt' type='number' min='0.5' max='1000' step='0.5' required/> m</div></td></tr><tr id='e"+edgeBandingLastId+"comment'><td colspan='4'>uwagi: <input class='inputHeight edgeBandComment' name='positions[position"+lastPositionId+"][edgeBandComments][]' autocomplete='off' type='text'/></td></tr>");

	if(document.getElementsByClassName('pos'+position).length == 1){
		document.getElementById('edgeBandingBoardSymbol'+position).value = document.getElementById('boardSymbol'+position).value;
	}
}

function addBoardInputs(){
	
	if (document.contains(document.getElementById("noPositionInfo"))){
		document.getElementById("noPositionInfo").remove();
	}
	lastPositionId++;
	positionsAmount++;
	
	document.getElementById('boardsInputs').insertAdjacentHTML("beforebegin", "<tr id='position"+lastPositionId+"'><td><div onclick=\"removePosition('"+lastPositionId+"');\" class='inline pointer'><span class='glyphicon glyphicon-remove'></span></div><div class='positions'  id='p"+lastPositionId+"'> Pozycja "+positionsAmount+")</div></td><td><table class='positionTable'><tr><td colspan='4'><select class='inputHeight' name='positions[position"+lastPositionId+"][boardSignId]' id='boardSign"+lastPositionId+"' onchange=\"checkIfNextSelectsAreDisabled('"+lastPositionId+"');\"><?PHP foreach($boardsSigns as $boardSign):?><option value='<?=$boardSign->id?>' <?PHP if($boardSign->sign == 'L'): ?>selected<?PHP endif; ?>><?=$boardSign->sign?></option><?PHP endforeach; ?></select><select class='inputHeight' name='positions[position"+lastPositionId+"][boardThicknessId]'><?PHP foreach($boardsThickness as $boardThickness):?><option value='<?=$boardThickness->id?>' <?PHP if($boardThickness->thickness == '18.0'): ?>selected<?PHP endif; ?>><?=$boardThickness->thickness?></option><?PHP endforeach; ?></select><select class='inputHeight' id='boardSymbol"+lastPositionId+"' name='positions[position"+lastPositionId+"][boardSymbolId]'><?PHP foreach($boardsSymbols as $boardSymbol):?><option value='<?=$boardSymbol->id?>'><?=$boardSymbol->symbol?></option><?PHP endforeach; ?></select><select class='inputHeight' id='boardStructure"+lastPositionId+"' name='positions[position"+lastPositionId+"][boardStructureId]'><?PHP foreach($boardsStructures as $boardStructure):?><option value='<?=$boardStructure->id?>'><?=$boardStructure->structure?></option><?PHP endforeach; ?></select></td></tr><tr><td>ilość:</td><td><input name='positions[position"+lastPositionId+"][amount]' class='szt' type='number' min='0.5' max='1000' step='0.5' required/> szt.</td><td colspan='2'></td></tr><tr><td>cięcie:</td><td><input name='positions[position"+lastPositionId+"][cuttingMetters]' class='szt' type='number' min='0.5' max='1000' step='0.5' required/> m</td><td colspan='2'></td></tr ><tr id='addNewEdgeBandingButton"+lastPositionId+"'><td colspan='3'><div class='btn btn-default btn-block' style='max-width: 210px; margin-top: 10px;' onclick=\"addEdgeBanding('"+lastPositionId+"');\">Dodaj oklejanie</div></td><td></td></tr></table></td></tr>");
}
</script>