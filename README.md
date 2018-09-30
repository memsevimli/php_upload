# user_upload

A command line PHP script to read csv files, and insert into a MySQL database

Requirements:
- This script utilises Console_Getopt, a built in library from the PEAR package
		PEAR must be installed

Instructions:
- Please change variable on line 9 to your database name

- Run user_upload.php --help to view the available command line options directives

- Enter desired short options first (eg. -u, -p. -h) before long options (eg. --file, --dry_run)

	Example: php user_upload.php -h hostname -u username -p password --file=users.csv --dry_run
