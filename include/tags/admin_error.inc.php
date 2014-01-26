<?

Class admin_error extends taglibrary {

	function firstfunction() {
		return;
	}

	function print_error($name, $data, $pars) {
		switch($data) {
			case 0 :
				return;
				break;
			case 1 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 Wrong Email or Password! Please try again!
			 </fieldset>";
				break;
			case 2 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 You are not allowed to enter!
			 </fieldset>";
				break;
			case 3 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 You are not allowed to enter!
			 </fieldset>";
				break;
			case 4 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 Error: A problem occurred during file upload!
			 </fieldset>";
				break;
			case 5 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 Error: File " . $_FILES["uploaded_file"]["name"] . " already exists
			 </fieldset>";
				break;
			case 6 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 Error: Only .jpg images under 999Kb are accepted for upload
			 </fieldset>";
				break;
			case 7 :
				return " <fieldset>
			 <i class='icon-exclamation-sign'></i>
			 Error: No file uploaded
			 </fieldset>";
				break;
		}
	}

}
?>