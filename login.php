<?php
  // login.php
  // server-side php file to validate customer login inputs against the txt file
  header('Content-Type: text/xml');

  if (isset($_GET["email"]) && isset($_GET["password"]) ) {
    // server picks up thses items as veriables
    $email = $_GET["email"];
    $password = $_GET["password"];
    $errMsg = "";

    if (empty($email)) {
      $errMsg .= "You must enter an email. <br />";
    }
    if (empty($password)) {
      $errMsg .= "You must enter a password. <br />";
    }
    
    if ($errMsg != "") {
      echo $errMsg;
    }
    else {  // no error, start checking with the xml file
      // declare xml file location
      $xmlfile = "../../data/customer.xml";
      // create DOM doc and load the xml
      $doc = new DOMDocument();
      $doc->load($xmlfile);
      // look for the customer node
      $customer = $doc->getElementsByTagName("customer");
      // loop through all customer nodes
      foreach($customer as $node) {
        $customerEmail = $node->getElementsByTagName("email");
        $customerEmail = $customerEmail->item(0)->nodeValue;

        $customerPw = $node->getElementsByTagName("password");
        $customerPw = $customerPw->item(0)->nodeValue;
        
        if ($customerEmail == $email && $customerPw == $password) {
          // store the email in session variable
          session_start();
          $_SESSION['customer'] = $email;
          // send the msg confirm back to client, so client could redirect the page 
          echo "confirmed";
          exit();
        }
      }
      echo "Invalid email or password.<br />";
    } 
  }
?>