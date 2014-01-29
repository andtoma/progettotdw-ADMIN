<?php
/********************************************PLEASE NOTE****************************************************
 the edit is performed in 2 phases, in the first will be load  the form precompiled,
 in the secondthe edit takes place
 id says what table we want to edit, op can be 0 or 1, 0 is the phase 1, 1 the phase 2 .
 ***********************************************************************************************************/
require_once "include/template2.inc.php";
require_once "include/dbms.inc.php";
require_once "include/admin_auth.inc.php";

session_start();

/*if it's passed more than 30minutes since last action OR who access this page is not in session, redirect*/
if (auto_logout("adminLastAction") || !isset($_SESSION['admin'])) {
	session_unset();
	session_destroy();
	header('Location:admin.php');
	exit ;
}

$main = new Skin("admin");

$time = date("Y-m-d H:i:s");

switch($_GET["id"]) {

	case 1 :
		/************************EDIT ITEM INFO*******************************************************/

		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("product_form");
				$main -> setContent("title", "Edit a product");
				//look in admin_table.inc.php
				$form -> setContent("options", $_POST['id']);
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=1&op=1");
				$main -> setContent("content", $form -> get());

				break;
			case 1 :
				$oid = mysql_query("UPDATE items set name='{$_POST['name']}',description='{$_POST['desc']}',sex='{$_POST['sex']}', furnisher= '{$_POST['furnisher']}',brand='{$_POST['brand']}',category='{$_POST['category']}',price='{$_POST['price']}' where id=  '{$_POST['id']}'  ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','items','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=1&op=2');
				break;
		}
		break;
	case 2 :
		/************************EDIT FURNISHER INFO*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("furnisher_form");
				$main -> setContent("title", "Edit info about furnisher");

				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=2&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update furnishers set name='{$_POST['name']}', vat_no='{$_POST['vat']}',phone1='{$_POST['phone1']}',phone2='{$_POST['phone2']}', email='{$_POST['email']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','furnishers','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=1&op=4');
				break;
		}

		break;
	case 3 :
		/*************************SET DISCOUNT******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("discount_form");
				$main -> setContent("title", "Set a discount");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Set");
				$form -> setContent("action", "edit.php?id=3&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update items set discount='{$_POST['discount']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','items','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=1&op=5');
				break;
		}
		break;
	case 4 :
		/************************UNSET DISCOUNT*******************************************************/
		$query = mysql_query("update items set discount=0 where id='{$_POST['id']}' ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','items','{$time}')") or die(mysql_error());

		header('Location:admin.php?sel=1&op=5');
		break;
	case 5 :
		/************************EDIT PRODUCT AVAILABILITY*******************************************************/
		if ($_POST['quantity'] == 0) {
			$oid = mysql_query("delete from availability where item='{$_POST['item']}' and colour='{$_POST['colour']}' and size='{$_POST['size']}'") or die(mysql_error());
			$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','delete','availability','{$time}')") or die(mysql_error());

		} else {
			$oid = mysql_query("update availability set quantity='{$_POST['quantity']}' where item='{$_POST['item']}' and colour='{$_POST['colour']}' and size='{$_POST['size']}'") or die(mysql_query());
			$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','availability','{$time}')") or die(mysql_error());

		}
		header('Location:admin.php?sel=1&op=6');
		break;
	case 6 :
		/************************EDIT USER INFO*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("user_form");
				$main -> setContent("title", "Edit user info");
				//look in table_filler.inc.php
				$form -> setContent("dis", "disabled");
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=6&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update users set name='{$_POST['name']}', surname='{$_POST['surname']}',sex='{$_POST['sex']}',birth_date='{$_POST['birthdate']}',email='{$_POST['email']}', username='{$_POST['username']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','users','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=2&op=2');
				break;
		}

		break;
	case 7 :
		/************************EDIT SLIDESHOW INFO*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("slideshow_form");
				$main -> setContent("title", "Edit Slideshow details");
				//look in table_filler.inc.php
				$form -> setContent("dis", "disabled");
				$form -> setContent("info", "(to change image, create a new slideshow and delete this one)");
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=7&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$oid = mysql_query("update slideshow set title='{$_POST['title']}', description='{$_POST['desc']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','slideshow','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=3&op=2');
				break;
		}

		break;
	case 8 :
		/************************EDIT PRODUCT IMAGE*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("product_image_form");
				$main -> setContent("title", "Edit Image info");
				//look in table_filler.inc.php
				$form -> setContent("dis", "disabled");
				$form -> setContent("info", "(to change image delete this one and add the image you want)");
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("options", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=8&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$oid = mysql_query("update items_images set item='{$_POST['item']}', colour='{$_POST['colour']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','items_images','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=3&op=4');
				break;
		}
		break;
	case 9 :
		/************************EDIT ORDER STATUS*******************************************************/
		$oid = mysql_query("update purchase set status='shipped' where id='{$_POST['id']}' ") or die(mysql_error());
		$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','purchase','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=4&op=2');
		break;
	case 10 :
		/************************EDIT POST*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("post_form");
				$main -> setContent("title", "Edit a post");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=10&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update posts set title='{$_POST['title']}', text='{$_POST['text']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','posts','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=5&op=2');
				break;
		}
		break;
	case 11 :
		/************************EDIT COMMENT*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("comment_form");
				$main -> setContent("title", "Edit a comment");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=11&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update comments set title='{$_POST['title']}', text='{$_POST['text']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','comments','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=5&op=2');
				break;
		}
		break;
	case 12 :
		/************************EDIT GROUP(NAME)*******************************************************/

		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("group_form");
				$main -> setContent("title", "Edit a group name");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=12&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update groups set name='{$_POST['name']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','groups','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=6&op=3');
				break;
		}

		break;
	case 13 :
		/************************EDIT NEWSLETTER EMAIL*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("newsletter_form");
				$main -> setContent("title", "Edit a subscribers email");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=13&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$oid = mysql_query("update newsletter set email='{$_POST['email']}' where id='{$_POST['id']}' ") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','newsletter','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=7&op=2');
				break;
		}
		break;
	case 14 :
		/************************EDIT SITE INFO*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("site_info_form");
				$main -> setContent("title", "Edit a site information");
				//look in table_filler.inc.php
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=14&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				$query = mysql_query("update site_infos set info_text= '{$_POST['info']}' where info_type='{$_POST['info_name']}'") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','site_infos','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=10');

				break;
		}
		break;
	case 15 :
		/************************EDIT MENU ITEM*******************************************************/
		switch($_GET["op"]) {
			case 0 :
				$form = new Skinlet("menu_form");
				$main -> setContent("title", "Edit a menu item");
				$form -> setContent("val", $_POST['id']);
				$form -> setContent("options", $_POST['id']);
				$form -> setContent("button", "Edit");
				$form -> setContent("action", "edit.php?id=15&op=1");
				$main -> setContent("content", $form -> get());
				break;
			case 1 :
				/*o inserisco all'ultima posizione*/
				$q = mysql_query("select position, parent_id from menu where id='{$_POST['id']}'") or die(mysql_error());
				$data = mysql_fetch_assoc($q);
				if ($data['parent_id'] == $_POST['parent']) {
					$pos = $data['position'];
				} else {
					$q = mysql_query("select MAX(position) as max from menu where parent_id='{$_POST['parent']}'") or die(mysql_error());
					$data = mysql_fetch_assoc($q);
					$pos = $data['max'] + 1;
				}
				$oid = mysql_query("update menu set icon='{$_POST['icon']}', name= '{$_POST['name']}',link= '{$_POST['link']}',parent_id= '{$_POST['parent']}', position={$pos} where id='{$_POST['id']}'") or die(mysql_error());
				$oid2 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','menu','{$time}')") or die(mysql_error());

				header('Location:admin.php?sel=9&op=2');

				break;
		}
		break;
	case 16 :
		/************************SWAP ITEMS POSITION*******************************************************/
		/*I get  the position of item 1*/
		$q1 = mysql_query("select position from menu where id='{$_POST['item1']}'") or die(mysql_error());
		$data1 = mysql_fetch_assoc($q1);
		$pos1 = $data1['position'];
		/*I get  the position of item 2*/
		$q2 = mysql_query("select position from menu where id='{$_POST['item2']}'") or die(mysql_error());
		$data2 = mysql_fetch_assoc($q2);
		$pos2 = $data2['position'];

		$oid1 = mysql_query("update menu set position='{$pos2}' where id='{$_POST['item1']}'") or die(mysql_error());
		$oid2 = mysql_query("update menu set position='{$pos1}'  where id='{$_POST['item2']}'") or die(mysql_error());
		$oid3 = mysql_query("insert into admin_actions(username,action,involved_table,datetime) values('{$_SESSION['admin']['username']}','edit','menu','{$time}')") or die(mysql_error());
		header('Location:admin.php?sel=9&op=2');

		break;
	case 17 :
		/************************MARK A MESSAGE AS READ*******************************************************/

		$oid = mysql_query("update contact_requests set is_read='true' where id='{$_POST['id']}'") or die(mysql_error());
		header('Location:admin.php?sel=8&op=2');

		break;
}
$main -> close();
?>