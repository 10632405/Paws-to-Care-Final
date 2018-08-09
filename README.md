# Paws To Care

First you will need to download the contents of this website to your web server document root.

##First
Once you have the site uploaded to the web server root, visit the site. You should see the Paws To Care website. If you do not see that then don't move on until you get that fixed.

##Second
Find the file named `createDatabase.txt`. You will need to run the table creation scripts on your MySQL server, those scripts where created using `MySQL version 5.7.22`

##Third
Once the tables are created you can start the migration of your existing database to this new one.

## Forth
You will need to update the file called `dbconnect.php` with your database information.

```
<?php
$servername = "localhost";
$username = "";
$password = "";
$dbname = "pawstocare";	
?>
```
##Last
Once you have completed the above steps everything should be working and you will be ready with your new site!
