<?php if(!isset($this)) die(); ?>
<h3 class="text-center">Edycja zlecenia</h3>
<table class="table addingTab">
	<tr>
		<td style="border-top: none;">Numer dokumentu:</td><td  style="border-top: none;"><div style="float:left;" id="documentNumber"><?=$documentNumber?></div><div class="text-right noPadding"><span onclick="updateDocumentNumber();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Klient:</td>
		<td>
			<div id="customerId" style="display: none;"><?=$customerId?></div>
			<div  id="customerName" style="float: left;"><?=$customerName?></div>
			<div class="text-right noPadding">
				<span onclick="updateCustomerData();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin">
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td>Numer telefonu:</td>
		<td><div id='phone' style="float: left;"><?=$phone?></div><div class="text-right noPadding"><span onclick="updateCustomerData();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Numer piły:</td>
		<td><div id='sawNumber' style="float: left;"><?=$sawNumber?></div><div class="text-right noPadding"><span onclick="updateSawNumber();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Data złożenia zamówienia:</td><td><div style="float: left;" id="admissionDate"><?=$admissionDate?></div><div class="text-right noPadding"><span onclick="updateAdmissionDate();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Data realizacji zamówienia:</td><td><div id="completionDate" style="float: left;"><?=$completionDate?></div><div class="text-right noPadding"><span onclick="updateCompletionDate();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Sprzedawca:</td><td><div  id="sellerName" style="float: left;" ><?=$sellerName?></div><div id="sellerId" style="display: none;"><?=$sellerId?></div><div class="text-right noPadding"><span onclick="updateSeller();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<tr>
		<td>Uwagi:</td><td><div id="comment" style="float: left;"><?PHP if($comment != ""): ?><?=$comment?><?PHP else: ?>Brak<?PHP endif; ?></div><div class="text-right noPadding"><span onclick="updateComment();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
	</tr>
	<?PHP $pos = 0; $lastBoardId = 0; ?>
	<?PHP foreach ($order as $position):?>
	<?PHP if($lastBoardId != $position -> boardId): ?>
	<?PHP $lastBoardId = $position -> boardId; ?>
	<?PHP $pos++; ?>
	<tr class="board<?=$position -> boardId?>" id="<?=$position -> boardId?>">
		<td class="text-center" style="vertical-align: middle; border-top: 2px solid white;">
			<h3 class="noPadding noMargin" style="float: left; margin-top: 0px;">
				<span id="boardSignId<?=$position -> boardId?>" style="display: none;"><?=$position -> boardSignId?></span>
				<span id="boardThicknessId<?=$position -> boardId?>" style="display: none;"><?=$position -> boardThicknessId?></span>
				<span id="boardSymbolId<?=$position -> boardId?>" style="display: none;"><?=$position -> boardSymbolId?></span>
				<span id="boardStructureId<?=$position -> boardId?>" style="display: none;"><?=$position -> boardStructureId?></span>
				<span style="margin-left: 20px;" id="boardSign<?=$position -> boardId?>"><?=$position -> boardSign?></span><span id="boardThickness<?=$position -> boardId?>"><?=($position -> thickness)+0?></span> - <span id="boardSymbol<?=$position -> boardId?>"><?=$position -> boardSymbol?></span> <span  id="boardStructure<?=$position -> boardId?>"><?=$position -> structure?></span>
			</h3>
			<div class="noPadding noMargin" style="float: right;">
				<span onclick="removePosition('<?=$position -> boardId?>');" style="cursor: pointer; font-size: 10px; padding:4px 8px;" class='glyphicon glyphicon-remove btn btn-default'></span>
				<span onclick="updateBoard('<?=$position -> boardId?>');" style="cursor: pointer; font-size: 10px; padding:4px 8px;" class="glyphicon glyphicon-pencil top btn btn-default"></span>
			</div>
			<div style="clear: both;"></div>
		</td>
		<td style="border-top: 2px solid white; padding-bottom: 0px; border-bottom: 1px solid transparent!important;">
			<div style="padding-bottom: 6px;">
				<div style="float: left;">ilość: <span id="amount<?=$position -> boardId?>"><?=($position -> amount)+0?></span> [szt.]</div>
				<div class="text-right noPadding"><span onclick="updateBoardAmount('<?=$position -> boardId?>');" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div>
			</div>
			<div style="border-top: 1px solid #ddd; padding-top: 7px; padding-bottom: 6px;">
				<div style="float: left;">cięcie: <span id="cuttingMetters<?=$position -> boardId?>"><?=($position -> cutting_metters)+0?></span> [mb]</div>
				<div class="text-right noPadding"><span onclick="updateCuttingMetters('<?=$position -> boardId?>');" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div>
			</div>
			<div style='margin: 0px auto 3px auto; padding:4px 8px;' class='btn btn-default btn-block' onclick="addNewEdgeBanding('<?=$position -> boardId?>');"><span style="font-size: 10px;" class="glyphicon glyphicon-plus"></span> Oklejanie</div>
		</td>
	</tr>
	<?PHP endif; ?>
	<?PHP if($position -> edgeBandType): ?>
	<tr class="board<?=$position -> boardId?>" id="eB<?=$position -> edgeBandingId?>">
		<td style="border-top: none; border-bottom: none;"></td>
		<td>
			<div style="float: left;">
				<span id="eBTypeId<?=$position -> edgeBandingId?>" style="display: none;"><?=$position -> edgeBandTypeId?></span>
				<span id="eBType<?=$position -> edgeBandingId?>"><?=$position -> edgeBandType?></span>
				<span id="eBSymbolId<?=$position -> edgeBandingId?>" style="display: none;"><?=$position -> edgeBandSymbolId?></span>
				<span id="eBSymbol<?=$position -> edgeBandingId?>"><?=$position -> edgeBandSymbol?></span>
				<span id="stickerSymbolId<?=$position -> edgeBandingId?>" style="display: none;"><?=$position -> stickerSymbolId?></span>
				(<span id="stickerSymbol<?=$position -> edgeBandingId?>"><?=$position -> stickerSymbol?></span>)
				 -> <span id="eBWzMetters<?=$position -> edgeBandingId?>" ><?=$position -> wzMetters + 0?></span> [mb]
			</div>
			<div class="text-right noPadding">
				<span style="font-size: 10px; cursor: pointer; padding:4px 8px;" onclick="removeEdgeBanding('<?=$position -> edgeBandingId?>')" class='glyphicon glyphicon-remove btn btn-default noMargin'>
				</span>
				<span onclick="updateEdgeBanding('<?=$position -> edgeBandingId?>');" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin">
				</span>
			</div>
			<div id="eBComment<?=$position -> edgeBandingId?>" style="font-style: italic;"><?PHP if($position -> edgeBandComment): ?><?=$position -> edgeBandComment?><?PHP endif; ?></div>
		</td>
	</tr>
	<?PHP endif; ?>
	<?PHP endforeach; ?>
	<tr id="newPositionAddingButton">
		<td style="border-top: none;" colspan="2">
			<div class="btn btn-default btn-block" onclick="addNewBoard();"><span style="font-size: 10px;" class="glyphicon glyphicon-plus"></span> Dodaj pozycję</div>
		</td>
	</tr>
</table>
<div class="modal fade" id="updatingOrderDataModal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body" id='updatingOrderDataModalBody'>
			</div>
		</div>
	</div>
</div>