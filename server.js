// Server.php
// client-side javascript file - get inputs from register, login, mlogin & send to php server side
// process all the on load functions

// define xhr as an XMLHttpRequest object
var xhr = false;
if (window.XMLHttpRequest) {
	xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

// access user inputs from registration page
// pass them to register.php
function customerRegistration() {
	// set up variables from html elements
	var firstname = document.getElementById("firstname").value;
	var lastname = document.getElementById("lastname").value;
	var email = document.getElementById("email").value;
	var password = document.getElementById("password").value;
	var confpassword = document.getElementById("confpassword").value;
	var phone = document.getElementById("phone").value;

	// open method, open the xhr object with the given url
	xhr.open("GET", "register.php?firstname=" + firstname + "&lastname=" + lastname + "&email=" + encodeURIComponent(email) + "&phone=" + phone +"&password=" + password + "&confpassword=" + confpassword + "&id=" + Number(new Date), true);

	// call stateChanged when a change occurs
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("msg").innerHTML = xhr.responseText;
		}
	}
	// send an HTTP requests to the server
	xhr.send(null);
}

// access user inputs from manager login page 
// pass them to mlogin.php
function managerLogin() {
	// set up variables from html elements
	var manid = document.getElementById("manid").value;
	var password = document.getElementById("password").value;

	xhr.open("GET", "mlogin.php?manid=" + manid + "&password=" + password + "&id=" + Number(new Date), true);
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			// set up variable to store ajax server response
			var serverResponse = xhr.responseText;
			// alert(serverResponse);

			// check ajax response against any string
			if (serverResponse.trim() == "Found") {
				console.log(serverResponse + " -- equal found");
				// TODO: remove the login form
				// reset all input values
				document.getElementById("manid").value = "";
				document.getElementById("password").value = "";
				document.getElementById("msg").innerHTML = "";
				document.getElementById("loggedIn").innerHTML = "Welcome back "+ manid
				+ "! <br /><a href=\"listing.htm\">Listing</a><br />" 
				+	"<a href=\"processing.htm\">Processing</a><br />"
				+	"<a href=\"logout.htm\">Logout</a><br />";
				// disable the login button 
				document.getElementById("loginBtn").disabled = true;
			} else {
				console.log(serverResponse);
				// reset all input values
				document.getElementById("manid").value = "";
				document.getElementById("password").value = "";
				document.getElementById("msg").innerHTML = serverResponse;
			}
		}
	}
	xhr.send(null);
}

// access user inputs from customer login page 
// pass them to login.php
function customerLogin() {
	// set up variables from html elements
	var email = document.getElementById("email").value;
	var password = document.getElementById("password").value;

	// open method, open the xhr object with the given url
	xhr.open("GET", "login.php?password=" + password + "&email=" + encodeURIComponent(email) + "&id=" + Number(new Date), true);

	// call stateChanged when a change occurs
	xhr.onreadystatechange =  function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			// set up variable to store ajax server response
			var serverResponse = xhr.responseText;
			// alert(serverResponse);
			// redirect to buying page when a customer is confirmed
			if (serverResponse.trim() == "confirmed") {
				document.getElementById("msg").innerHTML = "";
				window.location.href="buying.htm";
			} else {
				document.getElementById("msg").innerHTML = serverResponse;
			}
		}
	}
	xhr.send(null);
}

// access manager inputs from listing page
// pass them to listing.php
function addNewItemListing () {
	// set up variables from html elements
	var itemname = document.getElementById("itemname").value;
	var itemprice = document.getElementById("itemprice").value;
	var itemqty = document.getElementById("itemqty").value;
	var itemdes = document.getElementById("itemdes").value;

	// open method, open the xhr object with the given url
	xhr.open("GET", "listing.php?itemname=" + itemname + "&itemprice=" + itemprice + "&itemqty=" + itemqty +"&itemdes=" + itemdes+ "&id=" + Number(new Date), true);

	// call stateChanged when a change occurs
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			// set up variable to store ajax server response
			var serverResponse = xhr.responseText;
			console.log(serverResponse);
			// check ajax response against any string
			if (!serverResponse == "") {
				// reset the form
				document.getElementById("itemname").value = "";
				document.getElementById("itemprice").value = "";
				document.getElementById("itemqty").value = "";
				document.getElementById("itemdes").value = "";
			}
			document.getElementById("msg").innerHTML = serverResponse;
		}
	}
	xhr.send(null);
}

