<?php 

    include ("Console/Getopt.php");

    // initialise dry run flag to 0
    $dry = 0;

    // set database name
    $dbname = "mysql";
    
    class database {

        public function __construct(
            $dbname, 
            $username, 
            $password, 
            $hostname,
            $dry
        ) {
            $this->name = $dbname;
            $this->user = $username;
            $this->pass = $password;
            $this->host = $hostname;
            $this->dry = $dry;
            $this->connect();
        }
    
        protected function connect() {
            $this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->name);
        }
    
        // method to create a new users table
        public function createTable() {
            
            $query = "CREATE TABLE users (id INT(6) AUTO_INCREMENT PRIMARY KEY, name VARCHAR(30), surname VARCHAR(30), email VARCHAR(50) UNIQUE)";
            
            if (!mysqli_query($this->connection, $query)) {
                fwrite(STDOUT, "Table could not be created, " . mysqli_error($this->connection) . "\n");
            }
            else {
                fwrite(STDOUT, "Table was created.\n");
            }
        }
    
        // method to upload users into the database
        public function createUsers(array $users) {
            
            foreach ($users as $u) {
        
                $firstName = ucwords(strtolower(trim($u[0])));
                $lastName = ucwords(strtolower(trim($u[1])));
                $email = strtolower(trim($u[2]));
            
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    
                    fwrite(STDOUT, $email . " is not in an acceptable format. Record not added to database.\n");
                
                } else if ($this->dry != 1) {
                    
                    $firstName = mysqli_real_escape_string($this->connection, $firstName);
                    $lastName = mysqli_real_escape_string($this->connection, $lastName);
                    $email = mysqli_real_escape_string($this->connection, $email);
                    
                    $query = "INSERT INTO users (name, surname, email) VALUES ('$firstName', '$lastName', '$email')";
            
                    if (!mysqli_query($this->connection, $query)) {
                        fwrite(STDOUT, "Insert Failed, " . mysqli_error($this->connection) . "\n");
                    }
                }  
            }
        }
    }


    $cg = new Console_Getopt();

    // define the available command line options
    $shortOptions = "u:p:h:";
    $longOptions = array("file==", "create_table==", "dry_run==", "help==");
    
    // read the options from the command line
    $args = $cg->readPHPArgv();
    $ret = $cg->getopt($args, $shortOptions, $longOptions);

    // check to see if the options are valid
    if (PEAR::isError($ret)) {
        die ("Invalid options, use --help to see valid options" . $ret->getMessage() . "\n");
    }

    $opts = $ret[0];

    // handle command line options
    if (sizeof($opts) > 0) {
   
        foreach ($opts as $o) {

            switch($o[0]) {

                case '--file':                   
                    $csvFile = $o[1];
                    break;

                case '--dry_run':
                    $dry = 1;
                    // $dbname = "mysql";
                    $database = new database($dbname, $username, $password, $hostname, $dry);
                    $users = readCsv($csvFile);
                    $database->createUsers($users, $dry);
                    break;

                case '--create_table':
                    // $dbname = "mysql";
                    $database = new database($dbname, $username, $password, $hostname, $dry);
                    $database->createTable();
                    goto end;
                    break;
                
                case 'u':
                    $username = $o[1];
                    break;

                case 'p':
                    $password = $o[1];
                    break;
                
                case 'h':
                    $hostname = $o[1];
                    break;
                
                case '--help':
                    fwrite(STDOUT, "Help Screen:\n\n");
                    fwrite(STDOUT, "The following options are available: \n");
                    fwrite(STDOUT, "--file [csv file name] - this is the name of the csv file to be parsed \n");
                    fwrite(STDOUT, "--create_table - this will cause the MySQL users table to be built (and no further action will be taken)");
                    fwrite(STDOUT, "--dry_run - this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered. \n");
                    fwrite(STDOUT, "-u - MySQL username \n");
                    fwrite(STDOUT, "-p - MySQL password \n");
                    fwrite(STDOUT, "-h - MySQL host \n");
                    fwrite(STDOUT, "--help - show this help message \n");
                    goto end;
                    break;

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

    // main program to insert users into database, runs if dry run is not selected as an option
    if ($dry != 1) {
        
        $dbname = "mysql";
        $database = new database($dbname, $username, $password, $hostname, $dry);
        $users = readCsv($csvFile);
        $database->createUsers($users, $dry);

    }
    
    end:
?>
