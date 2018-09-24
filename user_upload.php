<?php 

    $db_host = "localhost"; 
    $db_name = "mysql";
    $user = "username";
    $pass = "password";

    // Database connection
    $connection = mysqli_connect($db_host, $user, $pass, $db_name);

    $csvFile = 'users.csv';

    $userFile = fopen($csvFile, 'r') or die("Can't open csv file.");
    
    while (!feof($userFile)) {
        $userArray[] = fgetcsv($userFile, 1024);
    }

    fclose($userFile) or die("Can't close file.");

    foreach ($userArray as $user) {

        $firstName = ucwords(strtolower(trim($user[0])));
        $lastName = ucwords(strtolower(trim($user[1])));
        $email = strtolower(trim($user[2]));

        $firstName = mysqli_real_escape_string($connection, $firstName);
        $lastName = mysqli_real_escape_string($connection, $lastName);
        $email = mysqli_real_escape_string($connection, $email);

        $query = "INSERT INTO users (name, surname, email) VALUES ('$firstName', '$lastName', '$email')";
        
        if (mysqli_query($connection, $query)) {
            echo "New record added";
        }
        else {
            echo mysqli_error($connection);
        }
        
    }

?>
