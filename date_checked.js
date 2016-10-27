function date_days(){
	console.log("date_days");
	document.getElementById("query_date").disabled = true;
	document.getElementById("end_date").disabled = true;
	document.getElementById("selectdays").disabled = false;
	console.log(document.getElementById('datedays').value);
}

function query_days(){
	document.getElementById("selectdays").disabled = true;
	document.getElementById("query_date").disabled = false;
	document.getElementById("end_date").disabled = false;
	console.log(document.getElementById('querydays').value);
}
