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

function tableJSON(tableID, jsonObjRoot, allowedOpts={edit :"EDIT", delete : "DELETE"}) {
	var d = new Date();
	var n = d.getTime();

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
				}
			}
			if (allowedOpts.edit)//if (allowedOpts.indexOf("EDIT") != -1)
			newTr += '<td><a class="optEdit" data-internalid="' + jsonObjRoot[key].id + '" href="">' + allowedOpts.edit + '</a></td>';
			if (allowedOpts.delete) //if (allowedOpts.indexOif (allowedOpts.delete) //f("DELETE") != -1)
				newTr += '<td><a class="optDelete" data-internalid="' + jsonObjRoot[key].id + '" href="">' + allowedOpts.delete + '</a></td>';
			newTr += "</tr>";
		}
	}
	return newTr;
}

function confirm_action(msg, action_performed) {
	if ($('#dialog').length != 1) {
		$('body').append(create_confirm_dialog("dialog", "Confirmation Required"));
	}

	$('#dialog > p').text(msg);

	$("#dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons : {
		    "Cancel" : function() {
		      $(this).dialog("close");
		    },"Confirm" : action_performed
		  }
	});	

	$("#dialog").dialog("open"); 
}

function create_confirm_dialog(myId, myTitle) {
	var confirm_div = document.createElement("div");
	confirm_div.setAttribute("id", myId);
	confirm_div.setAttribute("title", myTitle);
	confirm_div.style.display = 'none';
	var confirm_p = document.createElement("p");
	confirm_div.appendChild(confirm_p);
	return confirm_div;
}

function custom_alert_dialog(msg) {
	if ($('#customAlert').length != 1) {
		$('body').append(create_confirm_dialog("customAlert", "NOTICE"));
	}

	$('#customAlert > p').text(msg);

	$('#customAlert').dialog({
		autoOpen: false,
		modal: true,
		buttons : {
			"OK" : function() {
				$(this).dialog('close');
			}
		}
	});

	$('#customAlert').dialog('open');
}

function getTableHeader(headerArray) {
	var tblHeaders = "";
	for (var key in headerArray) {
		if (headerArray.hasOwnProperty(key)) {
			tblHeaders += '<th>' + headerArray[key] + '</th>';
		}
	}
	return tblHeaders;
}