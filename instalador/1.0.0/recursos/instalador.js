String.prototype.trim = function() {
    return this.replace(/^\s*(\S*(\s+\S+)*)\s*$/,'$1');
};
/**
 * Busca si un valor pertenece al arreglo
 */
function in_array (elemento, arreglo) {
	for (var i=0 ; i < arreglo.length; i++) {
		if (arreglo[i] == elemento) {
			return true;
		}
	}
	return false;
}

function esperar_operacion(form)
{
	setTimeout ("mostrar_esperar()", 1000); 
	return true;
}

function mostrar_esperar()
{
	scroll(0,0);
	var capa_espera = document.getElementById('capa_espera');
	if (capa_espera) {
		capa_espera.style.visibility = 'visible';
	}			
}

function scroll_fondo()
{
	if (document.body.scrollHeight) {
		window.scrollTo(0, document.body.scrollHeight);
	} else if (screen.height) {
		window.scrollTo(0, screen.height);
	}
} 

var ventana_hija = {};
function abrir_popup(id, url, opciones, extra, dep) {
	vars = '';
	if (typeof opciones != 'undefined') {
		for (var o in opciones) {
			vars += o + '=' + opciones[o] + ',';
		}
	}
	if (typeof dep == 'undefined') {dep = true;}
	if (dep) {
		vars += 'dependent=1';
	}
	if (typeof extra != 'undefined') {
		vars += extra;
	}
	var no_esta_definida  = !ventana_hija[id] || ventana_hija[id].closed || !ventana_hija[id].focus;
	if (no_esta_definida) {
		// No fue definida, esta cerrada o no puede tener foco
		ventana_hija[id] = window.open( url , id, vars);
		ventana_hija[id].focus();
	} else {
		// Ya fue definida, no esta cerrada  y puede tener foco
		ventana_hija[id].focus();		
		ventana_hija[id].location.href = url;
		ventana_hija[id].opener = window;
	}
	return false;	
}

// Password strength meter v1.0
// Matthew R. Miller - 2007
// www.codeandcoffee.com
// Based off of code from  http://www.intelligent-web.co.uk

// Settings
// -- Toggle to true or false, if you want to change what is checked in the password
var bCheckNumbers = true;
var bCheckUpperCase = true;
var bCheckLowerCase = true;
var bCheckPunctuation = true;
var nPasswordLifetime = 365;

// Check password
function checkPassword(strPassword)
{
	// Reset combination count
	nCombinations = 0;
	
	// Check numbers
	if (bCheckNumbers)
	{
		strCheck = "0123456789";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check upper case
	if (bCheckUpperCase)
	{
		strCheck = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check lower case
	if (bCheckLowerCase)
	{
		strCheck = "abcdefghijklmnopqrstuvwxyz";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check punctuation
	if (bCheckPunctuation)
	{
		strCheck = ";:-_=+\|//?^&!.@$£#*()%~<>{}[]";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Calculate
	// -- 500 tries per second => minutes 
    	var nDays = ((Math.pow(nCombinations, strPassword.length) / 500) / 2) / 86400;
 
	// Number of days out of password lifetime setting
	var nPerc = nDays / nPasswordLifetime;
	
	return nPerc;
}
 
// Runs password through check and then updates GUI 
function runPassword(strPassword, strFieldID) 
{
	 // Get controls
    	var ctlBar = document.getElementById(strFieldID + "_bar"); 
    	var ctlText = document.getElementById(strFieldID + "_text");
    	if (!ctlBar || !ctlText)
    		return;

	// Check password
	nPerc = checkPassword(strPassword);

    	// Set new width
	var nRound = Math.log(nPerc) * 5;
	if (nRound < (strPassword.length * 5)) {		//Feedback visual para el usuario cuando el porcentaje es demasiado pequeño 
		nRound = strPassword.length * 6; 
	}
	
	if (nRound > 100)
		nRound = 100;

 
 	// Color and text
 	if (nRound > 95)
 	{
 		strText = "Muy Seguro";
 		strColor = "#3bce08";
 	}
 	else if (nRound > 75)
 	{
 		strText = "Seguro";
 		strColor = "orange";
	}
 	else if (nRound > 50)
 	{
 		strText = "Mediocre";
 		strColor = "#ffd801";
 	}
 	else
 	{
 		strColor = "red"; 	
 		if (strPassword == 'toba') {
 			strText = 'definitivamente no';
 		} else {
	 		strText = "Inseguro";
	 	}
 	}
    	ctlBar.style.width = nRound + "%";	
	ctlBar.style.backgroundColor = strColor;
	ctlText.innerHTML = "<span style='white-spacen: nowrap; color: " + strColor + ";'>" + strText + "</span>";
}
 
// Checks a string for a list of characters
function doesContain(strPassword, strCheck)
 {
    	nCount = 0; 
 
	for (i = 0; i < strPassword.length; i++) 
	{
		if (strCheck.indexOf(strPassword.charAt(i)) > -1) 
		{ 
	        	nCount++; 
		} 
	} 
 
	return nCount; 
} 
 
 
 function eliminar_instalador()
 {
 	return true;
 }
 



 
 

