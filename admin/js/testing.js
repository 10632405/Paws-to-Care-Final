describe("Check that the table has been filled", function() { //suite
/*
	jasmine.DEFAULT_TIMEOUT_INTERVAL = 100000;
	beforeAll((done) => {
		
		document.addEventListener("DOMContentLoaded", function(event) {
		console.log("DOM fully loaded and parsed");
		done();
  		});
	});
*/
beforeAll(function(done) {
  window.setTimeout(done, 1000);
});

	it("should have Name in the first header cell", function() { //spec
		let table = document.getElementById("animalTable");
		expect(table.rows[1].cells[0].innerHTML).toBe("Name");
	});
});


describe("Check that when we go to page two that the first animal name is", function() { //suite
	beforeAll(function(done) {
		let pageTwo = document.getElementById("paginationItems").getElementsByTagName("li")[2].childNodes[0];
		
		console.log(pageTwo);
		
		paginationClick(pageTwo);
		done();
	});
	it("should have animal name in the first cell", function() { //spec
		

		
		let table = document.getElementById("animalTable");
		
		if (animal == "dogs"){
			expect(table.rows[2].cells[0].innerHTML).toBe("Ezequiel");
		}else if (animal == "cats"){
			expect(table.rows[2].cells[0].innerHTML).toBe("Manuel");
		}else if (animal == "exotics"){
			expect(table.rows[2].cells[0].innerHTML).toBe("Ericka");
		}

	});
});
/*
describe("Check that when we filter name with 'a' that the first animal name is", function() { //suite
	it("should have Name in the first header cell", function() { //spec
	});
});
describe("Check that when we sort by breed in acsending order that the fist animal name is", function() { //suite
	it("should have Name in the first header cell", function() { //spec
	});
});
describe("Check that the 2nd modal contains", function() { //suite
	it("should have Name in the first header cell", function() { //spec
	});

});
*/