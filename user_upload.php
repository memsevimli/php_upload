<?php 

    $csvFile = 'users.csv';

    $userFile = fopen($csvFile, 'r') or die("Can't open file.");
    
    while (!feof($userFile)) {
        $userArray[] = fgetcsv($userFile, 1024);
    }

    fclose($userFile) or die("Can't close file.");

    foreach ($userArray as $user) {

        $firstName = ucwords(strtolower(trim($user[0])));
        $lastName = ucwords(strtolower(trim($user[1])));
        $email = strtolower(trim($user[2]));
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "Invalid Email Format";
        }

        fwrite(STDOUT, $firstName . " " . $lastName . " " . $email . "\n");
        
    }

?>
