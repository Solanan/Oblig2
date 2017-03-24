NameSpace = {};
NameSpace["mode"] = "view";
NameSpace["currentForm"] = null;
NameSpace["brukernavn"] = null;
NameSpace["klassekode"] = null;

function ExecuteAjax(commandString, callback, optArgs=null) {
		var xhr = new XMLHttpRequest();
		console.log(commandString);

		xhr.onreadystatechange = (function() {
			if(xhr.readyState === 4) {
				if(xhr.status === 200) {
					//console.log(xhr.responseText);
					var obj = JSON.parse(xhr.responseText);
					callback(obj.data,obj.status, optArgs);
				}
				if(xhr.status === 500)
				{
					GiveUserFeedBack("Noe feil skjedde på serveren!!", "error");
				}
			}
		});

	xhr.open("GET", "backend.php?" + commandString , true);
	xhr.send();
}

function ResetForm()
{
	var inputs = document.getElementsByClassName("form-control");
	for(var i = 0; i < inputs.length; i++)
	{
		if(inputs[i].type === "text") { inputs[i].value = ""; }
	}

	GiveUserFeedBack("","BLANK");
}

function validerKlasseKode(klassekode)
{
	return klassekode.match(/^[a-zA-ZøæåØÆÅ]{2}[a-zA-ZøæåØÆÅ]*[0-9]{1}$/g);
}

function SwitchButtonFunctionality()
{
    if(NameSpace["mode"] === "edit")
    {
        var btn_change = document.getElementById("btn_edit_change");
        var btn_cancel = document.getElementById("btn_delete_cancel");

        btn_cancel.className = "btn btn-warning";
        btn_cancel.innerHTML = "Slett markerte";
        btn_cancel.name = "delete";

        btn_change.className = "btn btn-primary";
        btn_change.innerHTML = "Endre markerte";
        btn_change.name = "change";
        
        NameSpace["mode"] === "view";
    }
    
    if(NameSpace["mode"] === "view")
    {
        var btn_change = document.getElementById("btn_edit_change");
        var btn_cancel = document.getElementById("btn_delete_cancel");
        
        btn_change.name = "save_changes";
        btn_change.innerHTML = "Lagre endringer";
        btn_change.className = "btn btn-success";

        btn_cancel.name = "cancel_changes";
        btn_cancel.innerHTML = "Angre endringer";
        btn_cancel.className = "btn btn-danger";
        
        NameSpace["mode"] === "edit";
    }

}

function createOptions(optionMenu, status)
{
	var e = document.getElementsByName("klasselisteFelt");
	console.log(optionMenu);
	console.log(e);
	
	for ( var i = 0; i < e.length ; i++)
		{
			e[i].innerHTML=optionMenu;
			var match = e[i].dataset.selected;
			for (var j=0; j < e[i].childNodes[0].options.length; j++)
				{
					if (e[i].childNodes[0].options[j].text === match)
						{
							e[i].childNodes[0].options[j].selected = true;
						}
				}
		}
}
function createBildeOptions(optionMenu, status)
{
	var e = document.getElementsByName("bildeNrVelger");
	console.log(optionMenu);
	console.log(e);
	
	for ( var i = 0; i < e.length ; i++)
		{
			e[i].innerHTML=optionMenu;
			var match = e[i].dataset.selected;
			for (var j=0; j < e[i].childNodes[0].options.length; j++)
				{
					if (e[i].childNodes[0].options[j].text === match)
						{
							e[i].childNodes[0].options[j].selected = true;
						}
				}
		}
}

function onTabellVelgerChange()
{
	var elem = document.getElementById("messages");
	elem.className = "";
	elem.innerHTML = "";
}

function KeyUp(value, elemId, formName) 
	{
		if(value.length === 0)
		{
			GiveUserFeedBack("", "BLANK");
			return;
		}        
		if(elemId === "klassekode")
		{
			if(value.length > 2)
			{
				value = value.toUpperCase();
				var letters = value.substr(0,2);
				var number = value.substr(2,1);
				
				if(validerKlasseKode(value))
				{	
                    NameSpace["currentForm"] = formName;
					ExecuteAjax("checkPK=" + value + "&table=klasse", CheckPk_callback_klassekode);
				}
                
				else
				{
				    GiveUserFeedBack("Klassekoden må bestå av minst to bokstaver og slutte med ett tall.", "error");
				}
			}
            
			else
			{
				GiveUserFeedBack("Klassekoden må være minst 3 symboler lang", "error");
			}
		}
		
		if(elemId === "brukernavn")
		{
			if(value.length === 2)
			{
				NameSpace["currentForm"] = formName;
				ExecuteAjax("checkPK=" + value + "&table=student", CheckPk_callback_brukernavn);
			}
			if(value.length > 2 || value.length < 2)
			{
				GiveUserFeedBack("Brukernavnet kan kun være 2 symboler lang", "error");
			}
			
		}
}

