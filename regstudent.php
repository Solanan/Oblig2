<?php
include "start.html";
?>

<div id="form-box">
	
	<form id="regstudent" name="regstudent">
		
		<div class="from-group">
			<label> Fornavn <input class="form-control" type="text" id="fornavn" name="fornavn" > </label>
			
		</div><br>
		
		<div class="from-group">
			<label> Etternavn  <input class="form-control" type="text" id="etternavn" name="etternavn" > </label> 
			
		</div><br>
			
		<div class="from-group">
			<label> Klassekode <input class="form-control" type="text" id="klassekode" name="klassekode" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> </label>
			
		</div><br>
			
		<div class="from-group">
			<label> Brukernavn <input class="form-control" type="text" id="brukernavn" name="brukernavn" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> </label>
			
		</div><br>
			
			
		<button type="button" class="btn btn-success" value="Lagre" onclick="SubmitStudent()"> Lagre </button>
		
	</form>
</div>
	
<?php		
include "slutt.html";
?>

