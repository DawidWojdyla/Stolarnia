<?php if(!isset($this)) die(); ?>
<div class="contentContainer noMargin">
	<div class="header text-center"><span style='float: right;'><?=$sawNumber?></span>
		<div><?=$documentNumber?></div><div><?=$customerName?></div><div style="font-size: 16px; margin-top: -3px;">tel. <?=$phone?></div>
	</div>
	<div style="border-top: 1px solid black; padding-left: 3px; padding-right: 3px; padding-top: 2px;" class="tabContainer">
		<?PHP if($comment != ""): ?>
		<div style="padding:3px; margin-bottom: -5px;" class="tabContainer text-center">
			<div class="container noPadding italic"><span class="glyphicon glyphicon-paperclip"></span> <?=$comment?></div>
		</div>
		<?PHP endif;?>
		<?PHP if(empty($boards)): ?><h3 class='text-center'>Brak pociętych płyt do oklejenia</h3><?PHP endif; ?>
		<?PHP $lastBoardId = 0; ?>
		<table>
		<?PHP foreach($boards as $board): ?>
			<?PHP if($lastBoardId != $board -> boardId): ?>
			<?PHP $lastBoardId = $board -> boardId; ?>
		</table>
		<div class='btn btn-default btn-block boardTitle'' id='board<?=$board -> boardId?>' onclick="showBoardDetails('<?=$board -> boardId?>')"><?=$board -> boardSign?><?=($board -> thickness + 0)?> - <?=$board -> boardSymbol?><?PHP if($board -> structure != '---' ): ?><?=$board -> structure?><?PHP endif; ?></div>
		<div class="text-right" id="info<?=$board -> boardId?>" style="display:none; font-size: 18px; margin-top: -5px; margin-bottom: -5px;"><span class="glyphicon glyphicon-scissors pointer" data-toggle="popover" data-trigger="focus" tabindex="0" data-placement="left" data-content="<table class='table table-condensed'><tr><th style='border-top:none;' colspan='4' class='text-center'><span style='font-size: 20px;' class='glyphicon glyphicon-scissors'></span></th></tr><tr><td><span class='glyphicon glyphicon-tasks'></span></td><td colspan='3'>ilość: <?=($board -> amount+0)?> szt. / cięcie: <?=($board -> cutting_metters+0)?> mb</td></tr><tr><td style='height: 100%; vertical-align: middle;'><span class='glyphicon glyphicon-user'></span></td><td colspan='3'><?=$board -> cuttingWorkers?></td></tr><tr><td><span class='glyphicon glyphicon-calendar'></span></td><td colspan='3'><?=$board -> cuttingDate?> (<?=$board -> cuttingTime?>)</td></tr><?PHP if($board -> cuttingComment): ?><tr><td><span class='glyphicon glyphicon-edit'></span></td><td colspan='3'><?=$board -> cuttingComment?><?PHP endif; ?></td></tr></table>"></span></div>
		<table style="display:none;" id="table<?=$board -> boardId?>" class="table ebtable">
			<th class='text-center'>obrzeże</th><th class='text-center'>naklejki</th><th class='text-center'><div>wz</div><div>[mb]</div></th><th class='text-center'><div>maszyna</div><div>[mb]</div></th>
			<?PHP endif; ?>	
			<tr class="pointer ebtr"  id="<?=$board -> edgeBandingId?>" onclick="showEdgeBandingModal(<?=$board -> edgeBandingId?>);">
				<td class='text-center' style="border-top: 1.5px solid white;"><div><?=$board -> edgeBandType?><?PHP if($board -> edgeBandSymbol != ''): ?> <?=$board -> edgeBandSymbol?><?PHP endif; ?></td>
				<td class='text-center' style="border-top: 1.5px solid white;"><?=$board -> stickerSymbol?></td>
				<td class='text-center' style="border-top: 1.5px solid white;"><?=($board -> wzMetters+0)?></td>
				<td class='text-center' style="border-top: 1.5px solid white;" id="m<?=$board -> edgeBandingId?>"><?=($board -> machineMetters)?></td>
			</tr>
			<?PHP if($board -> edgeBandComment): ?>
			<tr class="pointer" onclick="showEdgeBandingModal(<?=$board -> edgeBandingId?>);">
				<td colspan="4" style='border-top: none; padding-top: 0px!important; font-style: italic;'><span style="font-size: 12px;" class="glyphicon glyphicon-pushpin"></span>  <?=$board -> edgeBandComment?></td>
			</tr>
			<?PHP endif; ?>
			<tr style="display:none;"><td id="c<?=$board -> edgeBandingId?>"><?PHP if($board -> edgeBandingComment): ?><?=$board -> edgeBandingComment?><?PHP endif; ?></td></tr>
			<?PHP endforeach; ?>
		</table>
		<div style="padding-bottom: 1px; margin-top: 20px; margin-bottom: 20px;">
			<div id='sms' class='btn btn-default btn-block' onclick="sendSMS();"><span class="glyphicon glyphicon-earphone"></span> Wyślij SMS</div>
		</div>
	</div>
</div>
<div class="modal fade" id="edgeBandingModal" role="dialog">
	<div class="modal-dialog">
		<div class="contentContainer">
			<div class="modal-content">
				<div class="modal-body" id='edgeBandingModalBody'>
				</div>
			</div>
		</div>
	</div>
</div>