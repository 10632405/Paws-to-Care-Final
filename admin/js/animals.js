//start on page 0 i.e. human page 1
let pageNum = 0;
let maxPages = 0;

const searchableCols = ["Name", "Breed", "Sex", "Shots", "Age - Birthdate", "Size", "Species", "Owners"];
const howManyPerPage = 10;
const maxPaginationButtons = 5;
const excludeCols = ["id", "ownerDetail", "noteDetail"];

let urlParams = new URLSearchParams(window.location.search);
let animal = urlParams.get('animal');
let tableHeaders;
let tableData;
let jsonURL = `../${animal}/`;
let validKeys = ["dogs", "cats", "exotics"];

$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
	
	if (validKeys.indexOf(animal) > -1) {
		let divContainer = document.getElementById("mainTableDivCenter");
		divContainer.innerHTML = "<div class='loader'></div>";
		
		$.ajax({
			url : jsonURL,
			type : 'GET',
			dataType:'json',
			success : function(data) {              
				buildHtmlTable(data['tableHeadings'], data['animals'], "mainTableDiv");
				tableData = data['animals'];
				tableHeaders = data['tableHeadings'];
				for (var key in tableData) {
					buildModal(atob(tableData[key]['noteDetail']), tableData[key]['Name'], "notes", tableData[key]['id']);
					// Build Owner Model. Make it look nice
					buildModal(tableData[key]['ownerDetail'], tableData[key]['Name'], "owner", tableData[key]['id']);
				}
    		},
			error : function(request,error)
			{
				let divContainer = document.getElementById("mainTableDiv");
				divContainer.innerHTML += "Something went wrong!";
    		}
		});

	}
});

function filterTable(id) {
	let textField = document.getElementById(`filter${id}`);
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
	// Insert Model
	let modelString = `<div class="modal" id="${modal}Modal${id}">`;
	modelString += `  <div class="modal-dialog">`;
	modelString += `    <div class="modal-content">`;
	modelString += `      <!-- Modal Header -->`;
	modelString += `      <div class="modal-header">`;
	modelString += `        <h4 class="modal-title">${modal.charAt(0).toUpperCase() + modal.slice(1)} for ${animalName}</h4>`;
	modelString += `        <button type="button" class="close" data-dismiss="modal">&times;</button>`;
	modelString += `      </div>`;
	modelString += `      <!-- Modal body -->`;
	modelString += `      <div class="modal-body"> ${string}`;
	modelString += `      </div>`;
	modelString += `      <!-- Modal footer -->`;
	modelString += `      <div class="modal-footer">`;
	modelString += `        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>`;
	modelString += `      </div>`;
	modelString += `    </div>`;
	modelString += `  </div>`;
	modelString += `</div>`;
	let main = document.getElementsByTagName("main");
	main[0].insertAdjacentHTML('afterend', modelString);
}

function changeToReadable(col, input, animalName, id) {
	if (col === "Notes") {
		return `<p><button type='button' class='btn btn-outline-secondary btn-sm' data-toggle='modal' data-target='#notesModal${id}' style='display: block;margin: auto;'>Notes</button></p>`;
	}
	if (col === "Owners") {
		return `<p><a href='#ownerModal${id}' data-toggle='modal' data-target='#ownerModal${id}'>${input}</a>`;
	}
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
			return `L - ${input}Lbs`;
		} else if (input > 20 && input <= 100) {
			return `M - ${input}Lbs`;
		} else {
			return `S - ${input}Lbs`;
		}
	}
	return input;
}