function CheckPk_callback_brukernavn(status)
{
	if(status)
	{
		GiveUserFeedBack("Brukernavnet fins fra før.", "error");
		NameSpace["brukernavn"] = false;
	}
	else
		{
			GiveUserFeedBack("Brukernavnet er ledig.", "ok");
			NameSpace["brukernavn"] = true;
		}
}

function CheckPk_callback_klassekode(status)
{
	switch(NameSpace["currentForm"])
	{
		case("regklasse"):
			{
				if(status)
				{
					GiveUserFeedBack("Klassekoden fins fra før.", "error");
					NameSpace ["klassekode"] = false;
				}

				else
				{
					GiveUserFeedBack("Klassekoden er ledig.", "ok");
					NameSpace ["klassekode"] = true;
				}
				break;
			}
			
		case("regstudent"):
			{
				if(status)
				{
					GiveUserFeedBack("Klassekoden fins.", "ok");
					NameSpace ["klassekode"] = true;
				}
				
				else
				{
					GiveUserFeedBack("Klassekoden finnes ikke.", "error");
					NameSpace ["klassekode"] = false;
				}
				break;
			}
		}
}


function SubmitStudent()
{
	var fornavn = $value("fornavn");
	var etternavn = $value("etternavn");
	var brukernavn = $value("brukernavn");
	

	var bildenummer;
	var bildeVelger = document.getElementById("bildeVelger");

	var klassekode;
	var klasseKodeVelger = document.getElementById("klasseVelger");

	if(klasseKodeVelger)
	{

		var klasse_index = klasseKodeVelger.selectedIndex;
		klassekode = klasseKodeVelger.options[klasse_index].value;
	}

	if(bildeVelger)
	{
		var bilde_index = bildeVelger.selectedIndex;
		var bildenummer = bildeVelger.options[bilde_index].value;
		/*debugger;*/
	}

	if(fornavn && etternavn && brukernavn && klassekode && bildenummer)

	{
		klassekode = klassekode.toUpperCase();
		fornavn=fornavn[0].toUpperCase()+fornavn.slice(1);
		etternavn=etternavn[0].toUpperCase()+etternavn.slice(1);
		
		if (NameSpace["brukernavn"])
		{
			var command = "submit=student&fornavn="+fornavn+"&etternavn="+etternavn+"&klassekode="+klassekode+"&brukernavn="+brukernavn+"&bildenummer="+bildenummer;
			//console.log(command);
			ExecuteAjax(command, GiveUserFeedBack);
			NameSpace["brukernavn"] = false;
			ResetForm();
		}
		else 
		{
			GiveUserFeedBack("Brukernavn er ikke godkjent.", "error");
		}
	}
	
	else
	{
		GiveUserFeedBack("Du må fylle ut alle feltene.", "error");
	}
}

function SubmitKlasse() 
{
	var klassekode = $value("klassekode");
	var klassenavn = $value("klassenavn");

	if(klassekode && klassenavn) 
	{
		if(NameSpace["klassekode"])
		{
			klassekode=klassekode.toUpperCase();
			var command = "submit=klasse&klassekode="+klassekode+"&klassenavn="+klassenavn;
			console.log(command);
			ExecuteAjax(command, GiveUserFeedBack);
			NameSpace["klassekode"] = false;
			ResetForm();
		}
		else
		{
			GiveUserFeedBack("Klassekode ikke godkjent.", "error");
		}
	}
	else
	{
		GiveUserFeedBack("Begge feltene må fylles ut.", "error");
	}
}	

