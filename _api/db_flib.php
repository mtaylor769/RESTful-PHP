<?php 
/*
 * whatsmycut.com 
 *
 * db_flib.php 
 *
 * @description		The main database function library for whatsmycut.com
 * @author			Mike Taylor
 * @version			1.0 beta
 * @created			Jan, 2004
 * @updated			Mar, 2014
 *
 * Version	Date			Author			Comments
 * --------------------------------------------------------------------------------------------
 * d1		Jan-2004		Mike Taylor		created most functions for insert and select
 * d2		Mar-2014		Mike Taylor		added cURL functions for UpOut interview test
 *                                          modified database to point at local mySql instance
*/

// Define the global variables for all DB access functions
$server_name = "127.0.0.1"; // use 'localhost' for security purposes.
$db_name = "test";

/*
 * showDatabases()
 *
 * this function is for testing connectivity. It connects, then lists the databases on the server.
 *
 * Version	Date			Author			Comments
 * --------------------------------------------------------------------------------------------
 * d1		08-May-2003		Mike Taylor		created
*/

function showDatabases() {
	global $server_name, $user_name, $user_passwd, $db_name;
	$link = mysql_connect($server_name, $user_name, $user_passwd,true)
		or die("Could not connect: " . mysql_errno() . ": " . mysql_error() . "\n");
	print "Connected successfully<br>";
	$db_list = mysql_list_dbs($link);

	while ($row = mysql_fetch_object($db_list)) 
	{
		echo $row->Database . "<br>\n";
	}
	mysql_select_db($db_name) or die("Could not select database: " . mysql_errno() . ": " . mysql_error()  ."<br>\n");
}

/*
*   mysql_fetch_array_nullsafe
*
*
*    get a result row as an enumerated and associated array
*    ! nullsafe !
*
*   parameter:    $result
*                    $result:    valid db result id
*
*    returns:    array | false (mysql:if there are any more rows)
*
*/
function mysql_fetch_array_nullsafe($result) {
	$ret=array();

	$num = mysql_num_fields($result);
	if ($num==0) return $ret;

	$fval = mysql_fetch_row ($result);
	if ($fval === false) return false;

	$i=0;
	while($i<$num)
	{
		$fname[$i] = mysql_field_name($result,$i);
		$ret[$i] = $fval[$i];            // enum
		$ret[''.$fname[$i].''] = $fval[$i];    // assoc
		$i++;
	}

	return $ret;
}


function stripCommas($str) {
	if (strrpos($str, '.')) 
	{
		$lf = substr($str, 0, strrpos($str, '.'));
	} else {
		$lf = $str;
	}
	$return = ereg_replace(',', '', $lf);
	return $return;
}


class Request {
    public $url_elements;
    public $verb;
    public $parameters;
 
    public function __construct() {
        $this->verb = $_SERVER['REQUEST_METHOD'];
        $this->url_elements = explode('/', $_SERVER['PATH_INFO']);
		$this->parseIncomingParams();
        // initialise json as default format
        $this->format = 'json';
        if(isset($this->parameters['format'])) {
            $this->format = $this->parameters['format'];
        }
        return true;
    }
 
    public function parseIncomingParams() {
        $parameters = array();
 
        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }
 
        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents("php://input");
        $content_type = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $content_type = $_SERVER['CONTENT_TYPE'];
        }
        switch($content_type) {
            case "application/json":
                $body_params = json_decode($body);
                if($body_params) {
                    foreach($body_params as $param_name => $param_value) {
                        $parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $postvars);
                foreach($postvars as $field => $value) {
                    $parameters[$field] = $value;
 
                }
                $this->format = "html";
                break;
            default:
                // we could parse other supported formats here
                break;
        }
        $this->parameters = $parameters;
    }
}










class cURL { 
	var $headers; 
	var $user_agent; 
	var $compression; 
	var $cookie_file; 
	var $proxy; 
	function cURL($cookies=TRUE,$cookie='cookies.txt',$compression='gzip',$proxy='') { 
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
		$this->headers[] = 'Connection: Keep-Alive'; 
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 
		$this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)'; 
		$this->compression=$compression; 
		$this->proxy=$proxy; 
		$this->cookies=$cookies; 
		if ($this->cookies == TRUE) $this->cookie($cookie); 
	} 
	function cookie($cookie_file) { 
		if (file_exists($cookie_file)) { 
			$this->cookie_file=$cookie_file; 
		} else { 
			fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions'); 
			$this->cookie_file=$cookie_file; 
			fclose($this->cookie_file); 
		} 
	} 
	function get($url) { 
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
		curl_setopt($process, CURLOPT_HEADER, 0); 
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
		curl_setopt($process,CURLOPT_ENCODING , $this->compression); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
		$return = json_decode(curl_exec($process),true);
		curl_close($process); 
		return $return; 
	} 
	function post($url,$data) { 
		$process = curl_init($url); 
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers); 
		curl_setopt($process, CURLOPT_HEADER, 1); 
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent); 
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file); 
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file); 
		curl_setopt($process, CURLOPT_ENCODING , $this->compression); 
		curl_setopt($process, CURLOPT_TIMEOUT, 30); 
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy); 
		curl_setopt($process, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($process, CURLOPT_POST, 1); 
		$return = curl_exec($process); 
		curl_close($process); 
		return $return; 
	} 
	function error($error) { 
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>"; 
		die; 
	} 
} 

	// Initialize options for REST interface
	$elastic_url="http://127.0.0.1:9200";
	$elastic_option_defaults = array(
		CURLOPT_HEADER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 2
	  ); 
	
	// ArangoDB REST function.
	// Connection are created demand and closed by PHP on exit.
	function elastic_rest($method,$uri,$query=NULL,$json=NULL,$options=NULL){
	  global $elastic_url,$elastic_link,$elastic_option_defaults;
	
	  // Connect 
	  if(!isset($elastic_link)) $elastic_link = curl_init();
	
	  //echo "<br>DB operation: $method $uri $query $json\n";
	
	  // Compose query
	  $options = array(
		CURLOPT_URL => $elastic_url.$uri."?".$query,
		CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS 
		CURLOPT_POSTFIELDS => $json,
	  ); 
	  curl_setopt_array($elastic_link,($options + $elastic_option_defaults)); 
	
	  // send request and wait for response
	  $response =  json_decode(curl_exec($elastic_link),true);
	
	  //echo "<br>response from DB: \n";
	  //print_r($response);
	  
	  return($response);
	}

?>
