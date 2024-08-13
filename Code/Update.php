<!DOCTYPE html>
<html>

<head>
  <title>Update Sponsorship Cost</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class = "body1">
  <header class="page-header">
    <h1>Formula 1 Database Application </h1>
  </header>
  <p text-align='left'>Return to Mainpage:
    <a href="https://www.students.cs.ubc.ca/~douxinyi/m4/MainUI.php">
      <button>Back</button>
    </a>
  </p>

  <h2 class="operations">Update Sponsorship Cost</h2>
  <form method="POST" action="Update.php"> 
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    Sponsorship Name: <input type="text" name="sponsorship_name"><br />
    New Cost: <input type="integer" name="amount"><br>
    <br>
    <input type="submit" value="Update" name="updateSubmit"></p>
  </form>


<?php
//Below code refers to oracle-test.txt provided in tutorial 7

require __DIR__ . '/#OracleFunctions.php';

function printResult($result) { //prints results from a select statement
    echo "<br><h3><font color='#2d4cb3'>Sponsorhsip Information and Cost:</h3>";
    echo "<table class='center'>";
    echo "<tr>
      <th><font color='#2d4cb3'>Sponsorhsip Name</th>
      <th><font color='#2d4cb3'>Cost (million $)</th>
      </tr>";
  
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr>
        <td><p align='center';>" . $row["0"] . "</p></td>
        <td><p align='center';>" . $row["1"] . "</p></td>
        </tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";
  }

function handleUpdateRequest() {
  global $db_conn;

  $input_sponsorship = $_POST['sponsorship_name'];

  $new_amount = $_POST['amount'];

  if (($input_sponsorship==NULL) || ($new_amount==NULL)){
    echo "<font color='#db2c20'><br />Please enter both fields. No changes made.</font>";
  } else {
    $result = executePlainSQL("UPDATE Sponsorship 
                               SET amount= '". $new_amount . "'
                               WHERE sponsorship_name = '". $input_sponsorship . "' ");
  }
 
               
  OCICommit($db_conn); 

}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
  if (connectToDB()) {

    if (array_key_exists('updateQueryRequest', $_POST)) {
      handleUpdateRequest();

      $result = executePlainSQL('SELECT sponsorship_name, amount
                                 FROM Sponsorship
                                 ORDER BY sponsorship_name');
      printResult($result);

      disconnectFromDB();
  }
}
}

if (isset($_POST['updateSubmit'])) {
  handlePOSTRequest();
}

?>

  </body>
</html>