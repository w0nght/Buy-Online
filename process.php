<?php
  // process.php 
  // server side to process all items in the process from
  // 1. remove entire item record when sold out
  // 2. reset record when sold > 0

  $xmlFile = "../../data/goods.xml";
  $doc = new DOMDocument();
  $doc->load($xmlFile);

  $root = $doc->getElementsByTagName("items")->item(0);
  $item = $doc->getElementsByTagName("item");

  foreach ($item as $node) {
    // get the nodes value
    $qty = $node->getElementsByTagName("itemqty")->item(0)->nodeValue;
    $qtyonhold = $node->getElementsByTagName("qtyonhold")->item(0)->nodeValue;
    $qtysold = $node->getElementsByTagName("qtysold")->item(0)->nodeValue;

    // reset the sold item back to 0
    if ($qtysold > 0) {
      $node->getElementsByTagName("qtysold")->item(0)->nodeValue = 0;
    }
    // remove completely sold item - both qty & on hold == 0
    if($qty == 0 && $qtyonhold == 0) {
      $root->removeChild($node);
    }
  }
  // update the xml doc 
  echo $doc->save($xmlFile); //save to xml
?>