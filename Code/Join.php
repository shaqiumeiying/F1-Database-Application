<!DOCTYPE html>
<html>

<head>
    <title>Search Race Location and Date</title>
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

    <h2 class="operations">Enter race name to get it's location and date:</h2>
    <form method="POST" action="Join.php">
        <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
        Race Name: <input type="text" , name="RaceName"> <input type="submit" value="Find" name="Submit"></br>
    </form>

    <?php

    require __DIR__ . '/#OracleFunctions.php';

    function printResult($result)
    { //prints results from a select statement
        echo "<br><h3><font color='#2d4cb3'>Races location and date:</h3>";
        echo "<table class='center'>";
        echo "<tr>
        <th><font color='#2d4cb3'>Location</th>
        <th><font color='#2d4cb3'>Date</th>
        </tr>";
        $hasResult = false;

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            $hasResult = true;

            echo "<tr>
            <td><p align='center';>" . $row[0] . "</p></td>
            <td><p align='center';>" . $row[1] . "</p></td>
            </tr>";
        }

        if ($hasResult == false) {
            echo "<tr><td><p align='center'; style='color:#db2c20';>No Race</p></td>
            <td><p align='center'; style='color:#db2c20';>Found</p></td>

            </tr>";
        }
        
        echo "</table>";

    }

    function handleJoinRequest()
    {
        global $db_conn;
        //$entryStatus = TRUE;
        //Getting the values from user and insert data into the table
        $racename = $_POST['RaceName'];

        if ($racename==NULL) {
            echo "<font color='#db2c20'><br />Please enter a race name.</font>";

        } else {

        $try = ("SELECT c.city, r.race_date
                 FROM RacesTakePlace r, Circuit_2 c
                 WHERE r.circuit_name = c.circuit_name 
                 AND r.race_name = '" . $racename . "'
                ");

                     
        $check = executePlainSQL($try);
        printResult($check);
    }
     
        OCICommit($db_conn);
    }

    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('joinQueryRequest', $_POST)) {
                handleJoinRequest();
            }
            disconnectFromDB();
        }
    }

    if (isset($_POST['Submit'])) {
        handlePOSTRequest();
    }

    ?>
</body>

</html>