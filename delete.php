<?
require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/admin_auth.inc.php";

session_start();


/*if it's passed more than 30minutes since last action OR who access this page is not in session, redirect*/
if (auto_logout("adminLastAction") || !isset($_SESSION['admin'] )) {
	session_unset();
	session_destroy();
	header('Location:admin.php');
	exit ;
}


$time = date("Y-m-d H:i:s");

switch($_GET["id"]) {

	case 1 :
		/************************DELETE ITEM*******************************************************/
		$oid = mysql_query("delete  FROM items WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','items','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=1&op=2');
		break;
	case 2 :
		/************************DELETE FURNISHER******************************************************/
		$oid = mysql_query("delete  FROM furnishers WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','furnishers','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=1&op=4');
		break;
	
	case 3 :
		/************************DELETE USER**************************************************/
		$oid = mysql_query("delete from users  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','users','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=2&op=2');
		break;
	case 4 :
		/************************UNBAN USER************************************************/
		$oid = mysql_query("delete from ban  WHERE user = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','ban','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=2&op=4');
		break;
	case 5 :
		/************************DELETE SLIDESHOW IMAGE**********************************************/
		$path = getSingleResult("select path from slideshow  WHERE id = '{$_POST['id']}'", "path");
		$complete_path = getcwd() .'/../client/'. $path;
		if (!unlink($complete_path))
			echo "file does not exist";
		$oid = mysql_query("delete from slideshow  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','slideshow','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=3&op=2');
		break;
	case 6 :
		/*************************DELETE PRODUCT IMAGE********************************************/
		$path = getSingleResult("select path from items_images  WHERE id = '{$_POST['id']}'", "path");
		$complete_path = getcwd() .'/../client/'. $path;
		if (!unlink($complete_path))
			echo "file does not exist";
		$oid = mysql_query("delete from items_images  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','items_images','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=3&op=4');
		break;
	case 7 :
		/******************************DELETE ORDER FROM PURCHASE*********************************************/
		$oid = mysql_query("delete  FROM purchase WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','purchase','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=4&op=1');
		break;
	case 8 :
		/*************************DELETE POST**************************************************/
		$oid = mysql_query("delete from posts  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','posts','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=5&op=2');
		break;
	case 9 :
		/**************************DELETE COMMENT*************************************************/
		$oid = mysql_query("delete from comments  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','comments','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=5&op=2');
		break;
	case 10 :
		/***************************DELETE GROUP************************************************/
		$oid = mysql_query("delete from groups  WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','groups','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=6&op=3');
		break;
	case 11 :
		/****************************DELETE SERVICE FROM GROUP***********************************************/
		$oid = mysql_query("delete from services_groups  WHERE grp = '{$_POST['group']}'  and service='{$_POST['service']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','service_groups','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=6&op=5');
		break;
	case 12 :
		/****************************DELETE USER FROM GROUP***********************************************/
		$oid = mysql_query("delete from users_groups  WHERE grp = '{$_POST['group']}'  and user='{$_POST['user']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','users_groups','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=6&op=7');
		break;
	case 13 :
		/******************************DELETE EMAIL FROM NEWSLETTER*********************************************/
		$oid = mysql_query("delete  FROM newsletter WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','newsletter','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=7&op=2');
		break;
	case 14 :
		/******************************DELETE ELEMENT FROM MENU*********************************************/
		$oid = mysql_query("delete  FROM menu WHERE id = '{$_POST['id']}'") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','menu','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=9&op=2');
		break;

}
?>