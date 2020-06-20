<?php
  // cancelPurchase.php 
  // server side to cancel all items in the shopping cart
  // 1. remove entire record 
  // 2. reset on hold item & available quantity
  // 3. update goods xml file

  session_start();

  if (!isset($_SESSION["shoppingcart"])) {
    $_SESSION["shoppingcart"] = "";
  } 
  if ($_SESSION["shoppingcart"] !== "") {
    $shoppingcart = $_SESSION["shoppingcart"];
    // unset the entier shopping cart session
    unset($_SESSION["shoppingcart"]);
    echo "cart empty";
  }
  $xmlFile = "../../data/goods.xml";
  $doc = new DOMDocument();
  $doc->load($xmlFile);

  $root = $doc->getElementsByTagName("items")->item(0);
  $item = $doc->getElementsByTagName("item");

  foreach ($item as $node) {
    // get the nodes value
    $qty = $node->getElementsByTagName("itemqty")->item(0)->nodeValue;
    $qtyonhold = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;

    // reset the sold item back to 0
    if ($qtyonhold > 0) {
      $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 0;
      $node->getElementsByTagName("itemqty")->item(0)->nodeValue = $qty + $qtyonhold;
    }
  }
  // update the xml doc 
  $doc->save($xmlFile); //save to xml
  // echo $doc->C14N();
?>