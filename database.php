<?php

class DBConn {

	private $dbConn;
	private $lastResult;
	
	function __construct() 
	{		
		try 
		{
			$this->Connect();
		}
		catch(Exception $e)
		{
			echo QueryResult($e->getMessage());
		}
	}

	function Connect() 
	{
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		// method scope, not class scope
		include "../settings.php";
		if(!$this->dbConn = mysqli_connect(
			$dbSettings->host,
			$dbSettings->user, 
			$dbSettings->pass, 
			$dbSettings->db
		))
		{
			throw new Exception("Could not connect to database: " . mysql_error());
		}
	}

	function Erase($data)
	{
		for($i = 0; $i < count($data); $i++)
		{
			$pk = mysqli_escape_string($this->dbConn, $data[$i]["pk"]);
			$this->RunQuery("DELETE FROM student WHERE klassekode = '$pk'");
			$this->RunQuery("DELETE FROM klasse WHERE klassekode = '$pk'");
		}
		
		return "Slettet student(er) og klasse(r)";
		
	}

	function EraseBildeStudent($data)
	{
		for($i = 0; $i < count($data); $i++)
		{
			$pk = mysqli_escape_string($this->dbConn, $data[$i]["pk"]);
			$this->RunQuery("DELETE FROM student WHERE bildenummer = '$pk'");
			$this->RunQuery("DELETE FROM oblig2_bilder WHERE bildenummer = '$pk'");
		}
		
		return "Slettet bilder og tilhørende studenter!";
		
	}


	function UpdateInto($rows) 
	{
		foreach ($rows as $row)
		{
			$table=$row['table'];
			switch($table)
			{

				case ('student'):
				{
					$values="fornavn= '" . $row['fornavn'] . "'" . ", etternavn= '" .
					 $row['etternavn'] . "'" . ", klassekode= '" . $row['klassekode'] . "'"
					 . ", bildenummer= " . $row['bildenummer'];
					 $pkCol="brukernavn";
					 $pk=$row['brukernavn'];
					$this->RunQuery("UPDATE $table SET $values WHERE $pkCol = '$pk' ;");
					break;

				}
				case ('klasse'):
				{
					$values="klassenavn= '" . $row['klassenavn'] . "'";
					$pkCol="klassekode";
					$pk=$row['klassekode'];
					$this->RunQuery("UPDATE $table SET $values WHERE $pkCol = '$pk' ;");
					break;
				}
				case ('oblig2_bilder'):
				{
					$values="beskrivelse= '" . $row['beskrivelse'] . "'";
					$pkCol="bildenummer";
					$pk=$row['bildenummer'];
					$this->RunQuery("UPDATE $table SET $values WHERE $pkCol = '$pk' ;");
					break;
				}
			}
			
		}
	}

