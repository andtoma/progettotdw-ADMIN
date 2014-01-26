$(document).ready(function() {

	$("#group_add").change(function() {

		$.ajax({
			url : "get-permissions_ajax.php",
			type : "post",
			data : {
				group : $(this).val(),
				type : "add"
			},
			success : function(responce) {
				$("#permissions").html(responce);
			}
		});
	});

	$("#group_remove").change(function() {

		$.ajax({
			url : "get-permissions_ajax.php",
			type : "post",
			data : {
				group : $(this).val(),
				type : "remove"
			},
			success : function(responce) {
				$("#permissions").html(responce);
			}
		});
	});

	$("#user_add").change(function() {

		$.ajax({
			url : "get-groups_ajax.php",
			type : "post",
			data : {
				user : $(this).val(),
				type : "add"
			},
			success : function(responce) {
				$("#groups").html(responce);
			}
		});
	});

	$("#user_remove").change(function() {
		$.ajax({
			url : "get-groups_ajax.php",
			type : "post",
			data : {
				user : $(this).val(),
				type : "remove"
			},
			success : function(responce) {
				$("#groups").html(responce);
			}
		});
	});



	$("#itemAdd").change(function() {
		
		$.ajax({
			url : "availability_ajax.php",
			type : "post",
			data : {
				item : $("#itemAdd").val(),
				type : "colour_Notin"
			},
			success : function(responce) {
				$("#colourAdd").html(responce);
			}
		});
	});

	$("#colourAdd").change(function() {
		
		$.ajax({
			url : "availability_ajax.php",
			type : "post",
			data : {
				item : $("#itemAdd").val(),
				colour : $(this).val(),
				type : "sizeNotIn"
			},
			success : function(responce) {
				$("#size").html(responce);
			}
		});
	});
	
	$("#itemEdit").change(function() {
		
		$.ajax({
			url : "availability_ajax.php",
			type : "post",
			data : {
				item : $("#itemEdit").val(),
				type : "colour_in"
			},
			success : function(responce) {
				$("#colourEdit").html(responce);
			}
		});
	});

	$("#colourEdit").change(function() {
		
		$.ajax({
			url : "availability_ajax.php",
			type : "post",
			data : {
				item : $("#itemEdit").val(),
				colour : $(this).val(),
				type : "sizeIn"
			},
			success : function(responce) {
				$("#size").html(responce); 
			}
		});
	});
	
	
	$("#size").change(function() {
		
		$.ajax({
			url : "availability_ajax.php",
			type : "post",
			data : {
				item: $("#itemEdit").val(),
				colour:$("#colourEdit").val(),
				size:$("#size").val(),
				type : "quantity"
			},
			success : function(responce) {
				$("#quantity").val(responce);
			}
		});
	});
	
	$("#parent").change(function() {
		
		$.ajax({
			url : "menu-ajax.php",
			type : "post",
			data : {
				id : $("#parent").val(),
				type: 'all'
			},
			success : function(responce) {
				$("#item1").html(responce);
			}
		});
	});
	
	$("#item1").change(function() {
		
		$.ajax({
			url : "menu-ajax.php",
			type : "post",
			data : {
				id : $("#parent").val(),
				selected: $("#item1").val(),
				type: 'notall'
			},
			success : function(responce) {
				$("#item2").html(responce);
			}
		});
	});

});
