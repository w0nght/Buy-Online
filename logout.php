<?php
  // logout.php
  // handle logout state information using sessions
  
  session_start();

  // if the session variable is empty
  // user will redirect to the login page
  if (!(isset($_SESSION["manager"]) || isset($_SESSION["customer"])) ) { 
    header("location: buyonline.htm");
    exit();
  }
  $html = ", please visit us again soon. <br />Here is a link to back to the <a href=\"buyonline.htm\">Home page</a>.";

  // logout button will destroy the session
  // the session variables will be set to unset
  // frees all session variables currently registered 

  // user will be redirect to the login page after logging out 
  if (isset($_SESSION["manager"])) {
    $manid = $_SESSION["manager"];
    echo "Thank you " .$manid. $html;
    session_destroy();
    unset($_SESSION["manager"]);

  } elseif (isset($_SESSION["customer"])) {
    $customerid = $_SESSION["customer"];
    echo "Thank you " .$customerid. $html;

    session_destroy(); 
    unset($_SESSION["customer"]);
  }
?>