<!--TransactionInfo.php-->
<html>

<head>
<title>Transaction Information</title>
<link rel="stylesheet" href="phpDesign@.css">
</head>
<body>
<?php

$servername = "localhost";
$username = "smadzar";
$password = "12345";
$database = "smadzarDB";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


if ($_POST['action'] == 'Create a New Trasaction') {
    $name = $_POST['transaction_name'];
    $type = $_POST['transaction_type'];
    $amount = $_POST['transaction_amount'];
    $date = $_POST['transaction_date'];
    $benef = '';
    
    foreach($_POST['transaction_beneficaries'] as $value) {
         $benef .= $value . " ";
    }
    if($benef == null) {
        $benef = "/";
    }
    $sql = "INSERT INTO transactionBook (Id, Name, Type, Amount, Date, Beneficaries) 
            VALUES (null, '$name', '$type', '$amount', '$date', '$benef')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<br><h3>New transaction created sucessfully!</h3>";
        echo "<br><br>TRANSACTION DETAILS";

        $sql = "SELECT * FROM transactionBook WHERE id=(SELECT max(id) FROM transactionBook)";
        $result = mysqli_query($conn, $sql);
        echo "<br><br><table border = 1><th>Transaction ID</th><th>Transaction Name</th><th>Transaction Type</th>
        <th>Transaction Amount</th><th>Transaction date</th><th>Beneficaries</th>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>{$row["ID"]}</td><td>{$row["Name"]}</td><td>{$row["Type"]}</td><td>{$row["Amount"]}</td>
            <td>{$row["Date"]}</td><td>{$row["Beneficaries"]}</td></tr>";
        }
        echo "</table>";

        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
        '>Transaction Book Form</a><br>";
        
    } 
    else {
        echo "<br>Error! Transaction not created. <br>Select all the options!";
        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
        '>Transaction Book Form</a><br>";
    }
    
} else if ($_POST['action'] == 'List all Transactions') {

        $sql = "SELECT * FROM transactionBook";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);

        if($rowCount > 0) {
        echo "<br><h3>ALL TRANSACTIONS LISTED</h3>";
        echo "<br><table border = 1><th>Transaction ID</th><th>Transaction Name</th><th>Transaction Type</th>
        <th>Transaction Amount</th><th>Transaction date</th><th>Beneficaries</th>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>{$row["ID"]}</td><td>{$row["Name"]}</td><td>{$row["Type"]}</td><td>{$row["Amount"]}</td>
            <td>{$row["Date"]}</td><td>{$row["Beneficaries"]}</td></tr>";
        }
        echo "</table>";
        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
        '>Transaction Book Form</a><br>";
        }
        else {
            echo "<br>There are no transactions stored!";
            echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
            '>Transaction Book Form</a><br>";
        }
    
} else if ($_POST['action'] == 'Search by Type') {
   
    if(isset($_POST['transaction_type'])) {
        
        $sql = "SELECT * FROM transactionBook WHERE type = \"" . $_POST['transaction_type'] . "\"";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);

        if($rowCount > 0) {
            echo "<br><h3>TRANSACTIONS BY TYPE(" . $_POST['transaction_type'] . ") LISTED</h3>";
            echo "<br><table border = 1><th>Transaction ID</th><th>Transaction Name</th><th>Transaction Type</th>
            <th>Transaction Amount</th><th>Transaction date</th><th>Beneficaries</th>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>{$row["ID"]}</td><td>{$row["Name"]}</td><td>{$row["Type"]}</td><td>{$row["Amount"]}</td>
                <td>{$row["Date"]}</td><td>{$row["Beneficaries"]}</td></tr>";
            }
            echo "</table>";
            echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
            '>Transaction Book Form</a><br>";
            }
            else {
                echo "<br>There are no transactions by " . $_POST['transaction_type'] . " stored!";
                echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
                '>Transaction Book Form</a><br>";
            }
  
    }
    else {
        echo "<br>Error! Option Type not selected. Try again!";
        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
            '>Transaction Book Form</a><br>";
    }

} else if($_POST['action'] == 'Overall balance')  {

        $sql = "SELECT * FROM transactionBook";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if($rowCount > 0) {
        
            $sql2 = "SELECT (SELECT IFNULL(SUM(amount), 0) FROM transactionBook WHERE type=\"Income\") - 
                   ((SELECT IFNULL(SUM(amount), 0) FROM transactionBook WHERE type=\"Purchase\") + 
                   (SELECT IFNULL(SUM(amount), 0) FROM transactionBook WHERE type=\"Tax Payment\")) as OverallBalance";
           
        if(mysqli_query($conn, $sql2)) {
            $result2 = $conn->query($sql2);
            echo "<br><h3>OVERALL BALANCE</h3>";
            echo "<br><table border = 1><th>Overall Amount($)</th>";
            while($row = $result2->fetch_assoc()) {
                echo "<tr><td>{$row["OverallBalance"]}</td><tr>";
            }
            echo "</table>";
        }
        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
            '>Transaction Book Form</a><br>";
    }
    else {
        echo "<br>There are no transactions stored!";
        echo "<br><br>Go to the main page: <a href='https://babbage.se.edu/~smadzar/index.html
            '>Transaction Book Form</a><br>";
    }
} 

?>

</body>
</html>