	function SearchTable($tab, $keyword) {
		$results = array();
		$rows = array();
		$hits = 0;

		$cols = $this->GetColumnNames($tab);

		if($keyword === "*")
		{
			$query = "SELECT * FROM $tab";
			$results[] = mysqli_query($this->dbConn, $query);

		}
		else 
		{
			for($i = 0; $i < count($cols); $i++) {
					$query = "SELECT * FROM " . $tab  . " WHERE " . $cols[$i] . " LIKE " . "'%" . $keyword . "%'";
					$results[] = mysqli_query($this->dbConn,$query);
			}
		}

		foreach($results as $result)
		{
			while($row = $result->fetch_assoc())
			{
				$hits += mysqli_num_rows($result);
				$rows[] = $row;
			}
		}

		if($hits === 0)
		{
			return "Ingen treff";
		}

		else
		{
	 		$cleanResult = array_unique($rows, SORT_REGULAR);
		 	$table = "";

		 	$table .= "<table id='searchTable' class='table table-striped table-bordered'> <thead> <tr>";
			for($i = 0; $i < count($cols); $i++) {
				$table .= "<th>" . ucfirst($cols[$i]) . "</th>";
			}

			$table .= "<th> Marker </th>";

			$table .=  "</tr> </thead> <tbody>";
			
			
		 	foreach($cleanResult as $row)
			{
				if($tab === 'student')
				{
					$table .= '<tr id=' . $row["brukernavn"] . '>';
				}
				
				if($tab === 'klasse')
				{
					$table .= '<tr id=' . $row["klassekode"] . '>';
				}
				if($tab === 'oblig2_bilder')
				{
					$table .= '<tr id=' . $row["bildenummer"] . '>';
				}
				
				foreach ($row as $key => $value)
				{
					$value = strtoupper($value);
					//str_replace(find,replace,string,count)
					
					//maritime
					
					$start = stripos($value, $keyword, 0);
					
					$c = strlen($keyword);
					
					$replace = substr($value, $start, $c);
					
					$value_rep = str_ireplace($keyword, "<span class='keywordhighlight'>" . $replace . "</span>", $value);
					
					$table .=  "<td> $value_rep </td>";

				}

				if($tab === "student")
				{
					$table .= '<td> <input data-table="student" data-primarykey=' . $row["brukernavn"] . ' type="checkbox"> </td>';
				}

				if($tab === "klasse")
				{
					$table .= '<td> <input data-table="klasse" data-primarykey=' . $row["klassekode"] . ' type="checkbox"> </td>';
				}
				if($tab === "oblig2_bilder")
				{
					$table .= '<td> <input data-table="oblig2_bilder" data-primarykey=' . $row["bildenummer"] . ' type="checkbox"> </td>';
				}
						
				$table .= "</tr>";
			}

			$table .=  "</tbody></table>";
			$table .= '<button id="btn_delete_cancel" class="btn btn-warning" name="delete" onclick="EditRow(this.name)"> Slett markerte </button>';
			$table .= '<button id="btn_edit_change" class="btn btn-primary" name="change" onclick="EditRow(this.name)"> Endre markerte </button>';
				
		 	return $table;
		 }
 	}

	function GetLastResultInJSON() {

		if($this->LastResultRowCount() > 0) {
			$results = [];
			while($row = mysqli_fetch_assoc($this->lastResult)) {
				$results[] = $row;
			}
			return json_encode($results);
		}
	}

	function GetColumnNames($table) {
		$this->RunQuery("DESC " . $table);
		$results = array();
		while($row = mysqli_fetch_assoc($this->lastResult))
		{
				$results[] = $row["Field"];
		}
		return $results;
	}

