<?php 
 
    $GLOBALS['db_name'] = "mysql";

    include ("Console/Getopt.php");

    $cg = new Console_Getopt();

    // define available options
    $shortOptions = "u:p:h:";
    $longOptions = array("file==", "create_table==", "dry_run==", "help==");
    

    // read arguments from command line
    $args = $cg->readPHPArgv();
    $ret = $cg->getopt($args, $shortOptions, $longOptions);

    // check to see if the options are valid
    if (PEAR::isError($ret)) {
        die ("Invalid options, use --help to see valid options" . $ret->getMessage() . "\n");
    }


    $opts = $ret[0];

    if (sizeof($opts) > 0) {
   
        foreach ($opts as $o) {

            switch($o[0]) {

                case '--file':
                    
                    $csvFile = $o[1];
                    break;

                case '--dry_run':

                    break;

                case '--create_table':

                    break;
                
                case 'u':

                    $GLOBALS['user'] = $o[1];
                    break;

                case 'p':

                    $GLOBALS['pass'] = $o[1];
                    break;
                
                case 'h':

                    $GLOBALS['db_host'] = $o[1];
                    break;
                
                case '--help':

                    fwrite(STDOUT, "Help Screen:\n");
                    fwrite(STDOUT, "The following options are available: \n");
                    fwrite(STDOUT, "--file [csv file name] - this is the name of the csv file to be parsed \n");
                    fwrite(STDOUT, "--create_table - this will cause the MySQL users table to be built (and no further action will be taken)");
                    fwrite(STDOUT, "--dry_run - this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered. \n");
                    fwrite(STDOUT, "-u - MySQL username \n");
                    fwrite(STDOUT, "-p - MySQL password \n");
                    fwrite(STDOUT, "-h - MySQL host \n");
                    fwrite(STDOUT, "--help - show this help message \n");

            }
            
        }
    }

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
