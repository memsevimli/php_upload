<?php 

    $GLOBALS['db_host'] = "localhost"; 
    $GLOBALS['db_name'] = "mysql";
    $GLOBALS['user'] = "username";
    $GLOBALS['pass'] = "password";

    $csvFile = 'users.csv';

    // function to read csv file and store values in an array
    function readCsv($csv) {
        $userFile = fopen($csv, 'r') or die("Can't open csv file.");
        
        while (!feof($userFile)) {
            $userArray[] = fgetcsv($userFile, 1024);
        }

        fclose($userFile) or die("Can't close file.");
        
        return $userArray;
    }

    // Database connection
    $connection = mysqli_connect($db_host, $user, $pass, $db_name);

    $users = readCsv($csvFile);

    foreach ($users as $u) {

        $firstName = ucwords(strtolower(trim($u[0])));
        $lastName = ucwords(strtolower(trim($u[1])));
        $email = strtolower(trim($u[2]));
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            fwrite(STDOUT, $email . " is not in an acceptable format. Record not added to database.\n");
        }
        else {
         
            $firstName = mysqli_real_escape_string($connection, $firstName);
            $lastName = mysqli_real_escape_string($connection, $lastName);
            $email = mysqli_real_escape_string($connection, $email);

            insertdb($firstName, $lastName, $email);
        }
        
    }

    // function to insert records into database
    function insertdb($firstName, $lastName, $email) {

        $db_host = $GLOBALS['db_host'];
        $db_name = $GLOBALS['db_name'];
        $user = $GLOBALS['user'];
        $pass = $GLOBALS['pass'];

        $connection = mysqli_connect($db_host, $user, $pass, $db_name);

        $query = "INSERT INTO users (name, surname, email) VALUES ('$firstName', '$lastName', '$email')";
    
        if (!mysqli_query($connection, $query)) {
            fwrite(STDOUT, "Insert Failed, " . mysqli_error($connection) . "\n");
        }


    }

?>
