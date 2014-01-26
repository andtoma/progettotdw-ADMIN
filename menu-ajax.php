<?
require_once "include/dbms.inc.php";

/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['type'])){
	header('Location:admin.php');
}


switch($_POST['type']) {
	case 'all' :
		$data = getResult("select id, name from menu where parent_id='{$_POST['id']}' order by position asc");
		if (!$data) {
			$items = "<option value='' disabled selected>This item has no children</option>";
		} else {
			$items = "<option value='' disabled selected>Select a item</option>";
			foreach ($data as $key => $value) {
				$items .= "<option value=" . $value['id'] . " >" . $value['name'] . "</option>";
			}
		}
			echo $items;
		
		break;

	case 'notall' :
		$data = getResult("select id, name from menu where parent_id='{$_POST['id']}' and id <> '{$_POST['selected']}' order by position asc");
		$items = "<option value='' disabled selected>Select a item</option>";
		foreach ($data as $key => $value) {
			$items .= "<option value=" . $value['id'] . " >" . $value['name'] . "</option>";
		}
		echo $items;
		break;
}
?>