function buildHtmlTable(headerArray, dataArray, div) {
	let animalName = "";
	let col = [];
	for (let key in dataArray[0]) {
		if (col.indexOf(key) === -1 && !(excludeCols.indexOf(key) > -1)) {
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
			addToCell.placeholder = `Search by ${col[i]}`;
			addToCell.setAttribute('onkeyup', `filterTable('${col[i]}')`);
			addToCell.setAttribute('id', `filter${col[i]}`);
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
	// If the data is less than 10 rows then use the array length
	let rows = howManyPerPage;
	if (dataArray.length < howManyPerPage) {
		rows = dataArray.length;
	}
	// Create the Rows
	for (let i = 0; i < rows; i++) {
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
	// Table has been created let check to see if we need pagination
	if (dataArray.length > howManyPerPage) {
		maxPages = Math.ceil((dataArray.length / howManyPerPage)) - 1;
		createPaginationButtons(dataArray.length);
	}
}

function createPaginationButtons(numOfItems) {
	let content = "<div id='paginationButtons'>";
	content += "	<nav aria-label='pagination'>";
	content += "		<ul id='paginationItems' class='pagination pagination-sm justify-content-end' style='margin:15px 0'>";
	content += "		    <li class='page-item disabled'><a class='page-link' href='#' tabindex='-1' onclick='paginationClick(this); return false';>Previous</a></li>";
	content += "		    <li class='page-item active'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>1</a></li>";
	if (maxPages > maxPaginationButtons) {
		content += "		    <li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>2</a></li>";
		content += "		    <li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>3</a></li>";
		content += "		    <li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>4</a></li>";
		content += "		    <li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>5</a></li>";
	} else {
		for (let i = 0; i < maxPages; i++) {
			content += `		    <li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>${(i + 2)}</a></li>`;
		}
	}
	content += "    		<li class='page-item'><a class='page-link' href='#' onclick='paginationClick(this); return false;'>Next</a></li>";
	content += "		</ul>";
	content += "	</nav>";
	content += "</div>";
	let divAfterTable = document.getElementById("mainTableDiv");
	divAfterTable.insertAdjacentHTML('afterend', content);
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

function updateTable(data, tableID, tableHeaders, page) {
	let table = document.getElementById(tableID);
	for (let i = 2, row; row = table.rows[i]; i++) { // Start on row 1
		for (let j = 0, col; col = row.cells[j]; j++) {
			let sanatizedHeader = table.rows[1].cells[j].innerHTML.replace(/▲/g, '');
			sanatizedHeader = sanatizedHeader.replace(/▼/g, '');
			let arrayIndex = ((i - 2) + (page * howManyPerPage));
			
			if(arrayIndex < data.length){
				col.innerHTML = changeToReadable(sanatizedHeader, data[arrayIndex][sanatizedHeader], data[arrayIndex]["Name"], data[arrayIndex]["id"]);
			}else{
				col.innerHTML = "";
			}
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
	updateTable(tableData, "animalTable", tableHeaders, pageNum);
}

function paginationClick(ele) {
	let pageinationButtons = document.getElementById("paginationItems").getElementsByTagName("li");
	
	
	if (ele.innerHTML === "Next") {
		if (pageNum < maxPages) {
			pageNum++;
		}
		if (pageinationButtons[pageinationButtons.length - 2].classList.contains("active")){
			for (let i = 1; i < pageinationButtons.length - 1; i++){
				let newLabel = (parseInt(pageinationButtons[i].getElementsByTagName("a")[0].innerHTML)) + 1;
				pageinationButtons[i].getElementsByTagName("a")[0].innerHTML = newLabel;
			}
		}
	}
	if (ele.innerHTML === "Previous") {
		if (pageNum !== 0) {
			pageNum--;
		}
		
		if (pageinationButtons[1].classList.contains("active")){
			for (let i = 1; i < pageinationButtons.length - 1; i++){
				let newLabel = (parseInt(pageinationButtons[i].getElementsByTagName("a")[0].innerHTML)) - 1;
				pageinationButtons[i].getElementsByTagName("a")[0].innerHTML = newLabel;
			}
		}
	}
	
	if (ele.innerHTML !== "Previous" && ele.innerHTML !== "Next")
	{
		let pageNumber = parseInt(ele.innerHTML) - 1;
		
		if (pageNumber <= maxPages){
			pageNum = pageNumber;
		}
	}
	
		
	
	if (pageNum < maxPages) {
		pageinationButtons[0].classList.remove("disabled");
		pageinationButtons[pageinationButtons.length - 1].classList.remove("disabled");
	}
	if (pageNum === 0) {
		pageinationButtons[0].classList.add("disabled");
	}
	if (pageNum === maxPages) {
		pageinationButtons[pageinationButtons.length - 1].classList.add("disabled");
		pageinationButtons[0].classList.remove("disabled");
	}
	
	for (let i = 1; i < pageinationButtons.length - 1; i++){
		
		if ((parseInt(pageinationButtons[i].getElementsByTagName("a")[0].innerHTML) - 1) === pageNum){
			pageinationButtons[i].classList.add("active");
		}else{
			pageinationButtons[i].classList.remove("active");
		}
	}
	
	updateTable(tableData, "animalTable", tableHeaders, pageNum);
}