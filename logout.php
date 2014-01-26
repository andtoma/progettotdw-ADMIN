<?

require "include/template2.inc.php";
require "include/dbms.inc.php";

session_start();
unset($_SESSION['admin']);


/*a seconda che stia facendo il logout dal sito o dalla pagina di admin lo portiamo ad un certa pagina e/o facciamo comprarire una certa stritta*/
switch($_GET['user']) {
	/*users logout from admin panel*/
	case 1 :
		header('Location:admin.php');
		break;
		
	/*users logout from site*/
	case 2 :
		header('Location:index.php');
		break;
}
?>
