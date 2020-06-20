<?php
	// register.php 
	// server-side php file to validate inputs from register page
	// store the customer details into customer xml file
	
	header('Content-Type: text/xml');

	if (isset($_GET["firstname"]) && isset($_GET["lastname"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["confpassword"]) && isset($_GET['phone']) ) {
		// server picks up thses items as veriables
		$firstname = $_GET["firstname"];
		$lastname = $_GET["lastname"];
		$email = $_GET["email"];
		$password = $_GET["password"];
		$confpassword = $_GET["confpassword"];
		$phone = $_GET["phone"];
		
		$errMsg = "";

		$xml = file_get_contents('../../data/customer.xml');
		if (empty($firstname)) {
			$errMsg .= "You must enter a first name. <br />";
		}
		
		if (empty($lastname)) {
			$errMsg .= "You must enter a last name. <br />";
		}
		
		// search the xml file if email exists
		if (empty($email)) {
			$errMsg .= "You must enter an email. <br />";
		} elseif (strpos($xml, "<email>$email</email>") !== false) {
			$errMsg .= "This email has already been registered. Please use other email. <br />";
		}

		// check regular expression if phone number is provided
		if (!empty($phone) && !(preg_match("/^\([0]{1}[0-9]{1}\)[0-9]{8}$/", $phone) || preg_match("/^[0]{1}[0-9]{1}\s[0-9]{8}$/", $phone)) ) {
			$errMsg .= "Your phone number number must follow 0d dddddddd or (0d)dddddddd format. <br />";
		}

		if (empty($password)) {
			$errMsg .= "You must enter a password. <br />";
		}

		if (empty($confpassword)) {
			$errMsg .= "You must re-enter a password. <br />";
		}  
		
		// check if passwords are match
		if ((!empty($password) && !empty($confpassword)) && ($password != $confpassword)) {
			$errMsg .= "Passwords don't match. Please try again. <br />";
		}
		
		if ($errMsg != "") {
			echo $errMsg;
		}
		else {
			$xmlfile = '../../data/customer.xml';
			$doc = new DomDocument();
			// declare the first id
			$id = 0;
			
			if (!file_exists($xmlfile)) { // if the xml file does not exist, create a root node $customers
				$customers = $doc->createElement('customers');
				$doc->appendChild($customers);
			}
			else { // load the xml file
				$doc->preserveWhiteSpace = FALSE; 
				$doc->load($xmlfile);
				// use length - attribute of DOMNodeList
				// count all 'customer' node elements & return as id
				$id = $doc->getElementsByTagName('customer')->length;

				// another way:
				// DOMXPATH return an object & could not be converted to int
				// $xp = new DOMXPath($doc);
				// evaluate the given XPath expression
				// echo $xp->evaluate('count(//customers/customer)');
			}

			//create a customer node under customers node
			$customers = $doc->getElementsByTagName('customers')->item(0);
			// create new node
			$customer = $doc->createElement('customer');
			// add the node to element		
			$customers->appendChild($customer);

			// create an id node ....
			$ID = $doc->createElement('id');
			$customer->appendChild($ID);
			$idValue = $doc->createTextNode($id);
			$ID->appendChild($idValue);
			
			// create a Fisrt Name node ....
			$FName = $doc->createElement('firstname');
			$customer->appendChild($FName);
			$fnameValue = $doc->createTextNode($firstname);
			$FName->appendChild($fnameValue);

			// create a Last Name node ....
			$LName = $doc->createElement('lastname');
			$customer->appendChild($LName);
			$lnameValue = $doc->createTextNode($lastname);
			$LName->appendChild($lnameValue);
			
			//create a Email node ....
			$Email = $doc->createElement('email');
			$customer->appendChild($Email);
			$emailValue = $doc->createTextNode($email);
			$Email->appendChild($emailValue);
			
			//create a pwd node ....
			$pwd = $doc->createElement('password');
			$customer->appendChild($pwd);
			$pwdValue = $doc->createTextNode($password);
			$pwd->appendChild($pwdValue);

			//create a phone node ....
			$Phone = $doc->createElement('phone');
			$customer->appendChild($Phone);
			$phoneValue = $doc->createTextNode($phone);
			$Phone->appendChild($phoneValue);
			
			//save the xml file
			$doc->formatOutput = true;
			$doc->save($xmlfile);
			echo "Dear $firstname, you have successfully registered! You are now a memeber of BuyOnline!!<br /><br />";
			// link to head back to main page will appear
			echo "Please navigate to <a href=\"buyonline.htm\">Home page</a> for Login.<br />";
		} 
	}
?>