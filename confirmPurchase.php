<?php
  // confirmPurchase.php
  // server side to confirm the purchase
  // 1. get all item in the shopping cart session variable
  // 2. unset the shopping cart session variable
  // 3. update the goods xml file 
  header("Content-Type: text/xml");

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

  // update the goods xml
  $xmlFile = "../../data/goods.xml";
  $doc = new DOMDocument();
  $doc->preserveWhiteSpace = FALSE; 
  $doc->load($xmlFile);
  
  // look for the item node
  $root = $doc->getElementsByTagName("items")->item(0);
  $item = $doc->getElementsByTagName("item");
  
  // loop through all item nodes
  foreach($item as $node) {
    // $availableId = $node->getElementsByTagName("id");
    // $availableId = $availableId->item(0)->nodeValue;
    $qtyonhold = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;

    // get those quantity none zero item
    // look for that item
    if ($qtyonhold >= 0) {
      // get the item quantity sold node
      $qtySold = $node->getElementsByTagName("qtysold")->item(0)->nodeValue;

      // update the quantity on hold & quantity sold
      $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue = 0;
      $node->getElementsByTagName("qtysold")->item(0)->nodeValue = $qtyonhold;
    }
  }
  // $doc->formatOutput = true;
  $doc->save($xmlFile);
  // echo $doc->C14N();

?>