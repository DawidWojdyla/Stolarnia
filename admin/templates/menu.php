<?php if(!isset($joineryAdmin)) die();?>
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> Pracownicy <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showWorkerAddingForm"><span class="glyphicon glyphicon-plus"></span> Nowy</a></li>
		<li><a href="index.php?action=showWorkerSearchingForm"><span class="glyphicon glyphicon-search"></span> Znajdź</a></li>
		<li><a href="index.php?action=showWorkersList"><span class="glyphicon glyphicon-th-list"></span> Lista</a></li>
		<li><a href="index.php?action=showRemovedWorkersList"><span class="glyphicon glyphicon-ban-circle"></span> Usunięci</a></li>
	</ul>
</li>
<!--<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-stats"></span> Statystyki <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showStats"><span class="glyphicon glyphicon-globe"></span> Wszystkie zlecenia</a></li>
		<li><a href="index.php?action=showCustomerStats"><span class="glyphicon glyphicon-screenshot"></span> Wybrany klient</a></li>
	</ul>
</li>-->
<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-cog"></span> Ustawienia <span class="caret"></span></a>
	<ul class="dropdown-menu">
		<li><a href="index.php?action=showLimitsUpdatingForm"><span class="glyphicon glyphicon-scale"></span> Limity</a></li>
		<li><a href="index.php?action=showStandsUpdatingForm"><span class="glyphicon glyphicon-map-marker"></span> Stanowiska</a></li>
		<li><a href="index.php?action=showAdminUpdatingForm"><span class="glyphicon glyphicon-lock"></span> Konto Admina</a></li>
	</ul>
</li>