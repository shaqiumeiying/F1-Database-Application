<!DOCTYPE html>
<html>

<head>
  <title>Insert circuits </title>
  <link rel="stylesheet" href="styles.css">
</head>

<body class="body1">
  <header class="page-header">
    <h1>Formula 1 Database Application </h1>
  </header>


  <p text-align='left'>Return to Mainpage:
    <a href="https://www.students.cs.ubc.ca/~douxinyi/m4/MainUI.php">
      <button>Back</button>
    </a>
  </p>

  <h2 class="operations">Adding new Circuits</h2>
   
   <form method="POST" action="InsertAndDeleteUI.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    City: <input type="text" name="city"><br/>
    Circuit Name: <input type="text" name="circuit_name"><br/>
    Country: <input type="text" name="country"><br/>
    Longitude: <input type="real" name="longitude"><br/>
    Latitude: <input type="real" name="latitude"><br/> 
    <br> 
   
    <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>


  <h2 class="operations">Delete Circuit</h2>
 
  <form method="POST" action="InsertAndDeleteUI.php">
    <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
    Circuit Name: <input type="text" name="circuit_name"><br>
    <br> 
    <input type="submit" value="Delete" name="deleteSubmit"></p>
  </form>
  

  <br>

  <?php
  
  require __DIR__ . '/#OracleFunctions.php';

  function printResult($result)
  {
    echo "<br><h3><font color='#2d4cb3'>Circuit Information:</h3>";
    echo "<table class='center'>";
    echo "<tr>
    <th><font color='#2d4cb3'>City</th>
    <th><font color='#2d4cb3'>Circuit Name</th>
    <th><font color='#2d4cb3'>Country</th>
    <th><font color='#2d4cb3'>Longitude</th>
    <th><font color='#2d4cb3'>Latitude</th>
    </tr>";

    while ($row = oci_fetch_row($result)) {
      echo "<tr>
      <td><p align='center';>" . $row[0] . "</p></td>
      <td><p align='center';>" . $row[1] . "</p></td>
      <td><p align='center';>" . $row[2] . "</p></td>
      <td><p align='center';>" . $row[3] . "</p></td>
      <td><p align='center';>" . $row[4] . "</p></td>
      </tr>"; 
    }
    echo "</table>";
  }

  function handleInsertRequest()
  {
    global $db_conn;
    $entryStatus = TRUE;
    $tuple = array(
      ":city" => $_POST['city'],
      ":circuit_name" => $_POST['circuit_name'],
      ":country" => $_POST['country'],
      ":longitude" => $_POST['longitude'],
      ":latitude" => $_POST['latitude']
    );

    $circuitTuples = array(
      $tuple
    );

    if (in_array('', $tuple, true)) {
      $entryStatus = FALSE;
      $missingArray = array();
      foreach ($tuple as $bind => $value) {
        if (empty($value)) {
            array_push($missingArray, $bind);
        }
    }
    $result = preg_replace('/[^a-zA-Z0-9_ -]/s','',$missingArray);
      echo "<p style='color:#db2c20;'> Please fill in '" .implode("', '",$result)."'<br> No changes made.</p>" ;;
  }

    if ($entryStatus) {
      executeBoundSQL("INSERT INTO Circuit_2 VALUES (:city, :circuit_name, :country, :longitude, :latitude)", $circuitTuples);
      OCICommit($db_conn);
    }
  }

  function handleDeleteRequest()
  {
    global $db_conn;
    $delete_name = $_POST['circuit_name'];
    $ans = executePlainSQL("SELECT circuit_name 
                          FROM Circuit_2
                          WHERE circuit_name = '" . $delete_name . "'");
    $row = oci_fetch_row($ans);
    if ($row == false) {
      echo "<p style='color:#db2c20;'> Delete failed, circuit does not exist.</p>" ;
    }
    $row = executePlainSQL("DELETE FROM Circuit_2
                          WHERE circuit_name = '" . $delete_name . "'");
    OCICommit($db_conn);
  }


  function handlePOSTRequest()
  {
    if (connectToDB()) {

      if (array_key_exists('deleteQueryRequest', $_POST)) {
        handleDeleteRequest();
      } else if (array_key_exists('insertQueryRequest', $_POST)) {
        handleInsertRequest();
      }
      $result = executePlainSQL('SELECT * FROM Circuit_2
                                ORDER BY circuit_name');
      printResult($result);
      disconnectFromDB();
    }
  }

  if (isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {

    handlePOSTRequest();
  }

  ?>

</body>

</html>