<?php
  // buying.php
  // server side to retrieve data from goods.xml every 10 seconds
  // only those items with quantity greater than 0 will be sent

  header("Content-Type: text/xml");

  $xmlFile = "../../data/goods.xml";
  $doc = new DOMDocument;

  if (!file_exists($xmlFile)) { // if the xml file does not  exists
    echo "System Error: File not exists.";
    exit();
  } else { // load the xml file
    $doc->load($xmlFile);
    $xpath = new DOMXPath($doc);
    // go through each item of the xpath result
    foreach ($xpath->query("/items/item[itemqty<=0]") as $node) {
      $node->parentNode->removeChild($node);
    }
    echo $doc->C14N(); // send the xml response back to the client
  }
?>