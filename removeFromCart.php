<?php
  // removedFromCart.php
  // server side to remove the item from cart
  // 1. handle the remove
  // - add the number of quantity in hold to item quantity
  // - subtract from item qty - update xml 
  // 3. handle shopping cart - unset the session version with the item id in it
  // 4. return result back to the client

  header("Content-Type: text/xml");

  session_start();
 
  $itemid = $_GET["itemid"];
  $errMsg = "";

  removeItemOnHold($itemid);
  
  // this item is available, now we put it on hold, update the goods xml
  function removeItemOnHold($itemid) {
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

        // update the quantity & quantity on hold 
        $node->getElementsByTagName("itemqty")->item(0)->nodeValue = $availableQty + $availableQtyOnHold;
        $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 0;
      }
    }
    $doc->formatOutput = true;
    $doc->save($xmlfile);

    updateShoppingCart($itemid);
  }

  // next, we update the shopping cart
  function updateShoppingCart($itemid) {
    if (!isset($_SESSION["shoppingcart"])) {
      $_SESSION["shoppingcart"] = "";
    }
    // if cart is not empty
    if ($_SESSION["shoppingcart"] !== "") {
      $shoppingcart = $_SESSION["shoppingcart"];

      if (isset($shoppingcart[$itemid])) { // that item id still in that shopping cart session variable
        // unset the session variable with this item id in it
        unset($shoppingcart[$itemid]);
        $_SESSION["shoppingcart"] = $shoppingcart;  // save the adjusted cart to session variable

        toXml($shoppingcart);  // push result to xml and return 
      }
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