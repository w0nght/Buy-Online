
<?php
// mlogin.php 
// php file to process manager login from mlogin.htm
  session_start();
  header("Content-type: text/xml"); // have to set this for IE

  if(isset($_GET['manid']) && isset($_GET['password']) ) {
    // set up variables 
    $manid = $_GET["manid"];
    $password = $_GET["password"];
    $errMsg = "";

    if (empty($manid)) {
      $errMsg .= "Please enter manager id.<br />";
    }
    if (empty($password)) {
      $errMsg .= "Please enter a password.<br />";
    } 
    if ($errMsg != "") {
      echo $errMsg;
    } else {
      // no error, start looking into the txt file for matches
      $userInfo = $manid .", " .$password;

      // declare file location
      $file = file("../../data/manager.txt");

      // loop through the file
      for ($i=0;$i<count($file);$i++) {
        // declare each line ends with \n
        $currentLine=explode("\n", $file[$i]);
        // echo $currentLine[0], " line " ,$i, "<br />";
        if (trim($currentLine[0]) == trim($userInfo)) {
          // match found!
			    // store the manger id in session variable
          $_SESSION['manager'] = $manid;
          $isLogin = true;
          break;  // exit the loop - go to display login msg
        } else {
          $isLogin = false;
        }
      }
      // display login message - failure/success
      if ($isLogin == false) {
        echo "Invaild manager id or password.";
      } elseif($isLogin == true) {
        echo "Found";
      }
    }
  }
?>