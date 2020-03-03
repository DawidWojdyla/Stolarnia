<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Dodawanie nowego zlecenia</h3>
<form action="index.php?action=addNewOrder" name="orderAddingForm" id="orderAddingForm" method="post" autocomplete="off">
	<table class="table table-condensed addingTab">
		<?PHP if (isset($_SESSION ['documentNumberError'])):?>
		<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['documentNumberError']?></td></tr>
		<?PHP unset($_SESSION ['documentNumberError']); endif; ?>
		<tr>
			<td>Numer dokumentu:</td>
			<td>
				<div class="row noMargin">
					<div class="col-sm-3 noPadding">
						<select id='documentTypeSelect' class="form-control" name="documentType" required onchange="checkIfDocumentNumberIsRequired();">
							<option value="PA"  
								<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'PA'):?>
								selected
								<?PHP unset ($_SESSION['documentType']); endif;?>>PA</option>
							<option value="FV"
								<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'FV'):?>
								selected
								<?PHP unset ($_SESSION['documentType']); endif;?>>FV</option>
							<option value="RV"
								<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == 'RV'):?>
								selected
								<?PHP unset ($_SESSION['documentType']); endif;?>>RV</option>
							<option value="4"
								<?PHP if (isset($_SESSION['documentType']) && $_SESSION['documentType'] == '4'):?>
								selected
								<?PHP unset ($_SESSION['documentType']); endif;?>>Brak</option>
						</select>
					</div>
					<div class="col-sm-6 noPadding">
						<input id='documentNumberInput' class="form-control text-center" type="text" pattern="\d*" maxlength="6" name="documentNumber" required value="<?PHP if (isset($_SESSION['documentNumber'])): ?><?=$_SESSION['documentNumber']?><?PHP unset ($_SESSION['documentNumber']); endif; ?>"/>
					</div>
					<div class="col-sm-3 noPadding">
						<select class="form-control" name="documentBranch" required>
							<option value="RZ"
							<?PHP if (isset($_SESSION['documentBranch']) && $_SESSION['documentBranch'] == 'RZ'):?>
								selected
							<?PHP unset ($_SESSION['documentBranch']); endif;?>>RZ</option>
							<option value="RA"
							<?PHP if (isset($_SESSION['documentBranch']) && $_SESSION['documentBranch'] == 'RA'):?>
								selected
							<?PHP unset ($_SESSION['documentBranch']); endif;?>>RA</option>
							<option value="KR"
							<?PHP if (isset($_SESSION['documentBranch']) && $_SESSION['documentBranch'] == 'KR'):?>
								selected
							<?PHP unset ($_SESSION['documentBranch']); endif;?>>KR</option>
							<option value="NS"
							<?PHP if (isset($_SESSION['documentBranch']) && $_SESSION['documentBranch'] == 'NS'):?>
								selected
							<?PHP unset ($_SESSION['documentBranch']); endif;?>>NS</option>
							<option value="5"
							<?PHP if (isset($_SESSION['documentBranch']) && $_SESSION['documentBranch'] == '5'):?>
								selected
							<?PHP unset ($_SESSION['documentBranch']); endif;?>></option>
						</select>
					</div>
				</div>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['customerIdError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><div><?=$_SESSION['customerIdError']?></div></div></td></tr>
		<?PHP unset($_SESSION['customerIdError']); endif; ?>
		<tr>
			<td>Wybierz klienta:</td>
			<td>
				<select id='customerIdSelect' class="form-control" name="customerId" required onchange="checkIfCustomerDataRequired();">
					<option value='1'>Inny (dane poniżej)</option>
					<?PHP foreach ($customerList as $customer):?>
					<option  value="<?=$customer->id?>" 
						<?PHP if (isset($_SESSION['customerId']) && $_SESSION['customerId'] == $customer->id):?>
						selected
						<?PHP unset ($_SESSION['customerId']); endif;?>
						><?=$customer->surname?> <?=$customer->name?> (<?=$customer->phone?>)</option>
					<?PHP endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Nazwa klienta:</td>
			<td><input minlength="2"  id='customerName' class="form-control" type="text" name="customerName" maxlength="25" <?PHP if (isset($_SESSION['customerName'])): ?>value="<?=$_SESSION['customerName']?>"<?PHP unset ($_SESSION['customerName']); endif; ?>/></td>
		</tr>
		<?PHP if(isset($_SESSION['customerPhoneError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><div><?=$_SESSION['customerPhoneError']?></div></div></td></tr>
		<?PHP unset($_SESSION['customerPhoneError']); endif; ?>
		<tr>
			<td>Numer tel. (9 cyfr):</td>
			<td>
				<input class="form-control" type="tel" id='customerPhone' name="customerPhone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" required <?PHP if (isset($_SESSION['customerPhone'])): ?>value="<?=$_SESSION['customerPhone']?>"<?PHP unset ($_SESSION['customerPhone']); endif; ?>/>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['sawNumberError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['sawNumberError']?></div></td></tr>
		<?PHP unset($_SESSION['sawNumberError']); endif; ?>
		<tr>
			<td>Numer piły:</td>
			<td>
				<select class="form-control" id="sawSelect" onchange="setPotentialOrderCompletionDate(); checkAmounts();" name="sawNumber" required>
					<option value="1"
						<?PHP if (isset($_SESSION['sawNumber']) && $_SESSION['sawNumber'] == "1"):?>
						selected
						<?PHP unset ($_SESSION['sawNumber']); endif;?>>1
					</option>
					<option value="2"
						<?PHP if (isset($_SESSION['sawNumber']) && $_SESSION['sawNumber'] == "2"):?>
						selected
						<?PHP unset ($_SESSION['sawNumber']); endif;?>>2
					</option>
				</select>
			</td>
		</tr>
		<?PHP if(isset($_SESSION['admissionDateError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['admissionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['admissionDateError']); endif; ?>
		<tr>
			<td>
				Data złożenia zlecenia:
			</td>
			<td>
				<input class="form-control" type="date" name="admissionDate" required value="<?PHP if (isset($_SESSION['admissionDate'])): ?><?=$_SESSION['admissionDate']?><?PHP unset ($_SESSION['admissionDate']);?><?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
			</td>
		</tr>
		<?PHP if(isset($_SESSION['orderCompletionDateError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['orderCompletionDateError']?></div></td></tr>
		<?PHP unset($_SESSION['orderCompletionDateError']); endif; ?>
		<tr>
			<td>
				Data realizacji zlecenia:
			</td>
			<td>
				<input class="form-control" id="orderCompletionDate" type="date" name="orderCompletionDate" onchange="checkAmounts();" required value="<?PHP if (isset($_SESSION['orderCompletionDate'])): ?><?=$_SESSION['orderCompletionDate']?><?PHP unset ($_SESSION['orderCompletionDate']);?><?PHP else:?><?=$potentialOrderCompletionDates[1]?><?PHP endif; ?>">
			</td>
		</tr>
		<tr>
			<td>Ilość płyt tego dnia:</td><td><div id='boardsAmount' style="font-style: italic;"></div></td>
		</tr>
		<tr>
			<td>Cięcie [mb]:</td><td><div id='cuttingMetters' style="font-style: italic;"></div></td>
		</tr>
		<tr>
			<td>Oklejanie [mb]:</td><td><div id='edgeBandingMetters'  style="font-style: italic;"></div></td>
		</tr>
		<?PHP if(isset($_SESSION['sellerIdError'])):?>
		<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['sellerIdError']?></div></td></tr>
		<?PHP unset($_SESSION['sellerIdError']); endif; ?>
		<tr>
			<td>Sprzedawca:</td>
			<td>
				<select class="form-control" name="sellerId" required>
					<option value=""></option>
					<?PHP foreach ($sellers as $seller): ?>
						<option value="<?=$seller->id?>"
						<?PHP if (isset($_SESSION['sellerId']) && $_SESSION['sellerId'] == $seller->id):?>
						selected
						<?PHP unset ($_SESSION['sellerId']); endif;?>
						><?=$seller->name?></option>
					<?PHP endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Uwagi:
			</td>
			<td>
				<textarea id="orderCommentTextarea" class='form-control' name="orderComment" rows="3" cols="25" maxlength="250" form="orderAddingForm"><?PHP if (isset($_SESSION['orderComment'])): ?><?=$_SESSION['orderComment']?><?PHP unset ($_SESSION['orderComment']);?><?PHP endif; ?></textarea>
			</td>
		</tr>
		<?PHP if (isset($_SESSION['positions'])): $edgeBandingNumber = 0; $positionNumber = 0;?>
		<script>positionsAmount =<?=count($_SESSION['positions']);?>; lastPositionId = positionsAmount;</script>
		<?PHP foreach($_SESSION['positions'] as $position): $positionNumber++;?>
		<tr id='position<?=$positionNumber?>'>
			<td>
				<div onclick="removePosition('<?=$positionNumber?>');" class='inline pointer'>
					<span class='glyphicon glyphicon-remove'></span>
				</div>
				<div class='positions'  id='p<?=$positionNumber?>'> Pozycja <?=$positionNumber?>)</div>
			</td>
			<td>
				<table class='positionTable'>
					<tr>
						<td colspan='4'>
							<select class='form-control' name='positions[position<?=$positionNumber?>][boardSignId]' id='boardSign<?=$positionNumber?>' onchange="checkIfNextSelectsAreDisabled('<?=$positionNumber?>');"><?PHP foreach($boardsSigns as $boardSign):?>
								<option value='<?=$boardSign->id?>' <?PHP if($boardSign->id == intval($position['boardSignId'])): ?>selected<?PHP endif; ?>><?=$boardSign->sign?></option>
								<?PHP endforeach; ?>
							</select>
							<select class='form-control' name='positions[position<?=$positionNumber?>][boardThicknessId]'>
								<?PHP foreach($boardsThickness as $boardThickness):?>
								<option value='<?=$boardThickness->id?>' <?PHP if($boardThickness->id == intval($position['boardThicknessId'])): ?>selected<?PHP endif; ?>><?=$boardThickness->thickness?></option>
								<?PHP endforeach; ?>
							</select>
							<select class='form-control' id='boardSymbol<?=$positionNumber?>' name='positions[position<?=$positionNumber?>][boardSymbolId]'>
								<?PHP foreach($boardsSymbols as $boardSymbol):?>
								<option value='<?=$boardSymbol->id?>' 
								<?PHP if(isset($position['boardSymbolId']) && $boardSymbol->id == intval($position['boardSymbolId'])): ?>selected<?PHP endif; ?>><?=$boardSymbol->symbol?></option>
								<?PHP endforeach; ?>
							</select>
							<select class='form-control' id='boardStructure<?=$positionNumber?>' name='positions[position<?=$positionNumber?>][boardStructureId]'><?PHP foreach($boardsStructures as $boardStructure):?><option value='<?=$boardStructure->id?>' 
								<?PHP if(isset($position['boardStructureId']) && $boardStructure->id == intval($position['boardStructureId'])): ?>selected<?PHP endif; ?>><?=$boardStructure->structure?></option>
								<?PHP endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>ilość:</td>
						<td>
							<input name='positions[position<?=$positionNumber?>][amount]' class='szt' type='number' min='0.5' max='1000' step='0.5' value="<?=$position['amount']?>" required/> szt.
						</td>
						<td colspan='2'></td>
					</tr>
					<tr>
						<td>cięcie:</td>
						<td>
							<input name='positions[position<?=$positionNumber?>][cuttingMetters]' class='szt' type='number' min='0.5' max='10000' step='0.5' value="<?=$position['cuttingMetters']?>" required/> m
						</td>
						<td colspan='2'></td>
					</tr>
					<?PHP if(isset($position['edgeBandTypesId'])): 
						$edgeBandsAmount = count($position['edgeBandTypesId']);
					?>
						<?PHP for( $j = 0; $j < $edgeBandsAmount; $j++): $edgeBandingNumber++;?>
					<tr id='e<?=$edgeBandingNumber?>'>
						<td colspan='4' style='padding-top: 10px;'><div onclick="removeEdgeBanding('e<?=$edgeBandingNumber?>');" class='inline pointer'><span class='glyphicon glyphicon-remove'></span></div><select name='positions[position<?=$positionNumber?>][edgeBandTypesId][]' class='form-control'>
								<?PHP foreach($edgeBandTypes as $edgeBandType):?>
								<option value='<?=$edgeBandType->id?>' <?PHP if($edgeBandType->id == intval($position['edgeBandTypesId'][$j])): ?>selected<?PHP endif; ?>><?=$edgeBandType->type?></option>
								<?PHP endforeach; ?>
							</select><select id='edgeBandingBoardSymbol<?=$edgeBandingNumber?>' class='form-control' name='positions[position<?=$positionNumber?>][edgeBandingBoardSymbolsId][]'>
								<?PHP foreach($boardsSymbols as $boardSymbol):?>
								<option value='<?=$boardSymbol->id?>' <?PHP if($boardSymbol->id == intval($position['edgeBandingBoardSymbolsId'][$j])): ?>selected<?PHP endif; ?>><?=$boardSymbol->symbol?></option>
								<?PHP endforeach; ?>
							</select>(<select name='positions[position<?=$positionNumber?>][edgeBandsStickersId][]' class='form-control'>
								<?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?>
								<option value='<?=$edgeBandStickerSymbol->id?>' <?PHP if($edgeBandStickerSymbol->id == intval($position['edgeBandsStickersId'][$j])): ?>selected<?PHP endif; ?>><?=$edgeBandStickerSymbol->symbol?></option>
								<?PHP endforeach; ?>
							</select>)<div class='inline-block'>-><input name='positions[position<?=$positionNumber?>][edgeBandingMetters][]' class='szt' type='number' min='0.5' max='10000' step='0.5' required value="<?=floatval($position['edgeBandingMetters'][$j])?>"/> m</div></td>
					</tr>
					<tr id='e<?=$edgeBandingNumber?>comment'>
						<td colspan='4'>
							uwagi: <input class='form-control edgeBandComment' name='positions[position<?=$positionNumber?>][edgeBandComments][]' type='text' autocomplete="off" <?PHP if(isset($position['edgeBandComments'][$j])): ?>value="<?=$position['edgeBandComments'][$j]?>"<?PHP endif; ?>/>
						</td>
					</tr>
					<?PHP endfor; ?>
					<?PHP endif; ?>
					<tr id='addNewEdgeBandingButton<?=$positionNumber?>'>
						<td colspan='4'>
							<div class='btn btn-default btn-block' style='max-width: 200px; margin:auto; margin-top: 10px;' onclick="addEdgeBanding('<?=$positionNumber?>');">Dodaj oklejanie</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
			<?PHP endforeach; ?>
		<script>
			edgeBandingLastId = <?=$edgeBandingNumber?>;
		</script>
		<?PHP unset($_SESSION['positions']); endif;?>
		<tr id="boardsInputs">
			<td colspan="2">
				<div class="btn btn-default btn-block" onclick="addBoardInputs();">Dodaj pozycję</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="btn btn-default btn-block" type="button" onclick="sendOrderAddingForm();">Dodaj zlecenie</buton>
				<button id="sendingButton" type="submit" style="display:none;">Dodaj zlecenie</buton>
			</td>
		</tr>
	</table>
</form>