<?php
//Сheck that we have a file
function upload($path) {
	if ((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
		//Check if the file is JPEG image and it's size is less than 999Kb
		$filename = basename($_FILES['uploaded_file']['name']);
		$ext = substr($filename, strrpos($filename, '.') + 1);
		if (($ext == "jpg") && ($_FILES["uploaded_file"]["type"] == "image/jpeg") && ($_FILES["uploaded_file"]["size"] < 999999)) {
			//Determine the path to which we want to save this file
			$newname = getcwd() .'/../client/'. $path . $_FILES['uploaded_file']['name'];
			//Check if the file with the same name is already exists on the server
			if (!file_exists($newname)) {
				//Attempt to move the uploaded file to it's new place
				if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $newname))) {
					return 0;
				} else {
					/*error during upload*/
					return 4;
				}
			} else {
				/*file already exists*/
				return 5;
			}
		} else {
			/*file is not jpeg or its size is over 999kb*/
			return 6;
		}
	} else {
		/*empty file or generic error*/
		return 7;
	}
}
?>