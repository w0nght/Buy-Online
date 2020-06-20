<?php
	// listing.php
	// server side to control the item that entered by manager
	// put those items in the system - save into the xml file 
	
	header("Content-Type: text/xml");

	if (isset($_GET["itemname"]) && isset($_GET["itemprice"]) && isset($_GET["itemqty"]) && isset($_GET["itemdes"]) ) {
		// server picks up thses items as veriables
		$itemname = $_GET["itemname"];
		$itemprice = $_GET["itemprice"];
		$itemqty = $_GET["itemqty"];
		$itemdes = $_GET["itemdes"];
		
		$errMsg = "";

		if (empty($itemname)) {
			$errMsg .= "You must enter an item name. <br />";
		}
		if (empty($itemprice)) {
			$errMsg .= "You must give an item a price. <br />";
		}
		if (empty($itemqty)) {
			$errMsg .= "You must enter an item quantity. <br />";
		}
		if (empty($itemdes)) {
			$errMsg .= "You must enter an item description. <br />";
		}
		
		if ($errMsg != "") {
			echo $errMsg;
		}
		else {
			// declare xml file location
			$xmlfile = "../../data/goods.xml";
			// create the XML Document
			$doc = new DOMDocument();
			// declare the first id
			$id = 0;
			// initial values for quantity on hold & quantity sold are set to 0
			$qtyonhold = 0;
			$qtysold = 0;
			
			if (!file_exists($xmlfile)) { // if the xml file does not exist, create a root node $items
				$items = $doc->createElement("items");
				$doc->appendChild($items);
			}
			else { // load the xml file
				$doc->preserveWhiteSpace = FALSE; 
				$doc->load($xmlfile);
				// use length - attribute of DOMNodeList
				// count all "item" node elements & return as id
				$id = $doc->getElementsByTagName("item")->length;
			}

			//create a item node under items node
			$items = $doc->getElementsByTagName("items")->item(0);
			// create new node
			$item = $doc->createElement("item");
			// add the node to element		
			$items->appendChild($item);

			// create a id node ....
			$ID = $doc->createElement("id");
			$item->appendChild($ID);
			$idValue = $doc->createTextNode($id);
			$ID->appendChild($idValue);
			
			// create a Item Name node ....
			$ItemName = $doc->createElement("itemname");
			$item->appendChild($ItemName);
			$itemnameValue = $doc->createTextNode($itemname);
			$ItemName->appendChild($itemnameValue);

			// create a Item Price node ....
			$ItemPrice = $doc->createElement("itemprice");
			$item->appendChild($ItemPrice);
			$itempriceValue = $doc->createTextNode($itemprice);
			$ItemPrice->appendChild($itempriceValue);
			
			//create a Item Quantity node ....
			$ItemQty = $doc->createElement("itemqty");
			$item->appendChild($ItemQty);
			$itemqtyValue = $doc->createTextNode($itemqty);
			$ItemQty->appendChild($itemqtyValue);
			
			//create a Item Description node ....
			$ItemDes = $doc->createElement("itemdes");
			$item->appendChild($ItemDes);
			$itemdesValue = $doc->createTextNode($itemdes);
			$ItemDes->appendChild($itemdesValue);

			//create a Item Quantity On Hold node ....
			$QtyOnHold = $doc->createElement("qtyonhold");
			$item->appendChild($QtyOnHold);
			$qtyonholdValue = $doc->createTextNode($qtyonhold);
			$QtyOnHold->appendChild($qtyonholdValue);
			
			//create a Item Quantity Sold node ....
			$QtySold = $doc->createElement("qtysold");
			$item->appendChild($QtySold);
			$qtysoldValue = $doc->createTextNode($qtysold);
			$QtySold->appendChild($qtysoldValue);
			
			//save the xml file
			$doc->formatOutput = true;
			$doc->save($xmlfile);
			echo "The item has been listed in the system, and the item number is : $id<br /><br />";
		} 
	}
?>