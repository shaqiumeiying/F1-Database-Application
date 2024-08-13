<!DOCTYPE html>
<html>

<head>
  <title>Information About the Race Record</title>
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

  <h2 class="operations">Select the race information you would like to view:</h2>
  <div class="display-inline">    
  <a><form method="POST" action="Projection.php">
    <input type="hidden" id="ProjectQueryRequest" name="ProjectQueryRequest">
    <input type="checkbox" name= "raceInfo[]"  value="race_date" > Race Date <br/>
    <input type="checkbox" name= "raceInfo[]"  value="race_name" > Race Name <br/>
    <input type="checkbox" name= "raceInfo[]" value="round_number" > Round Numbers <br/>
    <input type="checkbox" name= "raceInfo[]" value="lap_numbers"> Lap Numbers <br/>
    <input type="checkbox" name= "raceInfo[]" value="circuit_name"> Circuit Name <br/>
    <br>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="Project" name="ProjectSubmit">
</form></a>
</div>


  <?php

   require __DIR__ . '/#OracleFunctions.php';

  function printResult($result)
  { //prints results: Projection Table
    global $raceInfo;
    echo "<h3><font color='#2d4cb3'; align='center';>Projected Race Record :</h3>";
    echo "<table class='center'>";
  
    foreach($raceInfo as $title){
      echo "<th><font color='#2d4cb3'; align='center'; >" . $title . "</th>";
    }

    while ($array = oci_fetch_row($result)) {
      echo "<tr>";
      foreach($array as $row){
      echo "<td><p align='center';>"
      . $row . "</p></td>";
    }
      echo "</tr>"; 
    }
    echo "</table>";
  }

  function handleProjectRequest()
  {
    global $db_conn;

    if (isset($_POST['raceInfo'])){
      global $raceInfo;
      $raceInfo = $_POST['raceInfo'];
      $selectStatement = "SELECT ";
      for($i=0; $i<count($raceInfo); $i++){
       //echo "<th>&nbsp;". $raceInfo[$i] . "&nbsp;</th>";
       $selectStatement = $selectStatement. $raceInfo[$i] .", ";
      }
  
      $selectStatement = substr($selectStatement, 0, -2);
      $selectStatement = $selectStatement ." FROM RacesTakePlace";
    
      $result = executePlainSQL($selectStatement);
      printResult($result);   
  }

      OCICommit($db_conn);   
  }

  function handlePOSTRequest()
  {
    if (connectToDB()) {

      if (array_key_exists('ProjectQueryRequest', $_POST)) {
        handleProjectRequest();
      } 

      disconnectFromDB();
    }
  }

  if (isset($_POST['ProjectSubmit'])) {
    handlePOSTRequest();
  }

  ?>

</body>

</html>