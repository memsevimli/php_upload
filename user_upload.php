<?php 

    $csvFile = 'users.csv';

    $userFile = fopen($csvFile, 'r');
    
    while (!feof($userFile)) {
        $userArray[] = fgetcsv($userFile, 1024);
    }

    fclose($userFile);

    print_r($userArray);

?>