// refresh the shopping diaplay table on the buying page in every 10 seconds
function loadShoppingDisplay() {
	getXmlGoodsData();
	setInterval(function() {
		getXmlGoodsData();
		// displayShoppingCart();
	}, 10000);
}
// fetch data from goods.xml 
function getXmlGoodsData() {
	xhr.open("GET", "buying.php", true);
	// force the response to be parsed as XML
	xhr.overrideMimeType("text/xml");

	xhr.onreadystatechange = function() { 
		if ((xhr.readyState == 4) && (xhr.status == 200)) { 
			var serverXMLResponse = xhr.responseXML;
			if (serverXMLResponse !== "") {
				var tabletag = document.getElementById("catalog");
				tabletag.innerHTML = "";
				row = "<table>";
				row += "<tr><th>Item Number</th><th>Name</th><th>Description</th>"
				row += "<th>Price</th><th>Quantity</th><th>Add</th></tr>";
				var item = serverXMLResponse.getElementsByTagName("item");

				// loop through all "item"
				for (var i = 0; i < item.length; i ++) {
					var id = item[i].getElementsByTagName("id")[0].childNodes[0].nodeValue;
					var itemname = item[i].getElementsByTagName("itemname")[0].childNodes[0].nodeValue;
					var itemprice = item[i].getElementsByTagName("itemprice")[0].childNodes[0].nodeValue;
					var itemdes = item[i].getElementsByTagName("itemdes")[0].childNodes[0].nodeValue;
					var itemqty = item[i].getElementsByTagName("itemqty")[0].childNodes[0].nodeValue;

					row += "<tr>"
					+ "<td>" + id + "</td>"
					+ "<td>" + itemname + "</td>"
					+ "<td>" + itemdes + "</td>"
					+ "<td>" + itemprice + "</td>"
					+ "<td>" + itemqty + "</td>"
					+ "<td>" 
					+ "<button onclick=\"addToCart(" + id + ")\">Add one to cart</button>"
					+ "</td>"
					+ "</tr>";
				}
				row += "</table>";
				if (item.length != 0) {
					tabletag.innerHTML = row;
				}
			} else {
				document.getElementById("catalog").innerHTML = "Unable to render shopping catalog.<br />";
			}
		} 
	} 
	xhr.send(null);
}

function displayShoppingCart() {
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		// set up variable to store ajax response
		var ajaxResult = xhr.responseText;
		var serverXMLResponse = xhr.responseXML;
		console.log(ajaxResult, " tet response - ");

		if (ajaxResult.trim() == "unavailable") {
			// alert msg - when item is not available 
			alert("Sorry, this item is not available for sale.");
		} 
		if (serverXMLResponse !== "") {
			document.getElementById("cartname").innerHTML = "Shopping Cart";
			var carttag = document.getElementById("cart");
			carttag.innerHTML = "";

			row = "<table>";
			row += "<tr><th>Item Number</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>";
			var item = serverXMLResponse.getElementsByTagName("item"); // only response xml can let us print the table
			var total = 0;
			for (i=0;i<item.length;i++) {
				var id = item[i].getElementsByTagName("id")[0].childNodes[0].nodeValue;
				var itemprice = item[i].getElementsByTagName("price")[0].childNodes[0].nodeValue;
				var itemqty = item[i].getElementsByTagName("qty")[0].childNodes[0].nodeValue;
				total += itemprice * itemqty;

				row += "<tr>"
				+ "<td>" + id + "</td>"
				+ "<td>" + itemprice + "</td>"
				+ "<td>" + itemqty + "</td>"
				+ "<td>" 
				+ "<button onclick=\"removeFromCart(" + id + ")\">Remove from cart</button>"
				+ "</td>"
				+ "</tr>";
			}
			row += "<tr><td colspan=\"3\"><b>Total:</b></td>"
				+ "<td><b>$" + total + "</b></td>";
			row += "<tr><td colspan=\"2\">"
				+ "<button onclick=\"confirmPurchase(" + total + ")\">Confirm Purchase</button></td>"
				+ "<td colspan=\"2\">"
				+ "<button onclick=\"cancelPurchase()\">Cancel Purchase</button></td>"
				+ "</tr></table>";
			if (item.length != 0) {
				carttag.innerHTML = row;
			}
		}
	}
}

// to remove a selected item from shopping cart 
// only that table row will be removed
function removeFromCart(itemid) {
	xhr.open("GET", "removeFromCart.php?itemid=" + itemid + "&id=" + Number(new Date), true);
	// force the response to be parsed as XML
	xhr.overrideMimeType("text/xml");

	xhr.onreadystatechange = displayShoppingCart;
	xhr.send(null);	
}

// to cancel the entire purchase - table will be removed
function cancelPurchase() {
	xhr.open("GET", "cancelPurchase.php", true);
	// force the response to be parsed as XML
	xhr.overrideMimeType("text/xml");
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			// set up variable to store ajax response
			var serverResponse = xhr.responseText;
			// do not have any xml reponse expecting , just text
			console.log(serverResponse);
			document.getElementById("msg").innerHTML = serverResponse;

			if (serverResponse.trim() == "cart empty") {
				document.getElementById("cartname").innerHTML = "";
				document.getElementById("cart").innerHTML = "";
				document.getElementById("msg").innerHTML = "";
				alert("Your purchase request has been cancelled, welcome to shop next time.");
			}
		}
	}
	xhr.send(null);
}

