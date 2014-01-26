<?

require_once "include/template2.inc.php";
require_once "include/dbms.inc.php";
require_once "include/admin_auth.inc.php";

session_start();

if (!isset($_SESSION['admin'])) {
	/*************USER IS NOT IN SESSION -->login********/
	admin_login();
}

/*************USER CAN ACCESS, check if he's been inactive for more than 30 minutes**********/

if (auto_logout("adminLastAction")) {
	unset($_SESSION['admin']);
	header('Location:admin.php');
	exit ;
}

/*NOTE: both procedures are located into admin_aut.inc.php*/

$main = new Skin("admin");

/*******************************
 LEGEND:
 * sel 1=Catalog
 * sel 2=Users
 * sel 3=Images
 * sel 4=Orders
 * sel 5=Post
 * sel 6=Privileges
 * sel 7=Newsletter
 * sel 8= Contact us requests
 * sel 9=Menu
 * sel 10=Site info
 * sel 11= Last actions
 ********************************/

switch($_GET['sel']) {
	case 1 :
		/*************Add Product***********/
		switch($_GET['op']) {
			case 1 :
				$main -> setContent("title", "Add a product");
				$form = new Skinlet("product_form");
				$form -> setContent("options", -1);
				$form -> setContent("button", "Add");
				$form -> setContent("action", "add.php?id=1");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/*************Edit/Delete Product***********/
				$main -> setContent("title", "Edit or Delete product info");
				$table = new Skinlet("table");
				$query = "SELECT I.id as ID , I.name, description, F.name as furnisher, brand_name as brand ,cat_name as 'type' ,sex as target,FLOOR(price-price*(discount)/100) as price  from items I join furnishers F join brands B join categories C where category=C.id and I.brand=B.id and I.furnisher=F.id";

				$array = array(query => $query, type => "products_table", actionEdit => "edit.php?id=1&op=0", actionDelete => "delete.php?id=1");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 3 :
				/*************Add Furnisher***********/
				$main -> setContent("title", "Add a Furnisher");
				$form = new Skinlet("furnisher_form.html");
				$form -> setContent("button", "Add");
				$form -> setContent("action", "add.php?id=2");
				$main -> setContent("content", $form -> get());
				break;
			case 4 :
				/*************Edit/Delete Furnisher***********/
				$main -> setContent("title", "Edit or Delete furnisher info");
				$table = new Skinlet("table");
				$query = "select * from furnishers";

				$array = array(query => $query, type => "furnishers_table", actionEdit => "edit.php?id=2&op=0", actionDelete => "delete.php?id=2");

				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 5 :
				/*************Set/Unset Discount***********/
				$main -> setContent("title", "Edit or Delete furnisher info");
				$table = new Skinlet("table");
				$query = "select id as id ,name, price as 'full price',discount as '% discount', ROUND(price-price*(discount)/100,2) as 'discounted price' from items";

				$array = array(query => $query, type => "discount_table", actionSet => "edit.php?id=3&op=0", actionUnset => "delete.php?id=4");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;

			case 6 :
				/*************See Product Availability***********/
				$main -> setContent("title", "Products Availability");
				$table = new Skinlet("table");
				#$query = "select id, name from items";
				$query = "select I.id as ID,I.name, A.colour,A.size, A.quantity  as 'items number'from items I join availability A where I.id=A.item and  A.quantity>0 order by I.name, colour";

				$array = array(query => $query, type => "products_availability_table", );

				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 7 :
				/*************add Product Availability***********/
				$main -> setContent("title", "Add Product Availability");
				$table = new Skinlet("availability_form");
				$table -> setContent("button", "Add");
				$table -> setContent("options", 0);
				$table -> setContent("idI", "itemAdd");
				//<---jquery/ajax needs this
				$table -> setContent("idC", "colourAdd");
				//<---jquery/ajax needs this
				$table -> setContent("action", "add.php?id=3");
				$main -> setContent("content", $table -> get());
				break;
			case 8 :
				/************* Update Product Availability***********/
				$main -> setContent("title", "Edit a Product Availability");
				$table = new Skinlet("availability_form");
				$table -> setContent("button", "Edit");
				$table -> setContent("options", 0);
				$table -> setContent("idI", "itemEdit");
				//<---jquery/ajax needs this
				$table -> setContent("idC", "colourEdit");
				//<---jquery/ajax needs this
				$table -> setContent("action", "edit.php?id=5");
				$main -> setContent("content", $table -> get());
				break;
		}
		break;

	case 2 :
		switch($_GET['op']) {
			case 1 :
				/*************Add an User***********/
				$main -> setContent("title", "Add an user");
				$form = new Skinlet("user_form");
				$form -> setContent("button", "Add");
				$form -> setContent("action", "add.php?id=4");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/*************Edit/Delete an User***********/
				$main -> setContent("title", "Edit or Delete user info");
				$table = new Skinlet("table");
				$query = "select id as id ,name,surname, sex, DATE_FORMAT(birth_date,' %e %M %Y') as 'birthdate' , email,username from users";

				$array = array(query => $query, type => "users_table", actionEdit => "edit.php?id=6&op=0", actionDelete => "delete.php?id=3");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 3 :
				/*************Ban an user***********/
				$main -> setContent("title", "Ban an user");
				$form = new Skinlet("ban_form");
				$form -> setContent("button", "Ban");
				$form -> setContent("action", "add.php?id=5");
				$main -> setContent("content", $form -> get());
				break;
			case 4 :
				/*************UnBan an user***********/

				$main -> setContent("title", "Unban an user");
				$query = "select U.id as ID, U.username, B.reason,B. expiration from ban B join users U on U.id=B.user ";
				$array = array(query => $query, type => "unban_table", actionDelete => "delete.php?id=4");
				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
		}
		break;
	case 3 :
		switch($_GET['op']) {
			case 1 :
				/*************add image to slideshow***********/
				$main -> setContent("title", "Add a slideshow image");
				$form = new Skinlet("slideshow_form");
				$form -> setContent("info", "(only jpg, size limit is 1 Mb)");
				$form -> setContent("action", "add.php?id=6");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/************* edit/delete image to slideshow***********/
				$main -> setContent("title", "Edit/Delete a slidehow image");

				$array = array(type => "slideshow_table", actionEdit => "edit.php?id=7&op=0", actionDelete => "delete.php?id=5");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 3 :
				/*************add item image***********/
				$main -> setContent("title", "Add an item picture");
				$form = new Skinlet("product_image_form");
				$form -> setContent("options", -1);
				$form -> setContent("info", "(only jpg, size limit is 1 Mb)");
				$form -> setContent("action", "add.php?id=7");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 4 :
				/************* edit/delete item image***********/
				$main -> setContent("title", "Edit/Delete a product image");

				$array = array(type => "item_image_table", actionEdit => "edit.php?id=8&op=0", actionDelete => "delete.php?id=6");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
		}
		break;
	case 4 :
		switch($_GET['op']) {
			case 1 :
				/************* edit/delete processing order***********/
				$query = "select P.id as ID, CONCAT (U.name,' ',U.surname) as customer,I.name, 
				CONCAT (P.item_price,' $' )as 'Price per item', P.quantity,
				CONCAT( P.quantity*P.item_price,' $' )as 'order total', 
				CONCAT(P.address,', ', P.city,', ', P.zip_code,', ', P.state, ', ',P.country) as address, 
				DATE_FORMAT(P.datetime, ' %e %M %Y, %h:%i %p') as 'date of order' 
				from purchase P join users U join items I where P.user=U.id and P.item=I.id and P.status='processing'";

				$main -> setContent("title", "Manage Processing orders");

				$array = array(query => $query, type => "processing_orders_table", actionEdit => "edit.php?id=9", actionDelete => "delete.php?id=7");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 2 :
				/************* shipped orders***********/
				$query = "select P.id as ID, CONCAT (U.name,' ',U.surname) as customer,I.name, 
				CONCAT (P.item_price,' $' )as 'Price per item', P.quantity,
				CONCAT( P.quantity*P.item_price,' $' )as 'order total', 
				CONCAT(P.address,', ', P.city,', ', P.zip_code,', ', P.state, ', ',P.country) as address, 
				DATE_FORMAT(P.datetime, ' %e %M %Y, %h:%i %p') as 'date of order' 
				from purchase P join users U join items I where P.user=U.id and P.item=I.id and P.status='shipped'";

				$main -> setContent("title", "Shipped Orders");

				$array = array(query => $query, type => "shipped_orders_table", );

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
		}
		break;

	case 5 :
		switch($_GET['op']) {
			/*************Create a Post***********/
			case 1 :
				$main -> setContent("title", "Create a post");
				$form = new Skinlet("post_form");
				$form -> setContent("action", "add.php?id=8");
				$form -> setContent("button", "Publish");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/*************Edit/Delete a Post***********/
				$main -> setContent("title", "Edit or Delete a post");
				$query = "select id, username as creator, title, text, datetime as date from posts";

				$array = array(query => $query, type => "posts_table", actionEdit => "edit.php?id=10&op=0", actionDelete => "delete.php?id=8", actionComments => "admin.php?sel=5&op=3");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 3 :
				/*************Edit/Delete a Comment***********/
				$main -> setContent("title", "Edit or Delete a comment");
				$query = "select id, username as creator, title, text, datetime as date from comments where post='{$_POST['id']}'";

				$array = array(query => $query, type => "comments_table", actionEdit => "edit.php?id=11&op=0", actionDelete => "delete.php?id=9", );

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
		}
		break;
	case 6 :
		switch($_GET['op']) {
			case 1 :
				/*************See service***********/
				$main -> setContent("title", "Services");
				$query = "select id as Id, name as 'service name' from services";

				$array = array(query => $query, type => "services_table");
				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;

			case 2 :
				/************Add a group**************/
				$main -> setContent("title", "Add a group");
				$form = new Skinlet("group_form");
				$form -> setContent("action", "add.php?id=9");
				$form -> setContent("plc_name", "Enter group name");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 3 :
				/*************Edit/Delete a group ***********/
				$main -> setContent("title", "Groups management");
				$query = "select id as Id, name as 'group name' from groups";

				$array = array(query => $query, type => "groups_table", actionEdit => "edit.php?id=12&op=0", actionDelete => "delete.php?id=10");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 4 :
				/*************add service to group  ***********/
				$main -> setContent("title", "Add services to group");
				$form = new Skinlet("group_service_associate");
				$form -> setContent("option", 0);
				$form -> setContent("id", "group_add");
				//<---jquery/ajax needs this
				$form -> setContent("action", "add.php?id=10");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 5 :
				/*************remove service from group  ***********/

				$main -> setContent("title", "Remove service from group");
				$form = new Skinlet("group_service_associate");
				$form -> setContent("option", 0);
				$form -> setContent("id", "group_remove");
				//<---jquery/ajax needs this
				$form -> setContent("action", "delete.php?id=11");
				$form -> setContent("button", "Remove");
				$main -> setContent("content", $form -> get());
				break;
			case 6 :
				/*************add user into group  ***********/

				$main -> setContent("title", "Add user into group");
				$form = new Skinlet("user_group_associate");
				//<---jquery/ajax needs this
				$form -> setContent("option", 0);
				$form -> setContent("id", "user_add");
				$form -> setContent("action", "add.php?id=11");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 7 :
				/*************remove user from  group***********/

				$main -> setContent("title", "Remove user from group");
				$form = new Skinlet("user_group_associate");
				$form -> setContent("option", 0);
				$form -> setContent("id", "user_remove");
				//<---jquery/ajax needs this
				$form -> setContent("action", "delete.php?id=12");
				$form -> setContent("button", "Remove");
				$main -> setContent("content", $form -> get());
				break;
		}
		break;

	case 7 :
		switch($_GET['op']) {
			case 1 :
				/************Add an email to newsletter**************/
				$main -> setContent("title", "Newsletter");
				$main -> setContent("subtitle", "Add an email to newsletter");
				$form = new Skinlet("newsletter_form");
				$form -> setContent("action", "add.php?id=12");
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/************Edit/remove email from newsletters**************/
				$main -> setContent("title", "Newsletter");
				$main -> setContent("subtitle", "manage subscribers email");
				$query = "SELECT * from newsletter";

				$array = array(query => $query, type => "newsletter_table", actionEdit => "edit.php?id=13&op=0", actionDelete => "delete.php?id=13");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());

				break;
		}
		break;
	case 8 :
		switch($_GET['op']) {
			/************read Message sent from contact us form**************/

			case 1 :
				$main -> setContent("title", "Unread customers requests");
				$query = "SELECT id,name, email, message from contact_requests where is_read='false'";

				$array = array(query => $query, type => "unread_message_table", actionEdit => "edit.php?id=17");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 2 :
				/************read Message sent from contact us form**************/

				$main -> setContent("title", "Read customers requests");
				$query = "SELECT id,name,email, message from contact_requests where is_read='true'";

				$array = array(query => $query, type => "read_message_table");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
		}
		break;

	case 9 :
		switch($_GET['op']) {
			case 1 :
				/************Add item into menu**************/
				$main -> setContent("title", "Add menu");
				$form = new Skinlet("menu_form");
				$form -> setContent("action", "add.php?id=13");
				$form -> setContent("options", -1);
				$form -> setContent("val", -1);
				$form -> setContent("button", "Add");
				$main -> setContent("content", $form -> get());
				break;
			case 2 :
				/************Edit/Delete item from menu**************/
				$main -> setContent("title", "Edit/Delete menu");
				$query = "SELECT A.id as ID, A.name,  B.name as 'parent name',A.position, A.link, I.name as icon from menu A join icons I join menu B where A.parent_id=B.id and A.icon=I.id and A.id <> 0 order by A.parent_id, A.position asc";

				$array = array(query => $query, type => "menu_table", actionEdit => "edit.php?id=15&op=0", actionDelete => "delete.php?id=14");

				$table = new Skinlet("table");
				$table -> setContent("content", $array);
				$main -> setContent("content", $table -> get());
				break;
			case 3 :
				/************Swap items position**************/
				$main -> setContent("title", "Swap items position");
				$form = new Skinlet("menu_pos");
				$form -> setContent("action", "edit.php?id=16");
				$form -> setContent("options", -1);
				$form -> setContent("button", "Swap");
				$main -> setContent("content", $form -> get());
				break;
		}
		break;
	case 10 :
		/************Edit site info**************/
		$main -> setContent("title", "Edit site infos");
		$query = "select info_type as type, info_text as info from site_infos";

		$array = array(query => $query, type => "site_info_table", actionEdit => "edit.php?id=14&op=0", actionDelete => "delete.php?id=14");

		$table = new Skinlet("table");
		$table -> setContent("content", $array);
		$main -> setContent("content", $table -> get());

		break;
	case 11 :
		/************show last 100 actions**************/
		$main -> setContent("title", "History");
		$dash = new Skinlet("dashboard");
		$dash -> setContent("num", 100);
		$dash -> setContent("actions", 100);
		$main -> setContent("content", $dash -> get());
		break;
	default :
		/************main page**************/
		$main -> setContent("title", "Welcome back {$_SESSION['admin']['username']}!");
		$dash = new Skinlet("dashboard");
		$dash -> setContent("actions", 5);
		$dash -> setContent("num", 5);
		$dash -> setContent("act", "more +");
		$calendar = new Skinlet("calendar");
		$dash -> setContent("calendar", $calendar -> get());
		$main -> setContent("content", $dash -> get());
		break;
}

$main -> close();
?>