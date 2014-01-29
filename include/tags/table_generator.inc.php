<?php

require_once "include/dbms.inc.php";

Class table_generator extends taglibrary {

	function firstfunction() {
		return;
	}

	function generate($name, $data, $pars) {

		switch($data['type']) {
			/****************************************table with query columns and 2 buttons 1 for editing and 1 for deleting****************************************************************************/
			case "products_table" :
			case "furnishers_table" :
			case "users_table" :
			case "comments_table" :
			case "groups_table" :
			case "newsletter_table" :
			case "menu_table" :
				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>edit</th><th>delete</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-warning'><i class='icon-pencil' ></i></button></form></td>";

					$table .= "<td><form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";
				}
				$table .= "</tr>";

				return $table;

				break;
			/***************************************************table with query columns and 2 buttons 1 for set discount and 1 for unset*********************************************************************/
			case "discount_table" :
				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);

				$table .= "<th>set discount</th><th>unset discount</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionSet'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-success'><i class='icon-ok' ></i></button></form></td>";
					$table .= "<td><form action=" . $data['actionUnset'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";

				}
				$table .= "</tr>";
				return $table;
				break;
			/*******************************************************table with just query result columns *************************************************************/
			case "products_availability_table" :
			case "shipped_orders_table" :
			case "services_table" :
			case "read_message_table" :
				$result = mysql_query($data['query']) or die(mysql_error());
				$table = "<tr>";
				$table .= giveMeColumnsName($result);

				$table .= "</tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
				}
				$table .= "</tr>";

				return $table;

				break;
			/*******************************************************table with  query result columns and an unban column*************************************************************/
			case "unban_table" :
				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>UnBan</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-success'><i class='icon-ok' ></i></button></form></td>";
					$table .= "</tr>";

				}
				return $table;
				break;
			case "slideshow_table" :
				/*******************************************************slideshow table*************************************************************/

				$table .= "<tr><th>Id</th><th>Tile</th><th>Description</th><th>Preview</th><th>Edit</th><th>Delete</th></tr>";
				$img_data = getResult("select * from slideshow");
				foreach ($img_data as $key => $value) {
					$path="../client/".$value['path'];
					$table .= "<tr>";
					$table .= "<td>" . $value['id'] . "</td>";
					$table .= "<td>" . $value['title'] . "</td>";
					$table .= "<td>" . $value['description'] . "</td>";
					$table .= "<td><img src=" . $path . "  width='80' height='50'</td>";
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $value['id'] . " class='btn btn-xs btn-warning'><i class='icon-pencil' ></i></button></form></td>";
					$table .= "<td><form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $value['id'] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";
					$table .= "</tr>";
				}

				$table .= "</tr>";
				return $table;
				break;
			case "item_image_table" :
				/*******************************************************items images table*************************************************************/
				$table .= "<tr><th>item name</th><th>colour</th><th>Preview</th><th>Edit</th><th>Delete</th></tr>";
				$img_data = getResult("select I.id,P.name, I.path, I.colour from items P join items_images I where I.item=P.id");
				foreach ($img_data as $key => $value) {
					$path="../client/".$value['path'];
					$table .= "<tr>";
					$table .= "<td>" . $value['name'] . "</td>";
					$table .= "<td>" . $value['colour'] . "</td>";
					$table .= "<td><img src={$path} width='80' height='50'</td>";
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $value['id'] . " class='btn btn-xs btn-warning'><i class='icon-pencil' ></i></button></form></td>";
					$table .= "<td> <form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $value['id'] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";
					$table .= "</tr>";
				}

				$table .= "</tr>";
				return $table;
				break;

			case "processing_orders_table" :
				/*******************************************************processing orders table*************************************************************/

				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>Mark as shipped</th><th>Delete order</th></tr>";
				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-success'><i class='icon-ok' ></i></button></form></td>";
					$table .= "<td><form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";

				}
				$table .= "</tr>";

				return $table;
				break;

			case "posts_table" :
				/*******************************************************posts table*************************************************************/
				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>edit</th><th>delete</th><th>manage comments</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-warning'><i class='icon-pencil' ></i></button></form></td>";

					$table .= "<td><form action=" . $data['actionDelete'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-danger'><i class='icon-remove' ></i></button></form></td>";

					$table .= "<td> <form action=" . $data['actionComments'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-success'><i class='icon-ok' ></i></button></form></td>";

				}
				$table .= "</tr>";

				return $table;
				break;
			case "unread_message_table" :
				/*******************************************************contacts table*************************************************************/

				$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>Answer</th><th>Mark as read</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td><a href=mailto:{$row['email']}>answer</a></td>";
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-success'><i class='icon-ok' ></i></button></form></td>";
				}
				$table .= "</tr>";

				return $table;

				break;
			case "site_info_table" :
			$result = mysql_query($data['query']) or die(mysql_error());

				$table = "<tr>";
				$table .= giveMeColumnsName($result);
				$table .= "<th>edit</th></tr>";

				while ($row = mysql_fetch_array($result)) {
					$table .= "<tr>";
					for ($i = 0; $i < mysql_num_fields($result); $i++) {
						$field = mysql_fetch_field($result);
						$table .= "<td>" . $row[$i] . "</td>";
					}
					$table .= "<td> <form action=" . $data['actionEdit'] . " method='post'><button name='id' value=" . $row[0] . " class='btn btn-xs btn-warning'><i class='icon-pencil' ></i></button></form></td>";

				}
				$table .= "</tr>";

				return $table;

				break;
		}

	}

}
?>
