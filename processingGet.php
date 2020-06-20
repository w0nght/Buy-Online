<?php
  // processingGet.php
  // server side to retrieve the process sold items data from goods.xml 
  // only those items with non-zero quantity sold items will be sent back to the client

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
    foreach ($xpath->query("/items/item[qtysold<=0]") as $node) {
      $node->parentNode->removeChild($node);
    }
    echo $doc->C14N(); // send the xml response back to the client
  }
?>
