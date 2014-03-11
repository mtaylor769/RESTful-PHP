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

function showDatabases() 
{
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


function stripCommas($str)
{
	if (strrpos($str, '.')) 
	{
		$lf = substr($str, 0, strrpos($str, '.'));
	} else {
		$lf = $str;
	}
	$return = ereg_replace(',', '', $lf);
	return $return;
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
?>
