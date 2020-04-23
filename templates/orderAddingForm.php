<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<form action="index.php?action=addNewOrder" name="orderAddingForm" id="orderAddingForm" method="post" autocomplete="off">
		<div class="text-center header">Dodawanie nowego zlecenia</div>
		<div class="tabContainer">
			<table class="table addingTab">
				<?PHP if (isset($_SESSION ['documentNumberError'])):?>
				<tr class="danger text-center"><td colspan="2"><?=$_SESSION ['documentNumberError']?></td></tr>
				<?PHP unset($_SESSION ['documentNumberError']); endif; ?>
				<tr>
					<td><label>Numer dokumentu:</label></td>
					<td>
						<div class="row noMargin">
							<div class="col-sm-3 noPadding">
								<select id='documentTypeSelect' class="form-control textCenterSelect" name="documentType" required onchange="checkIfDocumentNumberIsRequired();">
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
								<select class="form-control textCenterSelect" name="documentBranch" required>
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
					<td><label>Wybierz klienta:</td>
					<td>
						<select id='customerIdSelect' class="form-control textCenterSelect" name="customerId" required onchange="checkIfCustomerDataRequired();">
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
					<td><label>Nazwa klienta:</label></td>
					<td><input minlength="2"  id='customerName' class="form-control text-center text-capitalize" type="text" name="customerName" maxlength="35" <?PHP if (isset($_SESSION['customerName'])): ?>value="<?=$_SESSION['customerName']?>"<?PHP unset ($_SESSION['customerName']); endif; ?>/></td>
				</tr>
				<?PHP if(isset($_SESSION['customerPhoneError'])):?>
				<tr class="danger"><td colspan="2"><div class="error"><div><?=$_SESSION['customerPhoneError']?></div></div></td></tr>
				<?PHP unset($_SESSION['customerPhoneError']); endif; ?>
				<tr>
					<td><label>Numer tel. (9 cyfr):</label></td>
					<td>
						<input class="form-control text-center" type="tel" id='customerPhone' name="customerPhone" pattern="[1-9]{1}[0-9]{8}" minlength="9" maxlength="9" required <?PHP if (isset($_SESSION['customerPhone'])): ?>value="<?=$_SESSION['customerPhone']?>"<?PHP unset ($_SESSION['customerPhone']); endif; ?>/>
					</td>
				</tr>
				<?PHP if(isset($_SESSION['sawNumberError'])):?>
				<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['sawNumberError']?></div></td></tr>
				<?PHP unset($_SESSION['sawNumberError']); endif; ?>
				<tr>
					<td><label>Numer piły:</label></td>
					<td>
						<select class="form-control textCenterSelect" id="sawSelect" onchange="setPotentialOrderCompletionDate(); checkAmounts();" name="sawNumber" required>
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
					<td><label>Data złożenia zlecenia:</label></td>
					<td>
						<input class="form-control pointer text-center" type="date" name="admissionDate" required value="<?PHP if (isset($_SESSION['admissionDate'])): ?><?=$_SESSION['admissionDate']?><?PHP unset ($_SESSION['admissionDate']);?><?PHP else:?><?=date('Y-m-d')?><?PHP endif; ?>">
					</td>
				</tr>
				<?PHP if(isset($_SESSION['orderCompletionDateError'])):?>
				<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['orderCompletionDateError']?></div></td></tr>
				<?PHP unset($_SESSION['orderCompletionDateError']); endif; ?>
				<tr>
					<td><label>Termin realizacji zlecenia:</label></td>
					<td>
						<input class="form-control text-center pointer" id="orderCompletionDate" type="date" name="orderCompletionDate" onchange="checkAmounts();" required value="<?PHP if (isset($_SESSION['orderCompletionDate'])): ?><?=$_SESSION['orderCompletionDate']?><?PHP unset ($_SESSION['orderCompletionDate']);?><?PHP else:?><?=$potentialOrderCompletionDates[1]?><?PHP endif; ?>">
					</td>
				</tr>
				<tr>
					<td><label>Ilość płyt tego dnia:</label></td><td id="boardsAmountCell" class="textShadow"><label><div id='boardsAmount' style="font-style: italic;"></div></label></td>
				</tr>
				<tr>
					<td><label>Cięcie [mb]:</label></td><td id="cuttingMettersCell" class="textShadow"><label><div id='cuttingMetters' style="font-style: italic;"></div></label></td>
				</tr>
				<tr>
					<td><label>Oklejanie [mb]:</label></td><td id='edgeBandingMettersCell' class="textShadow"><label><div id='edgeBandingMetters'  style="font-style: italic;"></div></label></td>
				</tr>
				<?PHP if(isset($_SESSION['sellerIdError'])):?>
				<tr class="danger"><td colspan="2"><div class="error"><?=$_SESSION['sellerIdError']?></div></td></tr>
				<?PHP unset($_SESSION['sellerIdError']); endif; ?>
				<tr>
					<td><label>Sprzedawca:</label></td>
					<td>
						<select class="form-control textCenterSelect" name="sellerId" required>
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
					<td><label>Uwagi:</label></td>
					<td>
						<textarea id="orderCommentTextarea" class='form-control text-center' name="orderComment" rows="3" cols="25" maxlength="500" form="orderAddingForm"><?PHP if (isset($_SESSION['orderComment'])): ?><?=$_SESSION['orderComment']?><?PHP unset ($_SESSION['orderComment']);?><?PHP endif; ?></textarea>
					</td>
				</tr>
				<?PHP if (isset($_SESSION['positions'])): $edgeBandingNumber = 0; $positionNumber = 0;?>
				<script>positionsAmount=<?=count($_SESSION['positions']);?>; lastPositionId = positionsAmount;</script>
				<?PHP foreach($_SESSION['positions'] as $position): $positionNumber++;?>
				<tr id='position<?=$positionNumber?>'>
					<td style='border-color: transparent!important; padding:0px;' colspan='2'>
						<div class='contentContainer' style='padding: 5px;!important'>
							<div class='textShadow'>
								<div style='float:left;'>
									<label> Pozycja <span class='positions' id='p<?=$positionNumber?>'><?=$positionNumber?></span></label>
								</div>
								<div style='float: right;'>
									<span class='glyphicon glyphicon-remove pointer' onclick="removePosition('<?=$positionNumber?>');"></span>
								</div>
								<div style='clear: both;'></div>
							</div>
							<div class='container-fluid'>
								<div class='row text-center'>
								<div class='col-sm-1'></div>
									<div class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow textShadow'>rodzaj</label>
										<select class='form-control textCenterSelect' name='positions[position<?=$positionNumber?>][boardSignId]' id='boardSign<?=$positionNumber?>' onchange="setBoardSelectDefaultOptions('<?=$positionNumber?>');"><?PHP foreach($boardsSigns as $boardSign):?>
										<option value='<?=$boardSign->id?>' <?PHP if($boardSign->id == intval($position['boardSignId'])): ?>selected<?PHP endif; ?>><?=$boardSign->sign?></option>
										<?PHP endforeach; ?>
									</select>
									</div>
									<div class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>grubość</label>
										<select class='form-control textCenterSelect' id='boardThickness<?=$positionNumber?>' name='positions[position<?=$positionNumber?>][boardThicknessId]'>
										<?PHP foreach($boardsThickness as $boardThickness):?>
										<option value='<?=$boardThickness->id?>' <?PHP if($boardThickness->id == intval($position['boardThicknessId'])): ?>selected<?PHP endif; ?>><?=$boardThickness->thickness?></option>
										<?PHP endforeach; ?>
									</select>
									</div>
									<div class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>symbol</label>
										<select class='form-control textCenterSelect' id='boardSymbol<?=$positionNumber?>' name='positions[position<?=$positionNumber?>][boardSymbolId]' onchange="addOtherBoardSymbolIfNeeded('<?=$positionNumber?>');">
											<?PHP foreach($boardsSymbols as $boardSymbol):?>
											<option value='<?=$boardSymbol->id?>' 
											<?PHP if(isset($position['boardSymbolId']) && $boardSymbol->id == intval($position['boardSymbolId'])): ?>selected<?PHP endif; ?>><?=$boardSymbol->symbol?></option>
											<?PHP endforeach; ?>
											<option value='-1'>+ inny</option>
											<?PHP if(isset($position['otherBoardSymbol']) && $position['otherBoardSymbol'] != ""): ?>
											<option value="0" <?PHP if($position['boardSymbolId'] == '0'): ?> selected<?PHP endif; ?>><?=$position['otherBoardSymbol']?></option>
											<?PHP endif; ?>
										</select>
										<input type='hidden' id='otherBoardSymbol<?=$positionNumber?>' name='positions[position<?=$positionNumber?>][otherBoardSymbol]' value='<?PHP if(isset($position['otherBoardSymbol'])):?><?=$position['otherBoardSymbol']?><?PHP endif; ?>'/>
									</div>
									<div class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>ilość [szt.]</label>
										<input name='positions[position<?=$positionNumber?>][amount]' class='form-control text-center' value="<?=$position['amount']?>" type='number' min='0.5' max='1000' step='0.5' required/>
									</div>
									<div class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>cięcie [mb]</label>
										<input name='positions[position<?=$positionNumber?>][cuttingMetters]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' value="<?=$position['cuttingMetters']?>" required/>
									</div>
									<div class='col-sm-1'></div>
								</div>
								<?PHP if(isset($position['edgeBandTypesId'])): $edgeBandsAmount = count($position['edgeBandTypesId']); ?>
								<?PHP for( $j = 0; $j < $edgeBandsAmount; $j++): $edgeBandingNumber++;?>
								<div style='margin-top: 10px;' class='row text-center' id='e<?=$edgeBandingNumber?>'>
									<div class='col-sm-2'></div>
									<div style='padding-top: 5px;' class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>typ</label>
										<select name='positions[position<?=$positionNumber?>][edgeBandTypesId][]' class='form-control textCenterSelect'>
											<?PHP foreach($edgeBandTypes as $edgeBandType):?>
											<option value='<?=$edgeBandType->id?>' <?PHP if($edgeBandType->id == intval($position['edgeBandTypesId'][$j])): ?>selected<?PHP endif; ?>><?=$edgeBandType->type?></option>
											<?PHP endforeach; ?>
										</select>
									</div>
									<div style='padding-top: 5px;' class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>symbol</label>
										<select id='edgeBandingBoardSymbol<?=$edgeBandingNumber?>' class='form-control textCenterSelect' name='positions[position<?=$positionNumber?>][edgeBandingBoardSymbolsId][]'>
										<?PHP foreach($boardsSymbols as $boardSymbol):?>
										<option value='<?=$boardSymbol->id?>' <?PHP if($boardSymbol->id == intval($position['edgeBandingBoardSymbolsId'][$j])): ?>selected<?PHP endif; ?>><?=$boardSymbol->symbol?></option>
										<?PHP endforeach; ?>
									</select>
									</div>
									<div style='padding-top: 5px;' class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>naklejki</label>
										<select name='positions[position<?=$positionNumber?>][edgeBandsStickersId][]' class='form-control textCenterSelect'>
										<?PHP foreach($edgeBandStickerSymbols as $edgeBandStickerSymbol):?>
										<option value='<?=$edgeBandStickerSymbol->id?>' <?PHP if($edgeBandStickerSymbol->id == intval($position['edgeBandsStickersId'][$j])): ?>selected<?PHP endif; ?>><?=$edgeBandStickerSymbol->symbol?></option>
										<?PHP endforeach; ?>
										</select>
									</div>
									<div style='padding-top: 5px;' class='col-sm-2 smallerPadding'>
										<label class='addingFormSmallLabel textShadow'>oklejanie [mb]</label>
										<input name='positions[position<?=$positionNumber?>][edgeBandingMetters][]' class='form-control text-center' type='number' min='0.5' max='1000' step='0.5' value="<?=floatval($position['edgeBandingMetters'][$j])?>" required/>
									</div>
									<div class='col-sm-1' onclick="removeEdgeBanding('e<?=$edgeBandingNumber?>');">
										<label class='addingFormSmallLabel textShadow'>
											<span class='glyphicon glyphicon-remove pointer'></span>
										</label>
									</div>
									<div class='col-sm-1'></div>
								</div>
								<div style='margin-top: 10px;' class='row' id='e<?=$edgeBandingNumber?>comment'>
									<div class='col-sm-2'></div>
									<div class='col-sm-2 smallerPadding text-center'>
										<label class='addingFormSmallLabel textShadow'>uwagi:</label>
									</div>
									<div class='col-sm-6 noPadding'>
										<input class='form-control text-center' name='positions[position<?=$positionNumber?>][edgeBandComments][]' autocomplete='off' type='text' <?PHP if(isset($position['edgeBandComments'][$j])): ?>value="<?=$position['edgeBandComments'][$j]?>"<?PHP endif; ?>/>
									</div>
									<div class='col-sm-2'></div>
								</div>
							<?PHP endfor; ?>
							<?PHP endif; ?>	
								<div style='margin-top: 10px;' class='row' id='addNewEdgeBandingButton<?=$positionNumber?>'>
									<div class='col-sm-2'></div>
									<div class='col-sm-8 noPadding'>
										<div class='btn btn-default btn-block' onclick="addEdgeBanding('<?=$positionNumber?>');"><span class="glyphicon glyphicon-plus"></span> Dodaj oklejanie</div>
									</div>
									<div class='col-sm-2'></div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?PHP endforeach; ?>
				<script> edgeBandingLastId = <?=$edgeBandingNumber?>; </script>
				<?PHP unset($_SESSION['positions']); endif;?>
				<tr id="boardsInputs">
					<td colspan="2">
						<div class="btn btn-default btn-block" onclick="addBoardInputs();"><span class="glyphicon glyphicon-plus"></span> Dodaj pozycję</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button class="btn btn-default btn-block" type="button" onclick="sendOrderAddingForm();"><span class="glyphicon glyphicon-floppy-disk"></span> Zapisz zlecenie</buton>
						<button id="sendingButton" type="submit" style="display:none;">Dodaj zlecenie</buton>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>
<div class="modal fade" id="modal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
		<div class="modal-content">
				<div class="modal-body" id='modalBody'>
				</div>
			</div>
		</div>
	</div>
</div>