function EditRow(job)
{
	var checked = document.getElementsByTagName("input");
	var rows = new Array();

	for(var i = 0; i < checked.length; i++)
	{
		if(checked[i].type == "checkbox")
		{
			if(checked[i].checked)
			{
				v = {};
				// v["row"] = checked[i].parentNode;
				v["pk"] = checked[i].dataset.primarykey;
				v["table"] = checked[i].dataset.table;
				
				rows.push(v);
			}
		}
	}
	
	if(rows.length === 0)
	{
		GiveUserFeedBack("Velg en eller flere rader moderering", "error");
		return;
	}

	switch(job)
		{
			case("delete"):
			{
				var r = confirm("Er du sikker på at du vil slette disse dataene?");
					if (r == true) 
					{
						console.log(rows[0]["table"]);
						if (rows[0]["table"]=="student")
						{
    						json_rows = JSON.stringify(rows);
							ExecuteAjax("delete=" + json_rows, GiveUserFeedBack);
							setTimeout(DoSearch, 1000);
						}
						if (rows[0]["table"]=="klasse")
						{
    						json_rows = JSON.stringify(rows);
							ExecuteAjax("deleteStudentsAndClass=" + json_rows, GiveUserFeedBack);
							setTimeout(DoSearch, 1000);							
						}

						if (rows[0]["table"]=="oblig2_bilder")
						{
							json_rows = JSON.stringify(rows);
							ExecuteAjax("deleteStudentsAndPics=" + json_rows, GiveUserFeedBack);
							setTimeout(DoSearch, 1000);							
						}

					} 
					else 
					{
						alert("Sletting avbrutt!!");
						DoSearch();
					}
				
				break;
			}

			case("cancel_changes"):
			{
				SwitchButtonFunctionality();
				
				DoSearch();
				break;
			}
			
			case("save_changes"):
			{
				
				var inputs = document.getElementsByTagName("input");
				var rows = new Array();
				var sjekkok = true;

				for(var i = 0; i < inputs.length; i++)
				{
					if(inputs[i].type == "checkbox")
					{
						if(inputs[i].checked)
						{
							if (inputs[i].dataset.table=="klasse")
							{
								var klassenavn = inputs[i].parentElement.parentElement.children[1].children[0].value;
								var pk = inputs[i].dataset.primarykey;
								console.log(klassenavn);
								if (klassenavn)
								{
									var verdier= {};
										verdier['klassekode']= pk;
										verdier['klassenavn']= klassenavn;
										verdier['table']= "klasse";	
									rows.push(verdier);
								}
								else
								{
									sjekkok = false;
								}					 
							}

							if (inputs[i].dataset.table=="oblig2_bilder")
							{
								var bildeBeskrivelse = inputs[i].parentElement.parentElement.children[3].children[0].value;
								var pk = inputs[i].dataset.primarykey;
								console.log(bildeBeskrivelse);
								if (bildeBeskrivelse)
								{
									var verdier= {};
										verdier['bildenummer']= pk;
										verdier['beskrivelse']= bildeBeskrivelse;
										verdier['table']= "oblig2_bilder";	
									rows.push(verdier);
								}
								else
								{
									sjekkok = false;
								}					 
							}
							
							if(inputs[i].dataset.table=="student")
							{
								var pk = inputs[i].dataset.primarykey;
								var rad = document.getElementById(pk);
								var selectedKlasseBoks = rad.getElementsByTagName("select")[0];
								var selectedBildeBoks = rad.getElementsByTagName("select")[1];
								console.log(selectedKlasseBoks);
								console.log(selectedBildeBoks);
								var selectedKlasse = selectedKlasseBoks.options[selectedKlasseBoks.selectedIndex].value;
								var selectedBilde= selectedBildeBoks.options[selectedBildeBoks.selectedIndex].value;
								
								/*var selectedKlasse = inputs[i].getElementsByTagName('select')[0];*/
								/*var selectedBilde = inputs[i].getElementsByTagName('select')[1]*/;	
								/*var select = inputs[i].parentNode.previousSibling.children[0].children[0];
								var selectedKlasse = select.options[select.selectedIndex].value;*/
								var fornavn = inputs[i].parentElement.parentElement.children[1].children[0].value;
								var etternavn = inputs[i].parentElement.parentElement.children[2].children[0].value;
								
								console.log(fornavn);
								console.log(etternavn);
								
								if (fornavn && etternavn)
								{
									var verdier= {};
										verdier['brukernavn']= pk;
										verdier['fornavn']= fornavn;
										verdier['etternavn']= etternavn;
										verdier['klassekode']= selectedKlasse;

										verdier['bildenummer']= selectedBilde;

										verdier['table'] = "student";
									rows.push(verdier);	
								}
								else
								{
									sjekkok = false;
								}						
							}

							//rows.push(v);
						}
					}
				}
				if (sjekkok)
				{
					var values = JSON.stringify(rows);
					console.log(values);
					ExecuteAjax("change="+values, GiveUserFeedBack);
					
					SwitchButtonFunctionality();
					
					// delay, to make sure all changes are finished in the database before we get the new result back
					setTimeout(DoSearch, 500);
					break;
				}
				else
				{
					GiveUserFeedBack("Du kan ikke ha tomme felt!!", "error");

					break;

				}
			}
				
		case("change"):
		{
			SwitchButtonFunctionality();
            
			if(rows[0]["table"] === "klasse")
			{	
				for(var i = 0; i < rows.length; i++)
				{
					var checkedRow = document.getElementById(rows[i]["pk"]);
					var txt = checkedRow.childNodes[1].innerText;
					checkedRow.childNodes[1].innerHTML = "";
					
					var txtField = document.createElement("input");
					txtField.type = "text";
					txtField.value = txt;
					txtField.name = "klassenavn";
					txtField.required = true;
					
					checkedRow.childNodes[1].appendChild(txtField);
				}
			}
			if(rows[0]["table"] === "oblig2_bilder")
			{	
				for(var i = 0; i < rows.length; i++)
				{
					var checkedRow = document.getElementById(rows[i]["pk"]);
					var txt = checkedRow.childNodes[3].innerText;
					checkedRow.childNodes[3].innerHTML = "";
					
					var txtField = document.createElement("input");
					txtField.type = "text";
					txtField.value = txt;
					txtField.name = "beskrivelse";
					txtField.required = true;
					
					checkedRow.childNodes[3].appendChild(txtField);
				}
			}
			if(rows[0]["table"] === "student")
			{					
				for(var i = 0; i < rows.length; i++)
				{
					var checkedRow = document.getElementById(rows[i]["pk"]);
					
					var txt = checkedRow.childNodes[1].innerText;
					checkedRow.childNodes[1].innerHTML = "";				
					var txtField = document.createElement("input");
					txtField.type = "text";
					txtField.name = "fornavn";
					txtField.required = true;
					txtField.value = txt;
										
					checkedRow.childNodes[1].appendChild(txtField);
					
					var txt = checkedRow.childNodes[2].innerText;
					checkedRow.childNodes[2].innerHTML = "";
					var txtField = document.createElement("input");
					txtField.name = "etternavn";
					txtField.type = "text";
					txtField.required = true;
					txtField.value = txt;					
					checkedRow.childNodes[2].appendChild(txtField);
					
					var selected = checkedRow.childNodes[3].innerText;
					checkedRow.childNodes[3].innerHTML = "<div name='klasselisteFelt' data-selected=" + selected + "></div>";
					//checkedRow.childNodes[3].Name = "klasselisteFelt";	
					//console.log(checkedRow.childNodes[3].Name);
					var selectedbilde = checkedRow.childNodes[4].innerText;
					checkedRow.childNodes[4].innerHTML = "<div name='bildeNrVelger' data-selected=" + selectedbilde + "></div>";
				}			
				ExecuteAjax("option=klasse", createOptions);

				ExecuteAjax("option=oblig2_bilder", createBildeOptions);

			}
			break;
		}
	}
}


