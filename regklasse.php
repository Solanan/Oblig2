<?php
include "start.html";
?>
<div id="form-box">
	<form id="regklasse" name="regklasse" action="" method="POST" onSubmit="return ValiderRegKlasse()">
		<div class="from-group">
		<label> Klassekode <input class="form-control" type="text" id="klassekode" name="klassekode" onkeyup="KeyUp(this.value, this.id, 'regklasse')"> </label>
		</div><br>
		<div class="from-group">
			<label> Klassenavn <input class="form-control" type="text" id="klassenavn" name="klassenavn"> </label></div>
		<br>
		<button class="btn btn-success" type="button" value="Lagre" onclick="SubmitKlasse()"> Lagre </button>
	</form>
</div>


<?php
include "slutt.html";
?>