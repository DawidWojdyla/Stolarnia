<?php if(!isset($this)) die(); ?>
<div class="contentContainer">
	<div class="text-center header">Limity dzienne</div>
	<div class="tabContainer">
		<table class="table addingTab">
			<tr class="noTopBorder">
				<td><label>Ilość płyt na piłe:</label></td>
				<td>
					<div style="float:left;" id="boardsAmount"><?=$limits -> boardsPerDay?></div>
					<div class="text-right noPadding">
						<span onclick="updateBoardsAmount();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>Ilość metrów cięcia na piłe:</label></td>
				<td>
					<div  id="cuttingMetters" style="float: left;"><?=$limits -> cuttingMettersPerDay?></div>
					<div class="text-right noPadding">
						<span onclick="updateCuttingMetters();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span>
					</div>
				</td>
			</tr>
			<tr>
				<td><label>ilość metrów oklejania:</label></td>
				<td><div id='edgeBandingMetters' style="float: left;"><?=$limits -> edgeBandingMettersPerDay?></div><div class="text-right noPadding"><span onclick="updateEdgeBandingMetters();" style="font-size: 10px; cursor: pointer; padding:4px 8px;" class="glyphicon glyphicon-pencil btn btn-default noMargin"></span></div></td>
			</tr>
		</table>
	</div>
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