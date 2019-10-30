/**
 * @author Masondo
 */
$(document).on('click', '#btnadd', function(){
	var form = $('#itemform')[0];
	// Create an FormData object 
    var data = new FormData(form);
    
    if(document.getElementById("itemimage").files.length == 0){
		alert("Please provide an image of the item");
	}else{
		if($("#itemname").val().length < 2){
			alert("Please enter the item name");
		}else{
			if($("#itemdesc").val().length < 3){
				alert("Please enter a description of the item");
			}else{
				if($("#location").val().length < 2)
				{
					alert("Please provide the location the item was found at");
				}else{
					$.ajax({
						type: "POST",
						enctype: 'multipart/form-data',
						url: "additem.php",
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						//timeout: 600000,
						success: function (data) {
							if(data == "success"){
								$('#errormessage').html("<b><p style='font-style:bold;color:green';>Item added :-) <a href='securityaccount.php'>View</a></p></b>");
								$('#itemname').val("");
								$('#itemdesc').val("");
								$('#location').val("");
								var el = $("#itemimage");
								el.wrap('<form>').closest('form').get(0).reset();
								el.unwrap();
							}
							else{
								if(data == "error"){
									$('#errormessage').html("<b><p style='font-style:bold;color:red';>Server error</p></b>");
								}
								else{
									$('#errormessage').html("<b><p style='font-style:bold;color:red';>Unknown server response</p></b>");
								}
							}
						},
						error: function (e) {
							alert("alert");
						}
					});	
				}
			}
		}
	}	
});

$(document).on('focus', 'input', function(){
	
});
	
$(document).on('focus', 'textarea', function(){
	
});
