<?php
/** Place this as endpoint in php webroot to generate JSON blobs.

    if you have PHP 5.4 you can do 
       php -S localhost:8080 endpoint.php
    otherwise stick it in a php enabled webroot somewhere
**/

date_default_timezone_set('America/Los_Angeles');

$id = date_timestamp_get(new DateTime());
$name = uniqid()."_name";
$value = uniqid()."_value";
$date = new DateTime();
$ts = $date->format('Y-m-d H:i:s');

print <<<END
{"name":"$name","id":$id, "value":"$value","timestamp":"$ts"}
END;
?>
