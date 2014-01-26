<?

session_start();

require_once "include/template2.inc.php";
require_once "include/dbms.inc.php";

/**************************************************************************************************
 this procedure is used to check login data, if they are correct users will be placed in session
 **************************************************************************************************/

function admin_login() {
	/********first time that the user opens admin.php *******/

	if (!isset($_POST["email"]) && !isset($_POST["password"])) {

		$main = new Skin("login");
		$main -> setContent("message", 0);
		$main -> close();
		exit ;

	} else {
		/********he's coming here pressing SIGN IN*******/

		$email = mysql_escape_string($_POST['email']);
		//anti injection
		$password = MD5($_POST['password']);

		$oid = mysql_query("SELECT * FROM users WHERE email = '{$email}' AND password = '{$password}'") or die(mysql_error());
		if (mysql_num_rows($oid) == 0) {
			/****no results => wrong data (or wrong query, I hope it's the first case ), I reload the login form with a message error*/
			$main = new Skin("login");
		$main -> setContent("message", 1);
		$main -> close();
			exit ;
		} else {
			/****data are correct, I check if he has permissions ****/
				$data = mysql_fetch_assoc($oid);
			if (check_permission("control_panel_access",$data['id'])) {
				/* I put user in session*/
				$_SESSION['admin'] = $data;
				$_SESSION['adminLastAction'] = time();

				return;

			} else {
			/* He has not the permission I reload the login form with a message error*/
				$main = new Skin("login");
		$main -> setContent("message", 2);
		$main -> close();
			}

		}
	}

}

/*************************************************************************************************
 this function is used to check if the users groups is one of the group that has
 the control panel access, if so it returns true
 ************************************************************************************************/

function match_verifier($data1, $data2) {
	foreach ($data1 as $key => $value) {
		if (in_array($value, $data2)) {
			return true;
		}
	}
	return false;
}

/*************************************************************************************************
 this procedure checks if the user has the permissions to login into the admin
 * panel, if so it returns to admin.php, else the procedure loads the login
 * form with an error message
 ************************************************************************************************/

function check_permission($service,$id) {

	/* groups that can access into the admin panel*/
	$q1 = ("select grp from services_groups where service=(select id from services where
name='{$service}')");

	$data1 = getResult($q1);

	/*user group*/
	$q2 = ("select grp from users_groups where user ='{$id}'");

	$data2 = getResult($q2);
	
	return match_verifier($data1, $data2);

}

/*************************************************************************************************
 this procedure checks if the admin has been inactive for more than 30minuted
 ************************************************************************************************/

function auto_logout($lastActTime) {
	$t1 = time();
	$t0 = $_SESSION[$lastActTime];
	$diff = $t1 - $t0;
	if ($diff > 3000) {
		return true;
	} else {
		$_SESSION[$lastActTime] = time();
		return false;
	}
}
?>