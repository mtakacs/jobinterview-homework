#!/usr/bin/env php
<?php
/**
 *
    # Program task: 

	- fetches an object in JSON format from an endpoint given as an argument 

	- stores the object in a mysql db (mysql:host=localhost;dbname=testdb;, user: user, password: passwd) 

	# Working assumptions : 

	- in production, the script will be run of crond on a minute frequency 

	- object JSON format: {"name":"","id":0,"value":"","timestamp":""}, where id and name are unique.

  Questions:
    + Store the blob directly as fetched vs explode out into fields. 
      "store the object" directions seem to indicate the raw string.
    + If we're storing the exploded JSON blob as columns in a table, I'd need to know a bit more about
      how the schema of the DB is prepared (eg: ID as an int or a string/UUID, for instance. looks like an INT but...)

*/

/** See discussion in mysql-config.php for security issues with mysql endpoints in code **/
define( 'DB_NAME',     'testdb' );
define( 'DB_USER',     'user' );
define( 'DB_PASSWORD', 'passwd' );
define( 'DB_HOST',     'localhost' );
define( 'SQL_ENDPOINT','mysql:host='.DB_HOST.';dbname='.DB_NAME);

// TODO: f*ck me, my version of MySQL places mysql.SOCK at /tmp/mysql.sql, this version of
//       PHP PDO looks for it in /var/mysql/mysql.sock.  Hacked it with a SYMLINK, as I dont have 
//       easy access to the PHP.ini or altering the compile time mysql/PDO.

// print "DBHOST: ".DB_HOST."\n";
// print "SQL_ENDPOINT: ".SQL_ENDPOINT."\n";
// print "CWD:".getcwd()."\n";
// print "ARGV:";
// var_dump($argv);

// TODO: usage()
// TODO: bad/missing arguments
$endpoint = $argv[1];

// print "Endpoint: $endpoint\n";

// TODO: error handling. eg: 404 error, 500 error, timeouts, etc.
// TODO: what if the fetch takes longer than 1 second, we're gonna end up hammering the endpoint
//       setup some sort of timer and handle the "long fetch" case (backoff, bail, etc)

// Dev Hack:
//$endpoint = "http://localhost/~tak/endpoint.php";

$result = file_get_contents($endpoint);
// testing hack
//$result = '{"name":"ugly","id":0,"value":"12345","timestamp":"20140327T123456"}';

// print "Result:\n";
// var_dump($result);

// TODO: May not even be needed if we're storing the fetched blob directly.
$json = json_decode($result);

// TODO: invalidate blob if we can't parse it into JSON.
//    if (json) is valid jason
//    if (json) has expect structure  (keys and values)
//    go ahead and insert
$valid_json = true;

// print "JSON:\n";
// var_dump($json);
// print "JSON.status: $json->status \n";

// Rudimentary error handling
// TODO: Catch insert-index collisions  (eg: running over and over with same test data)

if ($valid_json) {
	try {
		$db = new PDO(SQL_ENDPOINT, DB_USER, DB_PASSWORD);
	
		// If we're just Storing the BLOB'd version of json (ala REDIS), use this:
		// $q = $db->prepare("INSERT INTO JSONBLOB (JSONBLOB) VALUES (:json)");
		// $q -> bindParam(":json", $result);

		// If we're exploding the JSON out into a struct and storing into columns, do this:
		$q = $db->prepare("INSERT INTO JSONSTRUCT (ID,NAME,VAL,FETCHTIME) VALUES (:id, :name, :val, :fetchtime)");
		if (!$q) {
		    echo "\nPDO::errorInfo():\n";
		    print_r($db->errorInfo());
		}
		// 'Safely' map possibly polluted endpoint data into a MYSQL statement.
		$q -> bindParam(":id", $json->id);
		$q -> bindParam(":name", $json->name);
		$q -> bindParam(":val", $json->value);
		$q -> bindParam(":fetchtime", $json->timestamp);
		$q -> execute();
		if (!$q) {
		    echo "\nPDO::errorInfo():\n";
		    print_r($db->errorInfo());
		}
	    $db = null;   // Close db
	} catch (PDOException $e) {
		// TODO: should print to STDERR
	    print "Error!: " . $e->getMessage() . "\n";
	    die();
	}	
} else {
	// TODO: should print to STDERR
    print "Error!: Invalid JSON: $result from endpoint: $endpoint\n";
    die();
}
// Don't spam anything to STDOUT if everything went ok.
?>