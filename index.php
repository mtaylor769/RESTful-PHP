<?php
setlocale(LC_ALL, en_US);

date_default_timezone_set("America/Los_Angeles");

//phpinfo();

include_once("./db_flib.php");
//showDatabases();

$cc = new cURL(); 
$result = $cc->get('http://localhost:9200/coordinates/coordinate/_search?q=account_id:my_account_id'); 

print_r($result['hits']['hits'][0]['_source']);
?>
