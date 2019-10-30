/**
 *@author Masondo
 */
$(document).on('click', '#findbtn', function() {
	if($("#itemname").val().length < 1){
		alert("Please enter the name of the item you have lost");
	}else{
		if($("#itemdesc").val().length < 1){
			alert("Please provide a description of the item you have lost");
		}else{
			if($("#location").val().length < 1){
				alert("Please enter where you lost think you might have lost the item");
			}else{

				$.post("itemmatch.php", {
					itemname: $("#itemname").val(),
					itemdesc: $("#itemdescription").val(),
					location: $("location").val()
				},
				function(data, status){
			    	if(data == "invalid"){
			    		alert("No items found");
    				}
			    	else{
    					if(data == "success"){
    						window.location.replace("itemmatch.php");
    					}else{
    						if(data == "error"){
    							alert("Server error");
    						}else{
    							alert(data);
    						}
    					}
    				}
  				});
			}
		}
	}
});
