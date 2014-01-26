<?php
/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['id'])){
	header('Location:admin.php');
}

/* Values received via ajax */
$id = $_POST['id'];

// connection to the database
try {
 $bdd = new PDO('mysql:host=localhost;dbname=progettotdw', 'root', 'root');
 } catch(Exception $e) {
exit('Unable to connect to database.');
}
 
/* Delete */ 
$sql = "DELETE from admin_events WHERE id=".$id;
$q = $bdd->prepare($sql);
$q->execute();

?>