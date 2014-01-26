<?php
// Values received via ajax
/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['title'])){
	header('Location:admin.php');
}


$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$url = $_POST['url'];
// connection to the database
try {
$bdd = new PDO('mysql:host=localhost;dbname=progettotdw', 'root', 'root');
} catch(Exception $e) {
exit('Unable to connect to database.');
}
// insert the records
$sql = "INSERT INTO admin_events (title, start, end) VALUES (:title, :start, :end)";
$q = $bdd->prepare($sql);
$q->execute(array(':title'=>$title, ':start'=>$start, ':end'=>$end));
?>