function HentBildeVelger(id)
{
	ExecuteAjax(
		"option=oblig2_bilder", plasser_bildevelger);
}

function plasser_bildevelger(elementData)
{
	document.getElementById("bildeNrVelger").innerHTML = elementData;
}

function HentKlasseKodeVelger()
{
	ExecuteAjax(
		"option=klasse", plasser_klassevelger)
}

function plasser_klassevelger(elementData)
{

	document.getElementById("klasseKodeVelger").innerHTML = elementData;
}

function HentTabell(tabellNavn) {
	ExecuteAjax("get="+tabellNavn, ShowTable);
}

function OnChange_tabellVelger()
{
	document.getElementById("table").innerHTML = "";
	DoSearch();
}

function DoSearch(event)
{
    // prevent Ajax to fire twice when inserting * into "søkestreng" (SHIFT + *)
    // KeyUp will fire when Shift is released.
    //console.log(event);
    if(event)
    {
        if(event.key === "Shift")
        {
            return;
        }
    }
        
	var tabellVelger = document.getElementById("tabellVelger");
	var selectedTable = tabellVelger.options[tabellVelger.selectedIndex].value;
	var searchString = $value("keyword");

	if(!selectedTable)
	{
		GiveUserFeedBack("Velg tabell å søke i");
	}

	if(searchString.length === 0)
	{
		document.getElementById("table").innerHTML = "";
	}

	if(selectedTable && searchString.length > 0)
	{
		ExecuteAjax("search=" + searchString + "&table=" + selectedTable, ShowTable);
	}
}

function $value(id)
{
	return document.getElementById(id).value;
}

function ShowTable(tableData) {
	//console.log(tableData);
	document.getElementById("table").innerHTML = tableData;
}

function GiveUserFeedBack(msg, type)
{
    switch(type)
    {
        case("error"):
        {
            var elem = document.getElementById("messages");
            elem.className = "col-xs-6 alert alert-warning";
            elem.innerHTML = msg;
            break;
        }

        case("ok"):
        {
            var elem = document.getElementById("messages");
            elem.className = "col-xs-6 alert alert-info";
            elem.innerHTML = msg;
            break;
        }

        case("BLANK"):
        {
            var elem = document.getElementById("messages");
            elem.className = "";
            elem.innerHTML = "";
			break;
        }
    }
}