	function GetForeignKeyOnTable($table)
	{
		$result = mysqli_query(
			$this->dbConn, 
			"
			select COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_COLUMN_NAME, REFERENCED_TABLE_NAME
			from information_schema.KEY_COLUMN_USAGE
			where TABLE_NAME = '$table'
			");

		$fkInfo = new StdClass();
		foreach($result as $tableInfo)
		{

		}
	}

	function RunQuery($theQuery) 
	{
		if(!$this->dbConn) {
			throw new Exception("Connect first.");
		}	
		
		if(!$this->lastResult = mysqli_query($this->dbConn, $theQuery))
		{
			throw new Exception("Query failed: " . mysqli_error($this->dbConn));
		}
	}

	function DeleteRow($table, $pk) 
	{
		$pkCol = $this->GetPrimaryKeyColumn($table);
		$pkCol = mysqli_real_escape_string($this->dbConn, $pkCol);
		$q = "DELETE FROM $table where $pkCol = '$pk'";
		$this->RunQuery($q);
	}

	function DeleteRows($deleteInfo)
	{
		foreach($deleteInfo as $row)
		{
			$this->DeleteRow($row["table"], $row["pk"]);
		}
	}

	function GetRow() {
		if(!$this->lastResult) {
			throw new Exception("Do a query with Query() first. ");
		}
			
		return mysqli_fetch_assoc($this->lastResult);
	}

	function LastResultRowCount() {
		if(!$this->lastResult){
			throw new Exception("Do a query with Query() first. ");		
		}
		else {
			return mysqli_num_rows($this->lastResult);	
		}
	}

	function LastResultColCount() {
		if(!$this->lastResult) {
			throw new Exception("Do a query with Query() first. ");
		}	
		else {
			return mysqli_num_fields($this->lastResult);		
		}
	}

	function GetPrimaryKeyColumn($table) 
	{
		$this->RunQuery("show keys from $table where key_name = 'PRIMARY'");
		$colInfo = $this->lastResult->fetch_assoc();
		return $colInfo["Column_name"];	
	}
	
	function CheckIfPKExists($table, $pk) 
	{
		$pkCol = $this->GetPrimaryKeyColumn($table);
		$this->RunQuery("SELECT * FROM $table where $pkCol='$pk'");
		
		if($this->LastResultRowCount() > 0)
		{
			return true;
		}
		else { return false; }
	}
	
	function GetAllPKs($table)
	{
		$pkCol = $this->GetPrimaryKeyColumn($table);
		$this->RunQuery("SELECT $pkCol from $table");
		return $this->GetLastResultInJSON();
	}
	
	function CreateOptionMenu($table, $colShow, $labelAttr=null) {

		$result = mysqli_query(
			$this->dbConn, 
			"SELECT * FROM $table"
		);

		$pkCol = $this->GetPrimaryKeyColumn($table);


		if($table === "klasse")
		{
			$element = "<select name='klasseKodeVelger' id='klasseVelger'>";
		}
		else
		{
			$element = "<select name='bildeVelger' id='bildeVelger'>";
		}

			
		if($labelAttr) { $element .= "<option selected disabled> " . $labelAttr . " </option>"; }

		while($row = mysqli_fetch_assoc($result)) {
			$colString = "";

			if(is_array($colShow))
			{
				foreach($colShow as $col)
				{
					$colString .= $row[$col] . " ";
				}
				
				$element .= '<option value="' . $row[$pkCol] . '">' . $colString . '</option>';
			}
			

			else 
			{	
				$colString = $row[$colShow];
				//echo $colString;
				$element .= '<option value="' . $row[$pkCol] . '">' . $colString . '</option>';
			}
		}

		$element .= "</select>";
		return $element;
	}

	function CreateNewClassAndMoveStudents($rows, $newClass, $newCode)
	{
		if($newClass && $newCode)
		{
			$this->InsertInto("klasse", array($newCode, $newClass));

			$pk = $rows[0]["pk"];

			$pk = mysqli_real_escape_string($this->dbConn, $pk);
			$newCode = mysqli_real_escape_string($this->dbConn, $newCode);

			$query = ("UPDATE student SET klassekode = '$newCode' WHERE klassekode = '$pk'");
			mysqli_query($this->dbConn, $query);

			$query = ("DELETE FROM klasse where klassekode = '$pk'");
			mysqli_query($this->dbConn, $query);

			$r = "Ny klasse lagd og studentene flyttet over";
			return $r;
		}
	}
	
	function InsertInto($table,$values) 
	{
		$verdi="";
		for ($i=0; $i<count($values); $i++)
		{
			if ($i+1<count($values))
			{
				$verdi=$verdi."'$values[$i]',";
			}
			else
			{
				$verdi=$verdi."'$values[$i]'";
			}
		}
		
		$this->RunQuery("INSERT INTO $table VALUES ($verdi)");
	}
	
function CreateResultTable($changeOptions=false, $tabell=null) {
		if(!$this->lastResult) {
			throw new Exception("Do a query with Query() first. ");
		}

		else {
			$table = "<table class='table table-striped table-bordered'>";
			$table .= "<tr>";
			$h = $this->lastResult->fetch_fields();
			
			foreach($h as $key => $value) {
				$table .= "<th>" . $value->name . "</th>";
			}
			
			$table .= "</tr>";
			$rowCount = 0;
			while($row = mysqli_fetch_assoc($this->lastResult)) {
				$table .= '<tr id="' . $rowCount . '">';
				foreach ($row as $key => $value) {
					$table .= "<td>" . $value . "</td>";
				}

				if($changeOptions) {
					switch ($tabell) {
						case ("student"):
						{
							$table .= "<td><a href=endre.php?task=endre&pk=$row[brukernavn]&table=student>Endre</a></td>";
							$table .= "<td><a href=endre.php?task=slette&pk=$row[brukernavn]&table=student>Slett</a></td>";
							break;
						}

						case ("klasse"):
						{
							$table .=  "<td><a href=endre.php?task=endre&pk=$row[klassekode]&table=klasse>Endre</a></td>";
							$table .=  "<td><a href=endre.php?task=slette&pk=$row[klassekode]&table=klasse>Slett</a></td>";
							break;
						}
					}
				}

				$table .= "</tr>";
				$rowCount++;
			}
		
			$table .= "</table>";
			return $table;
		}
	}
}

?>
