<?php
include "start.html";
?>

<div id="form-box">
	
	<form id="regstudent" name="regstudent">
		
		<div class="form-group">
			<label> Fornavn <input class="form-control" type="text" id="fornavn" name="fornavn" > </label>
			
		</div><br>
		
		<div class="form-group">
			<label> Etternavn  <input class="form-control" type="text" id="etternavn" name="etternavn" > </label> 
			
		</div><br>
			
		<div class="form-group">
			<label> Klassekode <input class="form-control" type="text" id="klassekode" name="klassekode" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> </label>
			
		</div><br>
			
		<div class="form-group">
			<label> Brukernavn <input class="form-control" type="text" id="brukernavn" name="brukernavn" onkeyup="KeyUp(this.value, this.id, 'regstudent')"> </label>
			
		</div><br>
			
		<div class="form-group">
			<input type="file" name="filename" id="fileupload" >
		</div>

		<button type="button" class="btn btn-success" value="Lagre" onclick="SubmitStudent()"> Lagre </button>
		
	</form>
</div>
	
<?php		
include "slutt.html";
?>

