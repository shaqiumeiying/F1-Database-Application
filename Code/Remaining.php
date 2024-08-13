<!DOCTYPE html>
<html>

<head>
    <title>Aggregation+Nest+Division</title>
    
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

    <h2 class="operations">Here are some extra info if you want to know:</h2>
    <div class="display-inline">    
    <a><form method="POST" action="Remaining.php"> 
        <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
        The safety car driver who have driven every safety car: 
        <input type="submit" value="Find" name="divisionSubmit"></p>
    </form></a>

    <a><form method="POST" action="Remaining.php"> 
        <input type="hidden" id="aggregationQueryRequest" name="aggregationQueryRequest">
        Find out the MAX amount of sponsorship cost for each country: 
        <input type="submit" value="Find" name="aggSubmit"></p>
    </form></a>

    <a><form method="POST" action="Remaining.php"> 
        <input type="hidden" id="nestedQueryRequest" name="nestedQueryRequest">
        Which nationality of the sponsorship companies has MIN <br> AVG cost over all nationalities? 
        <input type="submit" value="Find" name="nSubmit"></p>
    </form></a>

    <a><form method="POST" action="Remaining.php"> 
        <input type="hidden" id="awhavingQueryRequest" name="awhavingQueryRequest">
        Things happen during races, what happened to the drivers <br> who didn't complete the race? 
        <input type="submit" value="Find" name="awhSubmit"></p><br>

    </form></a>
    </div >

    <?php
     require __DIR__ . '/#OracleFunctions.php';

    function printResultDV($result)
    { while ($row = oci_fetch_row($result)) {
        echo "<h3><font color='#228b22'>The safety driver is:</h3>";
        echo "<p style = 'color:#228b22'; align='center';>" . $row[0] . "</p>";
        }
    }

    function printResultAG($result)
    { while ($row = oci_fetch_row($result)) {
        echo "<h3><font color='#228b22'> Max cost among all " . $row[0] . " sponsorship:</h3>";
        echo "<p style = 'color:#228b22'; align='center';> $" . $row[1] . " million</p>";
        }
    }

    function printResultAWH($result)
    { while ($row = oci_fetch_row($result)) {
        echo "<p style = 'color:#228b22'; align='center';>" . $row[0] . " driver(s) failed the race due to ". $row[1] . "</p>";
        }
    }

    function printResultN($result)
    { while ($row = oci_fetch_row($result)) {
        echo "<p style = 'color:#228b22'; align='center';>Sponsorship from " . $row[0] . "have a minimum avg cost <br> of $". $row[1] . " million among other nationalities.</p>";
        }
    }


    function handleDivisionRequest()
    {
        global $db_conn;
        
        $result = executePlainSQL(
            "SELECT safetycardriver_name
             FROM SafetyCarDriver A
             WHERE NOT EXISTS ((SELECT dsc.safetycar_name
                                FROM DriveSafetyCars dsc)
                                MINUS (SELECT safetycar_name 
                                               FROM SafetyCarDriver, DriveSafetyCars
                                               WHERE safetycar_driver = A.safetycardriver_name))");
        printResultDV($result);
        OCICommit($db_conn);
    }

    function handleAggregationRequest()
    {
        global $db_conn;
        $result = executePlainSQL("SELECT s.nationality, MAX(s.amount)
                                   FROM Sponsorship s
                                   GROUP BY s.nationality");

        printResultAG($result);
        OCICommit($db_conn);
    }

    function handleNestedRequest()
    {
        global $db_conn;
        $result = executePlainSQL("SELECT nationality, avg(amount)
                                   FROM Sponsorship s
                                   GROUP BY nationality
                                   HAVING avg(amount) <= all (SELECT AVG(s.amount)
                                                              FROM Sponsorship s
                                                              GROUP BY s.nationality)
                                  ");

        printResultN($result);
        OCICommit($db_conn);
    }

    function handleAWHRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT COUNT(*), h.race_status
                                   FROM Participate p, HaveResults2 h
                                   WHERE p.race_date = h.race_date
                                   GROUP BY h.race_status
                                   HAVING h.race_status != 'completed'");
        
        printResultAWH($result);
        OCICommit($db_conn);
    }

    
    function handlePOSTRequest()
    {
        if (connectToDB()) {

            if (array_key_exists('divisionQueryRequest', $_POST)) {
                handleDivisionRequest();
            } else if (array_key_exists('aggregationQueryRequest', $_POST)) {
                handleAggregationRequest();
            } else if (array_key_exists('nestedQueryRequest', $_POST)) {
                handleNestedRequest();
            } else if (array_key_exists('awhavingQueryRequest', $_POST)) {
                handleAWHRequest();}

            disconnectFromDB();
        }
    }

    if (isset($_POST['divisionSubmit']) || isset($_POST['aggSubmit']) || isset($_POST['nSubmit']) || isset($_POST['awhSubmit'])) {
        handlePOSTRequest();
    }
    ?>


</body>

</html>