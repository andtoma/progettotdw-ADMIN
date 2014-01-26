<?php

/*this script is called by ajax, if the admin or someone else comes here typing the url, he will be redirected */
if(!isset($_POST['id'])){
	header('Location:admin.php');
}

/* Values received via ajax */
$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

// connection to the database
try {
 $bdd = new PDO('mysql:host=localhost;dbname=progettotdw', 'root', 'root');
 } catch(Exception $e) {
exit('Unable to connect to database.');
}
 // update the records
$sql = "UPDATE admin_events SET title=?, start=?, end=? WHERE id=?";
$q = $bdd->prepare($sql);
$q->execute(array($title,$start,$end,$id));
?>