<?
require "include/dbms.inc.php";

/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['type'])){
	header('Location:admin.php');
}

switch($_POST['type']) {
	case 'remove' :
		$data = getResult("select id, name from services where id   in(select service from services_groups where grp='{$_POST['group']}')");
		
		break;

	case 'add' :
		$data = getResult("select id, name from services where id not in(select service from services_groups where grp='{$_POST['group']}')");		
		break;
}
$service = "<option value='' disabled selected>Select a service</option>";

foreach ($data as $key => $value) {
	$service .= "<option value=" . $value['id'] . " >" . $value['name'] . "</option>";
}
echo $service;
?>