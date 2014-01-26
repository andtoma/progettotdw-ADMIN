<?
require "include/dbms.inc.php";

/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['type'])){
	header('Location:admin.php');
}

switch($_POST['type']) {
	case 'remove' :
		$data = getResult("select id, name from groups where id in(select grp from users_groups where user='{$_POST['user']}')");
		break;

	case 'add' :
		$data = getResult("select id, name from groups where id not in(select grp from users_groups where user='{$_POST['user']}')");
		break;
}
$group = "<option value='' disabled selected>Select a group</option>";
foreach ($data as $key => $value) {
	$group .= "<option value=" .$value['id']. " >" . $value['name'] . "</option>";
}
echo $group;
?>