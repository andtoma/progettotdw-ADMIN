<?php

require_once "include/dbms.inc.php";

Class table_filler extends taglibrary {

	function firstfunction() {
		return;
	}

	function filler($name, $data, $pars) {

		switch($pars['value']) {
			case 'item' :
				/************************************************************/

				$q = mysql_query("select * from items I where I.id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);

				switch($pars['field']) {
					case "sex" :
						if ($pars['gender'] == $row['sex'])
							return "checked";
						break;
					default :
						return $row[$pars['field']];
						break;
				}
				break;

			case 'furnisher' :
				/************************************************************/

				$q = mysql_query("select * from furnishers where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;

			case 'user' :
				/************************************************************/

				$q = mysql_query("select * from users where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				switch($pars['field']) {
					case "sex" :
						if ($pars['gender'] == $row['sex'])
							return "checked";
						break;
					default :
						return $row[$pars['field']];
						break;
				}
				break;
			case 'post' :
				/************************************************************/
				$q = mysql_query("select * from posts where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'group' :
				/************************************************************/
				$q = mysql_query("select * from groups where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'newsletter' :
				/************************************************************/
				$q = mysql_query("select * from newsletter where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'site_infos' :
				/************************************************************/
				$q = mysql_query("select * from site_infos where info_type = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'comment' :
				/************************************************************/
				$q = mysql_query("select * from comments where id = '{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'slideshow' :
				/************************************************************/
				$q = mysql_query("select * from slideshow where id ='{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				if ($pars['field'] == 'path') {
					return getcwd() . $row[$pars['field']];
				} else {
					return $row[$pars['field']];
				}
				break;
			case 'items_images' :
				/************************************************************/
				$q = mysql_query("select * from items_images where id ='{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
			case 'menu' :
				/************************************************************/
				$q = mysql_query("select * from menu where id ='{$data}'") or die(mysql_error());
				$row = mysql_fetch_array($q);
				return $row[$pars['field']];
				break;
		}

	}
	
}
?>
