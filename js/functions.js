var objReq = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
function processRequest(suppliedURL) {
	if (objReq) {
		objReq.open("GET", suppliedURL, true);
		objReq.onreadystatechange = handleServerResponse;
		objReq.send(null);
	} else {
		setTimeout("processRequest()", 1000);
	}
}

function processPOSTRequest(suppliedURL, sendData) {
	if (objReq) {
		objReq.open("POST", suppliedURL, true);
		objReq.onreadystatechange = handleServerResponse;
		objReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		objReq.send(sendData);
	} else {
		setTimeout("processPOSTRequest()", 1000);	
	}
}

function tableJSON(tableID, jsonObjRoot, allowedOpts=["EDIT", "DELETE"]) {
	$(tableID).html("");
	$(tableID).attr("border", 1);
	var newTr = "";
	for (var key in jsonObjRoot) {
		if (jsonObjRoot.hasOwnProperty(key)) {
			newTr += "<tr>";
			//console.log(jsonObjRoot[key].id);
			for (var eachField in jsonObjRoot[key]) {
				if (jsonObjRoot[key].hasOwnProperty(eachField)) {
					newTr += "<td>" + jsonObjRoot[key][eachField] + "</td>";
					//console.log(jsonObjRoot[key][eachField]);
				}
			}
			if (allowedOpts.indexOf("EDIT") != -1)
			newTr += '<td><a class="optEdit" data-internalid="' + jsonObjRoot[key].id + '" href="">EDIT</a></td>';
			if (allowedOpts.indexOf("DELETE") != -1)
				newTr += '<td><a class="optDelete" data-internalid="' + jsonObjRoot[key].id + '" href="">DELETE</a></td>';
			newTr += "</tr>";
		}
	}
	return newTr;
}

function confirm_action(msg, action_performed) {
	if ($('#dialog').length != 1) {
		$('body').append(create_confirm_dialog());
	}

	$('#dialog > p').text(msg);

	$("#dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons : {
		    "Confirm" : action_performed,
		    "Cancel" : function() {
		      $(this).dialog("close");
		    }
		  }
	});	

	$("#dialog").dialog("open"); 
}

function create_confirm_dialog() {
	var confirm_div = document.createElement("div");
	confirm_div.setAttribute("id", "dialog");
	confirm_div.setAttribute("title", "Confirmation Required");
	confirm_div.style.display = 'none';
	var confirm_p = document.createElement("p");
	confirm_div.appendChild(confirm_p);
	return confirm_div;
}
