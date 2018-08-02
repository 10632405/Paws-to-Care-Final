function fetchJSONFile(path, callback) {
	let httpRequest = new XMLHttpRequest();
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState === 4) {
			if (httpRequest.status === 200) {
				let data = JSON.parse(httpRequest.responseText);
				if (callback) callback(data);
			}
		}
	};
	httpRequest.open('GET', path);
	httpRequest.send();
}

function filterTable(id) {
	let textField = document.getElementById("filter" + id);
	let table = document.getElementById('animalTable');
	tr = table.getElementsByTagName("tr");
	let colNum = 0;
	for (let key in table.rows[1].cells) {
		colNum++;
		if (table.rows[1].cells[key].innerHTML === id) {
			break;
		}
	}
	$('#animalTable td:nth-child(' + colNum + ')').slice(1).each(function() {
		for (i = 2; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[colNum - 1];
			if (td) {
				if (td.innerHTML.toUpperCase().startsWith(textField.value.toUpperCase())) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	});
}

function buildModal(string, animalName, modal, id) {
	let modalButton = "<p><button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#" + modal + "Modal" + id + "' style='display: block;margin: auto;'>" + modal.charAt(0).toUpperCase() + modal.slice(1) + "</button></p>";
	// Instert Model
	let modelString = "<!-- The Modal -->";
	modelString += "<div class='modal' id='" + modal + "Modal" + id + "'>";
	modelString += "  <div class='modal-dialog'>";
	modelString += "    <div class='modal-content'>";
	modelString += "      <!-- Modal Header -->";
	modelString += "      <div class='modal-header'>";
	modelString += "        <h4 class='modal-title'>Notes for " + animalName + "</h4>";
	modelString += "        <button type='button' class='close' data-dismiss='modal'>&times;</button>";
	modelString += "      </div>";
	modelString += "      <!-- Modal body -->";
	modelString += "      <div class='modal-body'>" + string;
	modelString += "      </div>";
	modelString += "      <!-- Modal footer -->";
	modelString += "      <div class='modal-footer'>";
	modelString += "        <button type='button' class='btn btn-danger' data-dismiss='modal'>Close</button>";
	modelString += "      </div>";
	modelString += "    </div>";
	modelString += "  </div>";
	modelString += "</div>";
	let main = document.getElementsByTagName("main");
	main[0].insertAdjacentHTML('afterend', modelString);
	return modalButton;
}

function changeToReadable(col, input, animalName, id) {
	if (col === "Notes") {
		return buildModal(input, animalName, "notes", id);
	}
/*
	if (col === "Owners") {
		return buildModal(input, animalName, "owner", id);
	}
*/
	if (col === "Shots") {
		if (input === "1") {
			return "Up to date";
		} else {
			return "Not up to date. See notes.";
		}
	}

	if (col === "Neutered" || col === "Declawed" || col === "Licensed") {
		if (input === "1") {
			return "<span class='fa fa-check' style='font-size: 18pt; color: #00be37;'></span>";
		} else {
			return "<span class='fa fa-close' style='font-size: 18pt;'></span>";
		}
	}
	if (col === "Weight") {
		if (input >= 100) {
			return "L - " + input + "Lbs";
		} else if (input > 20 && input <= 100) {
			return "M - " + input + "Lbs";
		} else {
			return "S - " + input + "Lbs";
		}
	}
	return input;
}
let searchableCols = ["Name", "Breed", "Sex", "Shots", "Age - Birthdate", "Size", "Species", "Owners"];

function buildHtmlTable(headerArray, dataArray, div) {

	let animalName = "";
	let col = [];

	for (let key in dataArray[0]) {
		if (col.indexOf(key) === -1 && key !== "id") {
			col.push(key);
		}
	}
	
	let table = document.createElement("table");
	table.setAttribute("class", "animalTable");
	table.setAttribute("id", "animalTable");
	let tr = table.insertRow(-1);
	for (let i = 0; i < col.length; i++) {
		let tabCell = tr.insertCell(-1);
		let addToCell;
		if (searchableCols.indexOf(col[i]) > -1) {
			addToCell = document.createElement("input");
			addToCell.className = "filterField";
			addToCell.placeholder = "Search on " + col[i];
			addToCell.setAttribute('onkeyup', "filterTable('" + col[i] + "')");
			addToCell.setAttribute('id', "filter" + col[i]);
		} else {
			addToCell = document.createTextNode(" ");
		}
		tabCell.appendChild(addToCell);
	}
	tr = table.insertRow(-1);
	for (let i = 0; i < col.length; i++) {
		// Col Titles
		let th = document.createElement("th");
		th.innerHTML = col[i];
		th.onclick = function() {
			tableHeaderClick(col[i].toLowerCase());
		};
		th.setAttribute('title', headerArray[col[i]]);
		th.setAttribute('data-toggle', 'tooltip');
		th.setAttribute('data-placement', 'bottom');
		th.setAttribute('id', col[i].toLowerCase());
		tr.appendChild(th);
	}
	for (let i = 0; i < dataArray.length; i++) {
		tr = table.insertRow(-1);
		for (let j = 0; j < col.length; j++) {
			if (j === 0) {
				animalName = dataArray[i][col[j]];
			}
			let tabCell = tr.insertCell(-1);

			tabCell.innerHTML = changeToReadable(col[j], dataArray[i][col[j]], animalName, dataArray[i]['id']);
		}
	}
	let divContainer = document.getElementById(div);
	divContainer.innerHTML = "";
	divContainer.appendChild(table);
}

function srt(on, descending) {
	on = on && on.constructor === Object ? on : {};
	return function(a, b) {
		if (on.string || on.key) {
			a = on.key ? a[on.key] : a;
			a = on.string ? String(a).toLowerCase() : a;
			b = on.key ? b[on.key] : b;
			b = on.string ? String(b).toLowerCase() : b;
			if (on.key && (!b || !a)) {
				return !a && !b ? 1 : !a ? 1 : -1;
			}
		}
		return descending ? ~~ (on.string ? b.localeCompare(a) : a < b) : ~~ (on.string ? a.localeCompare(b) : a > b);
	};
}

function updateTable(data, tableID, tableHeaders) {
	let table = document.getElementById(tableID);

	for (let i = 2, row; row = table.rows[i]; i++) { // Start on row 1
		for (let j = 0, col; col = row.cells[j]; j++) {
			let sanatizedHeader = table.rows[1].cells[j].innerHTML.replace(/▲/g, '');
			sanatizedHeader = sanatizedHeader.replace(/▼/g, '');
			col.innerHTML = changeToReadable(sanatizedHeader, data[i - 2][sanatizedHeader], data['id']);
		}
	}
}
// ▲ Ascending i.e abc
// ▼ Descending i.e cba

function tableHeaderClick(id) {
	let tableColHeader = document.getElementById(id);
	for (let key in tableHeaders) {
		let keyLower = key.toLowerCase();
		if (keyLower !== id) {
			let colHeader = document.getElementById(keyLower);
			if (colHeader !== null) {
				colHeader.innerHTML = colHeader.innerHTML.replace(/▲/g, '');
				colHeader.innerHTML = colHeader.innerHTML.replace(/▼/g, '');
			}
		}
	}
	let arrow;
	normalizedKey = id.charAt(0).toUpperCase() + id.slice(1);
	if (tableColHeader.innerHTML.indexOf("▲") !== -1) {
		tableColHeader.innerHTML = tableColHeader.innerHTML.replace(/▲/g, '');
		arrow = document.createTextNode("▼");
		if (normalizedKey === "Age") {
			tableData.sort(srt({
				key: normalizedKey,
				string: false
			}, true));
		} else {
			tableData.sort(srt({
				key: normalizedKey,
				string: true
			}, true));
		}
	} else {
		tableColHeader.innerHTML = tableColHeader.innerHTML.replace(/▼/g, '');
		arrow = document.createTextNode("▲");
		if (normalizedKey === "Age") {
			tableData.sort(srt({
				key: normalizedKey,
				string: false
			}, false));
		} else {
			tableData.sort(srt({
				key: normalizedKey,
				string: true
			}, false));
		}
	}
	tableColHeader.appendChild(arrow);
	updateTable(tableData, "animalTable", tableHeaders);
}

let urlParams = new URLSearchParams(window.location.search);
let animal = urlParams.get('animal');
let tableHeaders;
let tableData;
$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
});

let jsonURL = "../" + animal + "/";

let validKeys = ["dogs", "cats", "exotics"];

fetchJSONFile(jsonURL, function(data) {
	let validAnimal = false;
	// If they load just the page without any GET values
	if (animal === null) {
		validAnimal = true;
	}
	
	if (validKeys.indexOf(animal) > -1) {
			validAnimal = true;
	}

	
	if (validAnimal) {
		buildHtmlTable(data['tableHeadings'], data['animals'], "mainTableDiv");
	} else {
		let divContainer = document.getElementById("mainTableDiv");
		divContainer.innerHTML += "Something went wrong!";
	}
	tableData = data['animals'];
	tableHeaders = data['tableHeadings'];

});