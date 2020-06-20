<?php
  // addToCart.php
  // server side 
  // 1. to check if the selected item is available 
  // 2. handle the update - put item on hold, subtract from item qty - update xml 
  // 3. handle shopping cart
  // 4. return result to the client

  header("Content-Type: text/xml");

  session_start();
 
  // server picks up thses items as veriables
  $itemid = $_GET["itemid"];
  $errMsg = "";

  itemAvailabilityCheck($itemid);

  // check if item available 
  function itemAvailabilityCheck($itemid) {
    // declare xml file location
    $xmlfile = "../../data/goods.xml";
    $doc = new DOMDocument();

    if (!file_exists($xmlfile)) { // if the xml file does not exist,
      exit("Unable to process. File not exists. <br />");
    } else { // load the xml file
      $doc->load($xmlfile);
      
      // look for the item node
      $item = $doc->getElementsByTagName("item");
      // loop through all item nodes
      foreach($item as $node) {
        // get the item quantity node & id node
        $availableId = $node->getElementsByTagName("id");
        $availableId = $availableId->item(0)->nodeValue;

        $availableQty = $node->getElementsByTagName("itemqty");
        $availableQty = $availableQty->item(0)->nodeValue;

        $availableItemPrice = $node->getElementsByTagName("itemprice");
        $availableItemPrice = $availableItemPrice->item(0)->nodeValue;

        // look for that item, and if its quantity is greater than 0
        if ($itemid == $availableId) {
          if ($availableQty > 0) {
            putItemOnHold($availableId, $availableItemPrice);
            break;  // exit the loop on the first iteration, below won't be executed
          } else {
            exit ("unavailable");
          }
        }
      }
    }
  }

  // this item is available, now we put it on hold, update the goods xml
  function putItemOnHold($itemid, $itemprice) {
    $xmlfile = "../../data/goods.xml";
    $doc = new DOMDocument();
    $doc->preserveWhiteSpace = FALSE; 

    $doc->load($xmlfile);
    
    // look for the item node
    $item = $doc->getElementsByTagName("item");
    
    // loop through all item nodes
    foreach($item as $node) {
      $availableId = $node->getElementsByTagName("id");
      $availableId = $availableId->item(0)->nodeValue;

      // look for that item
      if ($itemid == $availableId) {
        // get the item quantity& quantity on hold node
        $availableQty = $node->getElementsByTagName("itemqty")->item(0)->nodeValue;
        $availableQtyOnHold = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;

        // update the quantity (-1) & quantity on hold (+1)
        $node->getElementsByTagName("itemqty")->item(0)->nodeValue = $availableQty - 1;
        $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = $availableQtyOnHold + 1;
      }
    }
    $doc->formatOutput = true;
    $doc->save($xmlfile);

    updateShoppingCart($itemid, $itemprice);
  }

  // next, we update the shopping cart
  function updateShoppingCart($itemid, $itemprice) {
    // create a shopping cart session if not exists
    if (!isset($_SESSION["shoppingcart"])) {
      $_SESSION["shoppingcart"] = "";
    }
    // if cart is not empty
    if ($_SESSION["shoppingcart"] !== "") {
      $shoppingcart = $_SESSION["shoppingcart"];

      if (isset($shoppingcart[$itemid])) {  // if that item already exists
        // then add 1 qty on that item
        $value = $shoppingcart[$itemid];
        $value["qty"] = $value["qty"] + 1;

        $shoppingcart[$itemid] = $value;
        $_SESSION["shoppingcart"] = $shoppingcart;  // save the adjusted cart to session variable
        toXml($shoppingcart);  // push result to xml and return to client
      } else { // first copy of that item
        // item not in the cart
        // then add that item id to the cart, qty == 1
        $value = array(); // Key: itemid  Value: qty, itemprice
        $value["qty"] = "1";
        $value["price"] = $itemprice;

        $shoppingcart[$itemid] = $value;
        $_SESSION["shoppingcart"] = $shoppingcart;  // save the adjusted cart to session variable
        toXml($shoppingcart);  // push result to xml and return to client
      }
    } elseif ($_SESSION["shoppingcart"] == "") {  // shopping cart session is empty
      $value = array(); // Key: itemid  Value: qty, itemprice
      $value["qty"] = "1";
      $value["price"] = $itemprice;

      $shoppingcart = array();
      $shoppingcart[$itemid] = $value;
      $_SESSION["shoppingcart"] = $shoppingcart;  // save the adjusted cart to session variable
      toXml($shoppingcart);  // push result to xml and return to client
    }
  }

  // write to the shopping cart xml
  function toXml($shoppingcart) {
    header("Content-Type: text/xml");
    $doc = new DOMDocument("1.0");
    $doc->preserveWhiteSpace = FALSE; 

    $cart = $doc->createElement("cart");
    $cart = $doc->appendChild($cart);

    foreach ($shoppingcart as $Item => $ItemValue) {
      // create an item node under cart node
      $item = $doc->createElement("item");
      // add the node to element		
      $item = $cart->appendChild($item);
      
      // create an id node
      $itemid = $doc->createElement("id");
      $itemid = $item->appendChild($itemid);
      $itemidValue = $doc->createTextNode($Item);
      $itemidValue = $itemid->appendChild($itemidValue);
        
      // create a quantity node
      $itemqty = $doc->createElement("qty");
      $itemqty = $item->appendChild($itemqty);
      $itemqtyValue = $doc->createTextNode($ItemValue["qty"]);
      $itemqtyValue = $itemqty->appendChild($itemqtyValue);

      // create a price node
      $itemprice = $doc->createElement("price");
      $itemprice = $item->appendChild($itemprice);
      $itempriceValue = $doc->createTextNode($ItemValue["price"]);
      $itempriceValue = $itemprice->appendChild($itempriceValue);
    }
    //save the xml file
    $doc->formatOutput = true;
    $strXml = $doc->C14N();  // it sent the xml fine
    echo $strXml;
  }
?>