<?php
include "database.php";

if(!empty($_GET["get"])) 
{
	switch ($_GET["get"]) 
	{
		case("klasse"): 
		{
			try 
			{
				$db = new DBConn();
				$db->RunQuery("SELECT klassenavn AS Klassenavn, klassekode AS Klassekode FROM klasse");
				echo QueryResult($db->CreateResultTable());
			}
			catch(Exception $e)
			{
				echo QueryResult($e->getMessage());
			}

			break;
		}
		case("oblig2_bilder"):
		{
			try 
			{
				$db = new DBConn();
				$db->RunQuery("SELECT * FROM oblig2_bilder");
				echo QueryResult($db->CreateResultTable());
			}
			catch(Exception $e)
			{
				echo QueryResult($e->getMessage());
			}

			break;
		}
			
		case("student"):
		{
			try 
			{
				$db = new DBConn();
				$db->RunQuery("SELECT brukernavn as Brukernavn, fornavn as Fornavn, etternavn as Etternavn, klassekode as Klassekode, bildenummer as Bildenummer FROM student;");
				echo QueryResult($db->CreateResultTable());
			}
			catch(Exception $e)
			{
				echo QueryResult($e->getMessage());
			}
			break;
		}
	}
}

elseif(!empty($_GET["submit"])) 
{
	switch ($_GET["submit"]) 
	{	
		case("klasse"):
		{
			$klassekode = $_GET["klassekode"];
			$klassenavn = $_GET["klassenavn"];
			if (validerKlassekode($klassekode)&& validerFelt($klassenavn))
			{
				try 
				{
					$db = new DBConn();
					$db->RunQuery("INSERT INTO klasse(klassekode,klassenavn) VALUES('$klassekode','$klassenavn')");
					echo QueryResult("Lagring vellykket.");
				}
				catch(Exception $e) 
				{
					echo QueryResult($e->getMessage());
				}
			}
			else
			{
				echo QueryResult("Noen av feltene er ikke fylt ut korrekt.", "error");
			}
				break;
		}

		case("student"):
		{
			$brukernavn = $_GET["brukernavn"];
			$fornavn = $_GET["fornavn"];
			$etternavn = $_GET["etternavn"];
			$klassekode = $_GET["klassekode"];
			$bildenummer = $_GET["bildenummer"];
		
			if (
				validerKlassekode($klassekode) &&
				validerFelt($fornavn) &&
				validerFelt($etternavn) &&
				validerBrukerNavn($brukernavn) &&
				$bildenummer
			)
				{			
					try 
						{
							$db = new DBConn();
							$db->RunQuery(
								"INSERT INTO student(brukernavn,fornavn,etternavn,klassekode,bildenummer) 
								VALUES('$brukernavn','$fornavn','$etternavn','$klassekode','$bildenummer')");

							echo QueryResult("Lagring vellykket.");
						}
						catch(Exception $e) 
						{
							echo QueryResult($e->getMessage());
						}
				}
		else
			{
				echo QueryResult("Noen av feltene er ikke fylt ut korrekt.", "error");
			}
			break;
		}
	}
}

elseif(!empty($_GET["delete"]))
{
	try 
	{
		$keys = json_decode($_GET["delete"], true);
		$db = new DBConn();
		$db->DeleteRows($keys);
		echo QueryResult("Sletting utført.");
	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["change"]))
{
	try 
	{
		$keys = json_decode($_GET["change"], true);
		$db = new DBConn();
		$db->UpdateInto($keys);
		echo QueryResult("Oppdatering utført.");
	}

	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["search"]))
{
	$keyword = $_GET["search"];
	$table = $_GET["table"];

	try 
	{
		$db = new DBConn();
		$resultTable = $db->SearchTable($table, $keyword);
		echo QueryResult($resultTable);
	}

	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["checkPK"]))
{
	$pk = $_GET["checkPK"];
	$table = $_GET["table"];
	
	try
	{
		$db = new DBConn();
		$result = $db->CheckIfPKExists($table, $pk);
		echo QueryResult($result);
	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["changeForm"]))
{
	$rows = json_decode($_GET["changeForm"],true);

	try
	{
		$db = new DBConn();
		
	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}	
}

elseif(!empty($_GET["deleteAndMove"]))
{
	$rows = json_decode($_GET["deleteAndMove"],true);
	$newClass = $_GET["nyKlasse"];
	$newCode = $_GET["nyKlasseKode"];
	
	try 
	{
			$db = new DBConn();
			$result = $db->CreateNewClassAndMoveStudents($rows, $newClass, $newCode);
			echo QueryResult($result);
	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
	
}

elseif(!empty($_GET["option"]))
{
	try 
	{
		$db = new DBConn();

		$result = null;
		if($_GET["option"] === "klasse")
		{
			$result = $db->CreateOptionMenu($_GET["option"], "klassekode");
		}
		elseif($_GET["option"] === "oblig2_bilder") 
		{
			$result = $db->CreateOptionMenu($_GET["option"], "beskrivelse");
		}


		echo QueryResult($result);
	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["deleteStudentsAndClass"]))
{
	try 
	{
		// [{"pk":"IT7","table":"klasse"}]
		$data = json_decode($_GET["deleteStudentsAndClass"], true);
		//$deletePk = $data[0]["pk"];
		$db = new DBConn();
		$result = $db->Erase($data);
		echo QueryResult($result);

	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}

elseif(!empty($_GET["deleteStudentsAndPics"]))
{
	try 
	{
		// [{"pk":"IT7","table":"klasse"}]
		$data = json_decode($_GET["deleteStudentsAndPics"], true);
		//$deletePk = $data[0]["pk"];
		$db = new DBConn();
		$filnavn = $db->RunQuery("SELECT bildenummer, filnavn FROM oblig2_bilder");
		$resultset = json_decode($db->GetLastResultInJSON(),true);
		
		/*print_r($resultset);*/

		$result = $db->EraseBildeStudent($data);
		foreach($resultset as $row)
		{
			unlink($row['filnavn']);
		}

		echo QueryResult($result);

	}
	
	catch(Exception $e) 
	{
		echo QueryResult($e->getMessage());
	}
}



/////////////////////////////////////// SUPPORT FUNCTIONS STARTS HERE



function validerKlasseKode($klassekode)
{
	return preg_match("/^[a-zA-ZøæåØÆÅ]{2}[a-zA-ZøæåØÆÅ]*[0-9]{1}$/", $klassekode);
}

function validerBrukerNavn($brukernavn)
{
	return preg_match("/^[a-zA-ZøæåØÆÅ]{2}$/", $brukernavn);
}

function validerFelt($felt)
{
	return $felt ? true : false;
}


function QueryResult($data,$status="ok")
{
	return json_encode(array("data" => $data,"status"=> $status));
}


?>
