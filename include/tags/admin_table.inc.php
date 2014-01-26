<?php
/**************************NOTE******************************
 -------------------------------------------------------------
 | 															!
 |	  A)  for give_me_users, give_me_groups:				|
 |   	the function returns the list of users and groups	|
 |															|
 |------------------------------------------------------------------------------------------------------------------------
 !																														|
 |	 B) for give_me_brands,give_me_furnishers,give_me_colours,give_me_items,give_me_parents 							|
 !	  there are 2 cases, these functions  can be called in 2 ways:														|
 |	  																													|
 |	  1)setContent("option",n | n<0),---->used in add cases																|
 |	  2)setContent("option",$_POST['id'])---->used in edit cases														|
 |	 																													|
 |	 *In the first case it returns all the brand, id are represented as 												|
 |	 	 positive integer so the number of rows of $qBS will be 0, and obv the  complement set  						!
 |	 	 Brand-selectedBrand will be equal to Brand;																	!
 |	 *in the second case it returns the selected brand in the first position selected, and all the other under it		!
 |	  																													!
 |------------------------------------------------------------------------------------------------------------------------
 *
 * */
require_once "include/dbms.inc.php";

Class admin_table extends taglibrary {

	function firstfunction() {
		return;
	}

	function give_me_brands($name, $data, $pars) {

		$brands = "";
		$qBS = mysql_query("select B.id, brand_name from brands B join items I where B.id =(select brand from items I where I.id={$data})") or die(mysql_error());
		if (mysql_num_rows($qBS) == 0) {
			$brands = "<option value='' disabled selected>Select a brand</option>";

		} else {
			$selectedBrand = mysql_fetch_array($qBS);
			$brands = "<option value=" . $selectedBrand['id'] . ">" . $selectedBrand['brand_name'] . "</option>";
		}

		$qBNS = getResult("select id, brand_name from brands where brand_name <> '{$selectedBrand['brand_name']}'");
		foreach ($qBNS as $key => $value) {
			$brands .= "<option value=" . $value['id'] . ">" . $value['brand_name'] . "</option>";
		}
		return $brands;
	}

	function give_me_furnishers($name, $data, $pars) {

		/*this function operates in the identical way of the function "give_me_brands"*/
		$furnishers = "";
		$qFS = mysql_query("select F.id,F.name from furnishers F join items I where F.id =(select furnisher from items where id={$data})") or die(mysql_error());
		if (mysql_num_rows($qFS) == 0) {
			$furnishers = "<option value='' disabled selected>Select a furnisher </option>";

		} else {
			$selectedFurnisher = mysql_fetch_array($qFS);
			$furnishers = "<option value=" . $selectedFurnisher['id'] . ">" . $selectedFurnisher['name'] . "</option>";
		}
		$qFNS = getResult("select id, name from furnishers where name <> '{$selectedFurnisher['name']}'");

		foreach ($qFNS as $key => $value) {
			$furnishers .= "<option value=" . $value['id'] . ">" . $value['name'] . "</option>";
		}
		return $furnishers;

	}

	function give_me_categories($name, $data, $pars) {

		/*this function operates in the identical way of the function "give_me_brands"*/
		$categories = "";
		$qCS = mysql_query("select C.id ,cat_name from categories  C join items I where C.id =(select category from items where id={$data})") or die(mysql_error());
		if (mysql_num_rows($qCS) == 0) {
			$categories = "<option value='' disabled selected>Select a category </option>";

		} else {
			$selectedCat = mysql_fetch_array($qCS);
			$categories = "<option value=" . $selectedCat['id'] . ">" . $selectedCat['cat_name'] . "</option>";
		}
		$qCNS = getResult("select id, cat_name from categories where cat_name <> '{$selectedCat['cat_name']}'");

		foreach ($qCNS as $key => $value) {
			$categories .= "<option value=" . $value['id'] . ">" . $value['cat_name'] . "</option>";
		}
		return $categories;

	}

	function give_me_colours($name, $data, $pars) {

		$colours = "";
		$qCS = mysql_query("select id,colour from items_images where id ={$data} ") or die(mysql_error());
		if (mysql_num_rows($qCS) == 0) {
			$colours = "<option value='' disabled selected>Select a colour </option>";
		} else {
			$selectedColour = mysql_fetch_array($qCS);
			$colours = "<option selected value=" . $selectedColour['colour'] . ">" . $selectedColour['colour'] . "</option>";
		}
		$qCNS = getResult("select name from colours where name not in (select colour from items_images where id ={$data})");

		foreach ($qCNS as $key => $value) {
			$colours .= "<option value=" . $value['name'] . ">" . $value['name'] . "</option>";
		}

		return $colours;

	}

	function give_me_icons($name, $data, $pars) {
		$icons = "";
		$qIS = mysql_query("select id,name from icons where id =(select icon from menu where id={$data}) ") or die(mysql_error());

		if (mysql_num_rows($qIS) == 0) {
			$icons = "<option value='none'  selected>None</option>";
		} else {

			$selected_icon = mysql_fetch_array($qIS);
			$icons = "<option selected value=" . $selected_icon['id'] . ">" . $selected_icon['name'] . "</option>";
		}
		$qINS = getResult("select id,name from icons where id not in (select icon from menu where id={$data}) ");

		foreach ($qINS as $key => $value) {
			$icons .= "<option value=" . $value['id'] . ">" . $value['name'] . "</option>";
		}
		return $icons;
	}

	function give_me_items($name, $data, $pars) {
		$items = "";
		$qIS = mysql_query("select id, name from items where id =(select item from items_images where id ={$data})") or die(mysql_error());
		if (mysql_num_rows($qIS) == 0) {
			$items = "<option value='' disabled selected>Select an item </option>";
		} else {
			$selectedItem = mysql_fetch_array($qIS);
			$items = "<option selected value=" . $selectedItem['id'] . ">" . $selectedItem['name'] . "</option>";
		}
		$qINS = getResult("select id, name from items where id not in (select item from items_images where id ={$data})");

		foreach ($qINS as $key => $value) {
			$items .= "<option value=" . $value['id'] . ">" . $value['name'] . "</option>";
		}

		return $items;

	}

	function give_me_parents($name, $data, $pars) {

		$qPS = mysql_query("select 	id,name from menu where id=(select parent_id from menu where id={$data}) ") or die(mysql_error());
		if (mysql_num_rows($qPS) == 0) {
			$parents = "<option value='' disabled selected>Select a parent </option>";
		} else {
			$selectedPar = mysql_fetch_array($qPS);
			$parents = "<option selected value=" . $selectedPar['id'] . ">" . $selectedPar['name'] . "</option>";
		}
		//if($selectedPar['name'] =="ROOT")
		$qPNS = getResult("select 	id,name from menu where id<>{$data} and id not in (select parent_id from menu where id={$data}) ");

		foreach ($qPNS as $key => $value) {
			$parents .= "<option value=" . $value['id'] . ">" . $value['name'] . "</option>";
		}

		return $parents;

	}

	function give_me_groups($name, $data, $pars) {
		$groups = "";
		$data = getResult("select id, name from groups");
		foreach ($data as $key => $value) {
			$groups .= "<option value=" . $value['id'] . "  >" . $value['name'] . "</option>";
		}
		return $groups;
	}

	function give_me_users($name, $data, $pars) {
		if ($pars['value'] == 'ban') {
			$query = "select username from users where username not in (select user from ban) and username<>'{$_SESSION['admin']['username']}'";

		} else {
			$query = "select * from users";
		}
		$users = "";
		$data = getResult($query);
		foreach ($data as $key => $value) {
			$users .= "<option value=" . $value['id'] . "  >" . $value['username'] . "</option>";
		}
		return $users;
	}

	function lastactions($name, $data, $pars) {
		$lastActs = "";
		//$data = getResult("select * from admin_actions order by DATE_FORMAT(DATE(datetime), '%d') DESC,TIME(datetime) DESC limit {$data}");
		$data = getResult("select * from admin_actions order by id DESC limit {$data}");

		foreach ($data as $key => $value) {

			/*format of mysql datetime*/
			$phpdate = strtotime($value['datetime']);
			$datetime = date('d-M H:i:s', $phpdate);

			switch($value['action']) {
				case 'add' :
					$lastActs .= "&nbsp;&nbsp;<img src=skins/admin/img/icons/add.png>&nbsp;";
					$lastActs .= $datetime . "   :  " . $value['username'] . " added a row into <b>" . $value['involved_table'] . "</b><br/>";
					break;
				case 'edit' :
					$lastActs .= "&nbsp;&nbsp;<img src=skins/admin/img/icons/pencil.png>&nbsp;";
					$lastActs .= $datetime . "   :  " . $value['username'] . " edited a row of <b>" . $value['involved_table'] . "</b><br/>";
					break;
				case 'delete' :
					$lastActs .= "&nbsp;&nbsp;<img src=skins/admin/img/icons/delete.png>&nbsp;";
					$lastActs .= $datetime . "   :  " . $value['username'] . " deleted a row from <b>" . $value['involved_table'] . "</b><br/>";

					break;
			}
		}
		return $lastActs;
	}

}
?>
