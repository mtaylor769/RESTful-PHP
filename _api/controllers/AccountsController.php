<?php 
class AccountsController extends MyController {
    public function getAction($request) {
        if(isset($request->url_elements[3])) {
            $user_id = (int)$request->url_elements[3];
            if(isset($request->url_elements[4])) {
                switch($request->url_elements[4]) {
                case 'friends':
                    $data["message"] = "user " . $user_id . "has many friends";
                    break;
                case 'login':
                	$user = $this->login($request->parameters->username, $request->parameters->password);

                	$data['message'] = "login requested: " . print_r($user, true);
                	break;
                default:
                    // do nothing, this is not a supported action
                    break;
				}
            } else {
                $data["message"] = "here is the info for user " . $user_id . print_r($request->url_elements,true);
            }
        } else {
            $data["message"] = "you want a list of users";
        }
        return $data;
    }
 
    public function postAction($request) {
        $data = $request->parameters;
        $data['message'] = "This data was submitted";
        return $data;
    }

	public function login($username,$password) {
		global $server_name, $user_name, $user_passwd, $db_name;
		$link = mysql_connect("127.0.0.1", $user_name, $user_passwd,true)
			or die("Could not connect: " . mysql_errno() . ": " . mysql_error() . "\n");
		//print "Connected successfully<br>";
		mysql_select_db('test') or die("Could not select database: " . mysql_errno() . ": " . mysql_error()  ."<br>\n");
		$query = sprintf("SELECT id, first_name, last_name, email_address FROM upout_user 
			WHERE username='%s'",
			mysql_real_escape_string($username),
			mysql_real_escape_string($password));
		$result = mysql_query($query);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $query;
			die($message);
		}
		$rows = [];
		while ($row = mysql_fetch_object($result)) {
			//echo $row['first_name'];
			//echo $row['last_name'];
			//echo $row['email_address'];
			//print_r($row, 1);
			$rows[] = $row;
		}
		mysql_free_result($result);
		return $rows;
	}
	
}
?>