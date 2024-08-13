<?php


$success = True; 
$db_conn = NULL; 
$show_debug_alert_messages = False; 

function debugAlertMessage($message)
{
  global $show_debug_alert_messages;

  if ($show_debug_alert_messages) {
    echo "<script type='text/javascript'>alert('" . $message . "');</script>";
  }
}

function executePlainSQL($cmdstr)
{
  global $db_conn, $success;

  $statement = OCIParse($db_conn, $cmdstr);

  if (!$statement) {
    echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
    $e = OCI_Error($db_conn); 
    echo htmlentities($e['message']);
    $success = False;
  }

  $r = OCIExecute($statement, OCI_DEFAULT);
  if (!$r) {
    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
    $e = oci_error($statement); 
    echo htmlentities($e['message']);
    $success = False;
  }

  return $statement;
}

function executeBoundSQL($cmdstr, $list)
{
  global $db_conn, $success;
  $statement = OCIParse($db_conn, $cmdstr);

  if (!$statement) {

    $e = OCI_Error($db_conn);
    echo htmlentities($e['message']);
    $success = False;
  }

  foreach ($list as $tuple) {
    foreach ($tuple as $bind => $val) {
      OCIBindByName($statement, $bind, $val);
      unset($val); 
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {

      $e = OCI_Error($statement); 
      echo "<br>";
      $success = False;
    }
  }
}

function connectToDB()
  {
    global $db_conn;
    //change to your cwl and password
    $db_conn = OCILogon("ora_douxinyi", "a84855964", "dbhost.students.cs.ubc.ca:1522/stu");
    if ($db_conn) {
      debugAlertMessage("Database is Connected");
      return true;
    } else {
      debugAlertMessage("Cannot connect to Database");
      $e = OCI_Error(); // For OCILogon errors pass no handle
      echo htmlentities($e['message']);
      return false;
    }
  }

  function disconnectFromDB()
  {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
  }


?>