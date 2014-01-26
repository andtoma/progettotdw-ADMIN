<?


require_once "include/template2.inc.php";
require_once "include/dbms.inc.php";
require_once "include/image_upload.inc.php";
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
		/**************************ADD PRODUCT *************************************************/
		$oid = mysql_query("insert into items(name,description,furnisher,brand,category,sex,price) values('{$_POST['name']}','{$_POST['desc']}','{$_POST['furnisher']}','{$_POST['brand']}','{$_POST['category']}','{$_POST['sex']}','{$_POST['price']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','items','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=1&op=2');
		break;

	case 2 :
		/**************************ADD FURNISHER *************************************************/
		$oid = mysql_query("insert into furnishers(name,vat_no,phone1,phone2,email) values('{$_POST['name']}','{$_POST['vat']}','{$_POST['phone1']}','{$_POST['phone2']}','{$_POST['email']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','furnishers','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=1&op=4');
		break;
	case 3 :
		/**************************ADD PRODUCT AVAILABILITY*************************************************/
		if ($_POST['quantity'] > 0) {
			$oid = mysql_query("insert into availability(item, size, colour, quantity) values('{$_POST['item']}','{$_POST['size']}','{$_POST['colour']}','{$_POST['quantity']}')") or die(mysql_error());
			$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','availability','{$time}')") or die(mysql_error());
		}
		header('Location:admin.php?sel=1&op=6');
		break;
	case 4 :
		/**************************ADD USER*************************************************/
		$oid = mysql_query("insert into users(name,surname,email, sex, birth_date,password,username ) values('{$_POST['name']}','{$_POST['surname']}','{$_POST['email']}','{$_POST['sex']}','{$_POST['birthdate']}',MD5('{$_POST['password']}'),'{$_POST['username']}')") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','users','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=2&op=2');
		break;
	case 5 :
		/**************************BAN USER*************************************************/
		/*calculate the expire date*/

		$date = date("Y-m-d");
		switch($_POST['duration']) {
			case '1' :
				$expiration = date("Y-m-d", strtotime($date . "+ 1 days"));
				break;
			case '7' :
				$expiration = date("Y-m-d", strtotime($date . "+ 7 days"));
				break;
			case '30' :
				$expiration = date("Y-m-d", strtotime($date . "+ 30 days"));
				break;
			case '365' :
				$expiration = date("Y-m-d", strtotime($date . "+ 1 years"));
				break;
			case 'never' :
				$expiration = date("Y-m-d", strtotime($date . "+ 100 years"));
				//bug to fix
				//hoping that life expectancy will not grow too much
				break;
		}
		$oid = mysql_query("insert into ban(reason, expiration,user) values('{$_POST['reason']}','{$expiration}','{$_POST['user']}')") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','ban','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=2&op=4');
		break;
	case 6 :
		/**************************ADD SLIDESHOW IMAGE*************************************************/
		$res = upload("/skins/BeClothing/img/slideshow/");
		if ($res) {
			$main = new Skin("admin");
			$main -> setContent("title", "Add a slideshow image");
			$form = new Skinlet("slideshow_form");
			$form -> setContent("message", $res);
			$form -> setContent("info", "(only jpg, size limit is 1 Mb)");
			$form -> setContent("action", "add.php?id=6");
			$form -> setContent("button", "Add");
			$main -> setContent("content", $form -> get());
			$main -> close();
		} else {
			$path = "skins/BeClothing/img/slideshow/" . $_FILES["uploaded_file"]["name"];
			$oid = mysql_query("insert into slideshow(path, title, description) values('{$path}','{$_POST['title']}','{$_POST['desc']}')") or die(mysql_error());
			$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','slideshow','{$time}')") or die(mysql_error());
			header('Location:admin.php?sel=3&op=2');
		}
		break;
	case 7 :
		/**************************ADD ITEM IMAGE*************************************************/
		$res = upload("/skins/BeClothing/img/items/");
		if ($res) {
			$main = new Skin("admin");
			$main -> setContent("title", "Add a slideshow image");
			$form = new Skinlet("product_image_form");
			$form -> setContent("message", $res);
			$form -> setContent("info", "(only jpg, size limit is 1 Mb)");
			$form -> setContent("action", "add.php?id=7");
			$form -> setContent("options", -1);
			$form -> setContent("button", "Add");
			$main -> setContent("content", $form -> get());
			$main -> close();
		} else {
			$path = "skins/BeClothing/img/items/" . $_FILES["uploaded_file"]["name"];
			$oid = mysql_query("insert into items_images(path, colour, item) values('{$path}','{$_POST['colour']}','{$_POST['item']}')") or die(mysql_error());
			//$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','items_images','{$time}')") or die(mysql_error());
			header('Location:admin.php?sel=3&op=4');
		}
		break;
	case 8 :
		/**************************ADD POST*************************************************/
		$date = date("j F Y, g:i a");
		$oid = mysql_query("insert into posts(title, text,datetime,username) values('{$_POST['title']}','{$_POST['text']}','{$date}','{$_SESSION['admin']['username']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','posts','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=5&op=2');
		break;
	case 9 :
		/**************************ADD GROUP*************************************************/
		$oid = mysql_query("insert into groups(name) values('{$_POST['name']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','groups','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=6&op=3');
		break;
	case 10 :
		/**************************ADD SERVICE TO GROUP*************************************************/

		$oid = mysql_query("insert into services_groups values('{$_POST['group']}','{$_POST['service']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','groups_services','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=6&op=4');
		break;
	case 11 :
		/**************************ADD USER INTO GROUP*************************************************/
		$oid = mysql_query("insert into users_groups values('{$_POST['user']}','{$_POST['group']}') ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','users_groups','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=6&op=6');
		break;
	case 12 :
		/**************************ADD EMAIL*************************************************/
		$oid = mysql_query("insert into newsletter(email) values('{$_POST['email']}')") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','newsletter','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=7&op=2');
		break;
	case 13 :
		/**************************ADD MENU*************************************************/
		/*calcolo posizione prendendo la posizione massima di tutti quelli che hanno lo stesso padre
		 * di quella che vado ad aggiungere*/
		$q = mysql_query("select MAX(position) as max from menu where parent_id='{$_POST['parent']}'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		$pos = $data['max'] + 1;
		$oid = mysql_query("insert into menu(name, link, parent_id,position,icon) values('{$_POST['name']}','{$_POST['link']}','{$_POST['parent']}','{$pos}','{$_POST['icon']}')") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','add','menu','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=9&op=2');
		break;
}