// to confirm the purchase
function confirmPurchase(total) {
	// document.getElementById("msg").innerHTML = "confirm pressed and the total would be " + total;
	// force the response to be parsed as XML
	xhr.open("GET", "confirmPurchase.php", true);
	xhr.overrideMimeType("text/xml");

	xhr.onreadystatechange = function () {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			var serverResponse = xhr.responseText;
			console.log(serverResponse);
			document.getElementById("msg").innerHTML = serverResponse;

			if (serverResponse.trim() == "cart empty") {
				document.getElementById("cartname").innerHTML = "";
				document.getElementById("cart").innerHTML = "";
				document.getElementById("msg").innerHTML = "";
				alert("Your purchase has been confirmed, the total amount due to pay is $" + total);
			}
		}
	}	
	xhr.send(null);
}

// to add an item to shopping cart
// a table of shopping cart will be created
function addToCart(itemid) {
	xhr.open("GET", "addToCart.php?itemid=" + itemid + "&id=" + Number(new Date), true);
	// force the response to be parsed as XML
	xhr.overrideMimeType("text/xml");
	xhr.onreadystatechange = displayShoppingCart;
	xhr.send(null);	
}

// to display a table of those non-zero sold items from goods.xml on processing page
function getProcessData() {
	xhr.open("GET", "processingGet.php", true);
	// force the response to be parsed as XML
	xhr.overrideMimeType("text/xml");
	xhr.onreadystatechange = function() { 
		if ((xhr.readyState == 4) && (xhr.status == 200)) { 
			var serverXMLResponse = xhr.responseXML;
			var msg = document.getElementById("msg");

			if (serverXMLResponse !== "") {
				var tabletag = document.getElementById("form");
				tabletag.innerHTML = "";
				row = "<table>";
				row += "<tr><th>Item Number</th><th>Item Name</th><th>Price</th>";
				row += "<th>Quantity Available</th><th>Quantity on Hold</th><th>Quantity Sold</th></tr>";
				
				var item = serverXMLResponse.getElementsByTagName("item");

				// if the return item is empty ---> <items></items> <---- only this
				if (item.length <= 0) {
					msg.innerHTML = "All items had been processed. Please check again later.";
				}

				// loop through all "item"
				for (var i = 0; i < item.length; i ++) {
					var id = item[i].getElementsByTagName("id")[0].childNodes[0].nodeValue;
					var itemname = item[i].getElementsByTagName("itemname")[0].childNodes[0].nodeValue;
					var itemprice = item[i].getElementsByTagName("itemprice")[0].childNodes[0].nodeValue;
					var itemqty = item[i].getElementsByTagName("itemqty")[0].childNodes[0].nodeValue;
					var itemqtyonhold = item[i].getElementsByTagName("qtyonhold")[0].childNodes[0].nodeValue;
					var itemqtysold = item[i].getElementsByTagName("qtysold")[0].childNodes[0].nodeValue;

					row += "<tr>"
					+ "<td>" + id + "</td>"
					+ "<td>" + itemname + "</td>"
					+ "<td>" + itemprice + "</td>"
					+ "<td>" + itemqty + "</td>"
					+ "<td>" + itemqtyonhold + "</td>"
					+ "<td>" + itemqtysold + "</td>"
					+ "</tr>";
				}
				row += "<tr><td colspan=\"6\">"
				+ "<button onclick=\"process()\">Process</button>"
				+ "</td></tr></table>";
				if (item.length != 0) {
					tabletag.innerHTML = row;
				}
			} else {
				tabletag.innerHTML = "Unable to render the process form.<br />";
			}
		} 
	} 
	xhr.send(null);		
}

// process items in the process table
function process() {
	xhr.open("GET", "process.php", true);
	xhr.onreadystatechange = getProcessData;
	xhr.send(null);
}

// to run the php"s destroy session
function logout() {
	xhr.open("GET", "logout.php", true);
	xhr.onreadystatechange = function() {
		if ((xhr.readyState == 4) && (xhr.status == 200)) {
			document.getElementById("msg").innerHTML = xhr.responseText;
		}
	}
	xhr.send(null);
}

// reset customer register form
function clearRegisterForm() {
	document.getElementById("firstname").value = "";
	document.getElementById("lastname").value = "";
	document.getElementById("email").value = "";
	document.getElementById("password").value = "";
	document.getElementById("confpassword").value = "";
	document.getElementById("phone").value = "";
	document.getElementById("msg").innerHTML = "";
}
// reset manager login form
function clearMLoginForm() {
	document.getElementById("manid").value = "";
	document.getElementById("password").value = "";
}
// reset customer login form
function clearLoginForm() {
	document.getElementById("email").value = "";
	document.getElementById("password").value = "";
}

// reset add new item form - listing
function clearAddNewItemForm() {
	document.getElementById("itemname").value = "";
	document.getElementById("itemprice").value = "";
	document.getElementById("itemqty").value = "";
	document.getElementById("itemdes").value = "";
	document.getElementById("msg").innerHTML = "";
}