<?php
include "start.html";
?>
<div id="form-box">
	<form id="regklasse" name="regklasse" action="" method="POST" onSubmit="return ValiderRegKlasse()">
		<div class="from-group">
		<label> Klassekode <input class="form-control" type="text" id="klassekode" name="klassekode" onfocus="GiveUserFeedBack('','BLANK')"  onkeyup="KeyUp(this.value, this.id, 'regklasse')" required > </label>
		</div><br>
		<div class="from-group">
			<label> Klassenavn <input class="form-control" type="text" id="klassenavn" name="klassenavn" onfocus="GiveUserFeedBack('','BLANK')" required > </label></div>
		<br>
		<button class="btn btn-success" type="button" value="Lagre" onclick="SubmitKlasse()"> Lagre </button>
		
		<button class="btn btn-warning" type="button" value="Reset" onclick="ResetForm(this)"> Slett skjemadata </button>
	</form>
</div>


<?php
include "slutt.html";
?>