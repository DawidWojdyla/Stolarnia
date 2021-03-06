<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Szczegóły zlecenia</div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr>
				<td style="border-top: none;"><label>Numer dokumentu:</label></td><td  style="border-top: none;"><div id="documentNumber"><?=$documentNumber?></div></td>
			</tr>
			<tr>
				<td><label>Klient:</label></td>
				<td>
					<div id="customerId" style="display: none;"><?=$customerId?></div>
					<div  id="customerName"><?=$customerName?></div>
				</td>
			</tr>
			<tr>
				<td><label>Numer telefonu:</label></td>
				<td><div id='phone'><?=$phone?></div></td>
			</tr>
			<tr>
				<td><label>Numer piły:</label></td>
				<td><div id='sawNumber'><?=$sawNumber?></div></td>
			</tr>
			<tr>
				<td><label>Data złożenia zamówienia:</label></td>
				<td><div id="admissionDate"><?=$admissionDate?></div></td>
			</tr>
			<tr>
				<td><label>Data realizacji zamówienia:</label></td>
				<td><div id="completionDate"><?=$completionDate?></div></td>
			</tr>
			<tr>
				<td><label>Sprzedawca:</label></td>
				<td><div  id="sellerName"><?=$sellerName?></div></td>
			</tr>
			<tr>
				<td><label>Uwagi:</label></td>
				<td><div id="comment"><?PHP if($comment != ""): ?><?=$comment?><?PHP else: ?>Brak<?PHP endif; ?></div></td>
			</tr>
			<?PHP $pos = 0; $lastBoardId = 0; ?>
			<?PHP foreach ($order as $position):?>
			<?PHP if($lastBoardId != $position -> boardId): ?>
			<?PHP $lastBoardId = $position -> boardId; ?>
			<?PHP $pos++; ?>
			<tr class="board<?=$position -> boardId?>" id="<?=$position -> boardId?>">
				<td class="text-center" style="vertical-align: middle; border-top: 2px solid white;">
					<h3 class="noPadding noMargin" style="float: left; margin-top: 0px;">
						<span style="margin-left: 20px;" id="boardSign<?=$position -> boardId?>"><?=$position -> boardSign?></span><span id="boardThickness<?=$position -> boardId?>"><?=($position -> thickness)+0?></span><?PHP if($position -> boardSymbol != '' || $position -> otherSymbol): ?> - <?PHP endif; ?><span id="boardSymbol<?=$position -> boardId?>"><?=$position -> boardSymbol?></span><span class="text-uppercase" id="otherBoardSymbol<?=$position -> boardId?>"><?=$position -> otherSymbol?></span>
					</h3>
					<div class="noPadding noMargin" style="float: right;">
					</div>
					<div style="clear: both;"></div>
				</td>
				<td style="border-top: 2px solid white; padding: 0px;">
					<div style="padding: 5px;">
						<label>ilość:</label> <span id="amount<?=$position -> boardId?>"><?=($position -> amount)+0?></span> [szt.]
					</div>
					<div style="border-top: 1px solid #aaa; padding: 6px;">
						<div style="float: left;"><label>cięcie:</label> <span id="cuttingMetters<?=$position -> boardId?>"><?=($position -> cutting_metters)+0?></span> [mb]</div>
						<?PHP if($position -> cuttingDate): ?><div class="text-right noPadding"><span style="font-size: 16px; cursor: pointer;" class="glyphicon glyphicon-scissors" data-toggle="popover" data-trigger="focus" tabindex="0" data-placement="left" data-content="<table class='table table-condensed'><tr><th style='border-top:none;' colspan='4' class='text-center'><span style='font-size: 20px;' class='glyphicon glyphicon-scissors'></span></th></tr><tr><td style='height: 100%; vertical-align: middle;'><span class='glyphicon glyphicon-user'></span></td><td colspan='3'><?=$position -> cuttingWorkersNames?></td></tr><tr><td><span class='glyphicon glyphicon-calendar'></span></td><td colspan='3'><?=$position -> cuttingDate?> (<?=$position -> cuttingTime?>)</td></tr><?PHP if($position -> cuttingComment): ?><tr><td><span class='glyphicon glyphicon-edit'></span></td><td colspan='3'><?=$position -> cuttingComment?><?PHP endif; ?></td></tr></table>"></span></div><?PHP endif; ?>
					</div>
				</td>
			</tr>
			<?PHP endif; ?>
			<?PHP if($position -> edgeBandType): ?>
			<tr>
				<td style="border-top: none; border-bottom: none;"></td>
				<td>
					<div style="float: left;">
						<span><?=$position -> edgeBandType?></span>
						<span><?=$position -> edgeBandSymbol?></span>
						(<span><?=$position -> stickerSymbol?></span>)
						 -> <span><?=$position -> wzMetters + 0?></span> [mb]
					</div>
					<?PHP if($position -> edgeBandingDate): ?><div class="text-right noPadding"><span style="font-size: 16px; cursor: pointer;" class='glyphicon glyphicon-cd' data-toggle="popover" data-trigger="focus" tabindex="0" data-placement="left" data-content="<table class='table table-condensed'><tr><th style='border-top:none;' colspan='4' class='text-center'><span style='font-size: 20px;' class='glyphicon glyphicon-cd'></span></th></tr><tr><td style='height: 100%; vertical-align: middle;'><span class='glyphicon glyphicon-user'></span></td><td colspan='3'><?=$position -> eBWorkers?></td></tr><tr><td><span class='glyphicon glyphicon-calendar'></span></td><td colspan='3'><?=$position -> edgeBandingDate?> (<?=$position -> edgeBandingTime?>)</td></tr><?PHP if($position -> edgeBandingComment): ?><tr><td><span class='glyphicon glyphicon-edit'></span></td><td colspan='3'><?=$position -> edgeBandingComment?><?PHP endif; ?></td></tr></table>"></span></div><?PHP endif; ?>
					<div style="clear: both;"></div>
					<div id="eBComment<?=$position -> edgeBandingId?>" style="font-style: italic;"><?PHP if($position -> edgeBandComment): ?><?=$position -> edgeBandComment?><?PHP endif; ?></div>
				</td>
			</tr>
			<?PHP endif; ?>
			<?PHP endforeach; ?>																						
		</table>
	</div>
</div>
<div class="modal fade" id="updatingOrderDataModal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='updatingOrderDataModalBody'>
				</div>
			</div>
		</div>
	</div>
</div>