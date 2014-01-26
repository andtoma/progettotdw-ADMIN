<?
require "include/dbms.inc.php";

switch($_POST['type']) {

	case "colour_Notin" :
		$data = getResult("select name from colours");
		if (!$data) {
			$colours = "<option value='' disabled selected>No color available</option>";
		} else {
			$colours = "<option value='' disabled selected>Select a colour</option>";
			foreach ($data as $key => $value) {
				$colours .= "<option  >" . $value['name'] . "</option>";
			}
		}
		echo $colours;

		break;
		
	case "colour_in" :
		$data = getResult("select colour from availability where item = '{$_POST['item']}'");
		if (!$data) {
			$colours = "<option value='' disabled selected>No colours available</option>";
		} else {
			$colours = "<option value='' disabled selected>Select a colour</option>";
			foreach ($data as $key => $value) {
				$colours .= "<option  >" . $value['colour'] . "</option>";
			}
		}
		echo $colours;

		break;

	case "sizeNotIn" :
		$data = getResult("select size from size_chart  where size not in(select size from availability where 
							item = '{$_POST['item']}'  and colour = '{$_POST['colour']}')");
		$size = "<option value='' disabled selected>Select a size</option>";
		foreach ($data as $key => $value) {
			$size .= "<option>" . $value['size'] . "</option>";
		}
		echo $size;
		break;
		
	case "sizeIn" :
		$data = getResult("select size  from availability where  item = '{$_POST['item']}'  and colour = '{$_POST['colour']}'");
		$size = "<option value='' disabled selected>Select a size</option>";

		foreach ($data as $key => $value) {
			$size .= "<option>" . $value['size'] . "</option>";
		}
		echo $size;
		break;
	case "quantity" :
		$data = mysql_query("select quantity  from availability where  item = '{$_POST['item']}'  and size='{$_POST['size']}' and colour = '{$_POST['colour']}'") or die(mysql_query);
		if (mysql_num_rows($data) == 0) {
			echo 0;
		} else {
			$val = mysql_fetch_assoc($data);
			echo $val['quantity'];
		}
		break;
}
?>