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
    + Store the blob directly as fetched vs explode out into fields. "store the object" directions seem to indicate the raw string

*/

/** See discussion in mysql-config.php for security issues with mysql endpoints in code **/
define( 'DB_NAME',     'testdb' );
define( 'DB_USER',     'user' );
define( 'DB_PASSWORD', 'passwd' );
define( 'DB_HOST',     'localhost' );
define( 'SQL_ENDPOINT','mysql:host='.DB_HOST.';dbname='.DB_NAME);

// print "DBHOST: ".DB_HOST."\n";
// print "SQL_ENDPOINT: ".SQL_ENDPOINT."\n";
// print "CWD:".getcwd()."\n";
// print "ARGV:";
// var_dump($argv);

// TODO: usage()
// TODO: bad/missing arguments
$endpoint = $argv[1];

// print "Endpoint: $endpoint\n";

// TODO: error handling
// TODO: what if the fetch takes longer than 1 second, we're gonna end up hammering the endpoint
// $result = file_get_contents($endpoint);
$result = '{"name":"ugly","id":0,"value":"12345","timestamp":"20140327T123456"}';

// print "Result:\n";
// var_dump($result);

// TODO: May not even be needed if we're storing the fetched blob directly.
$json = json_decode($result);
// TODO: invalidate blob if we can't parse it into JSON.

// print "JSON:\n";
// var_dump($json);
// print "JSON.status: $json->status \n";

// Simulation data
// $json->id = "1";
// $json->name = "asdasd";
// $json->value = $json->data;
// $json->timestamp = "20140328123456";

// Rudimentary error handling
try {
	$db = new PDO(SQL_ENDPOINT, DB_USER, DB_PASSWORD);
	$q = $db->prepare("INSERT INTO JSONBLOBS (BLOB) VALUES (:json)");
	$q -> bindParam(":id", $json);
	// $q = $db->prepare("INSERT INTO JSONBLOBS (ID,NAME,VALUE,TIMESTAMP) VALUES (:id, :name, :value, :timestamp)");
	// $q -> bindParam(":id", $json->id);
	// $q -> bindParam(":name", $json->name);
	// $q -> bindParam(":value", $json->value);
	// $q -> bindParam(":timestamp, $json->timestamp);
	print "QUERY: $query\n";
	$q ->execute();
    $db = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "\n";
    die();
}

?>