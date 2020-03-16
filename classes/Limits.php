<?php
class Limits
{
	public $boardsLimit;
	public $cuttingMettersLimit;
	public $edgeBandingMettersLimit;
  
	function __construct($boardsLimit, $cuttingMettersLimit, $edgeBandingMettersLimit){
		$this -> boardsLimit = $boardsLimit;
		$this -> cuttingMettersLimit = $cuttingMettersLimit;
		$this -> edgeBandingMettersLimit = $edgeBandingMettersLimit;
	}
}
?>