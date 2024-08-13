<!DOCTYPE html>
<html>

<head>
    <title>Selectable Information</title>
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

    <h2 class="operations">Select Team Members by:</h2>
    <form method="POST" action="Selection.php">
        <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
        Nationality: <input type="text" , name="Nationality"> 
        </br>
        Born after year: <input type="text" , name="Year"><br>
        <br> 
        <input type="submit" value="Select" name="NBSubmit"></br>
    </form>

    <h2 class="operations">Select Constructors by:</h2>
    <form method="POST" action="Selection.php">
        <input type="hidden" id="selectQueryRequest1" name="selectQueryRequest1">
        Nationality: <input type="text" , name="C-Nationality">
        </br>
        City: <input type="text" , name="City"><br></a>
        <br>
        <input type="submit" value="Select" name="CNSubmit"></br>
    </form>
    <br>
    </div>

    <?php
    
    require __DIR__ . '/#OracleFunctions.php';

    function printResult($result)
    {   
        //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Selected data based on your input:</h3>";
        echo "<table class='center'>";
        echo "<tr>
         <th><font color='#2d4cb3'>First Name</th>
         <th><font color='#2d4cb3'>Last Name</th>
         <th><font color='#2d4cb3'>Birthday</th>
         <th><font color='#2d4cb3'>Nationality</th>
         <th><font color='#2d4cb3'>Constructor</th>
        </tr>";
        $hasResult = false;

        while ($row = oci_fetch_row($result)) {
            $hasResult = true;
            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            <td><p align='center';>" . $row[2] . "</p></td>
            <td><p align='center';>" . $row[3] . "</p></td>
            <td><p align='center';>" . $row[4] . "</p></td>
            </tr>";
        }

        if ($hasResult == false) {
            echo "<tr>
            <td><p align='center'; style='color:#db2c20';>         </p></td>
            <td><p align='center'; style='color:#db2c20';>         </p></td>
            <td><p               ; style='color:#db2c20';>No Result</p></td>
            <td><p align='center'; style='color:#db2c20';>         </p></td>
            <td><p align='center'; style='color:#db2c20';>         </p></td>
            </tr>";
        }
        echo "</table>";
    }

    function printResult1($result)
    { //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Selected data based on your input:</h3>";
        echo "<table class='center'>";
        echo "<tr>
         <th><font color='#2d4cb3'>Name</th>
         <th><font color='#2d4cb3'>Nationality</th>
         <th><font color='#2d4cb3'>City</th>
        </tr>";
        $hasResult = false;
        while ($row = oci_fetch_row($result)) {
            $hasResult = true;
            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            <td><p align='center';>" . $row[2] . "</p></td>
            </tr>";
        }
        if ($hasResult == false) {
            echo "<tr>
            <td><p align='center'; style='color:#db2c20';>         </p></td>
            <td><p align='center'; style='color:#db2c20';>No Result</p></td>
            <td><p align='center'; style='color:#db2c20';>         </p></td></tr>";
        }
        echo "</table>";
    }

    function handleSelectRequest()
    {
        global $db_conn;
        //$entryStatus = TRUE;
        //Getting the values from user and insert data into the table
        $nationality = $_POST['Nationality'];
        $Year = $_POST['Year'];
   
            if(($nationality != Null) && ($Year != Null)){
            $check = executePlainSQL("SELECT * 
                                      FROM  EmployTeamMembers
                                      WHERE nationality = '" . $nationality . "'
                                      AND (TO_DATE($Year,'YYYY') < TO_DATE(date_of_birth,'YYYY-MM-DD'))
                                      ");

                printResult($check);
                
            } else if (($nationality == Null) && ($Year != Null)) {
                $check = executePlainSQL("SELECT * 
                                          FROM EmployTeamMembers
                                          WHERE TO_DATE($Year,'YYYY') < TO_DATE(date_of_birth,'YYYY-MM-DD')
                                          ");
                printResult($check);

            } else if(($nationality != Null) && ($Year == Null)){
                $check = executePlainSQL("SELECT * 
                                          FROM EmployTeamMembers
                                          WHERE nationality = '" . $nationality . "'");
                printResult($check);
                
            } else {
                echo "<font color='#db2c20'><br /> Please enter a field.</font>";
            }
 

        OCICommit($db_conn);
    }

    function handleSelectRequest1()
    {
        global $db_conn;

        $cnationality = $_POST['C-Nationality'];
        $City = $_POST['City'];

          if(($cnationality != Null) && ($City != Null)){
            $check1 = executePlainSQL("SELECT * 
                                FROM Constructors
                                WHERE nationality = '" . $cnationality . "' 
                                AND city = '" . $City . "'");
            printResult1($check1);

        } else if(($cnationality == Null) && ($City != Null)){
            $check1 = executePlainSQL("SELECT * 
                                FROM Constructors
                                WHERE city = '" . $City . "'");
            printResult1($check1);

        } else if(($cnationality != Null) && ($City == Null)){
            $check1 = executePlainSQL("SELECT * 
                                FROM Constructors
                                WHERE nationality = '" . $cnationality . "' ");
            printResult1($check1);

        } else {
            echo "<font color='#db2c20'><br /> Please enter a field.</font>";
        }

        
        OCICommit($db_conn);
    }

    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('selectQueryRequest', $_POST)) {
                handleSelectRequest();
            } else if (array_key_exists('selectQueryRequest1', $_POST)) {
              handleSelectRequest1();
          }
            disconnectFromDB();
        }
    }

    if (isset($_POST['NBSubmit']) || isset($_POST['CNSubmit'])) {
        handlePOSTRequest();
    }

    ?>

</body>

</html>