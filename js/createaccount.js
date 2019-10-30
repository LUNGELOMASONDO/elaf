/**
 * @author Masondo
 */
$(document).on('click', '#signupbtn', function(){
	$.post("signup.php", {
		studentnumber: $("#sstudentnumber").val(),
		idnumber: $("#sidnumber").val(),
		name: $("#susername").val(),
		email: $("#semail").val(),
		password: $("#spassword").val()
	},
	function(data, status){
		
    	if(data == "invalid"){
    		alert("Please provide valid input everywhere requested");
    	}
    	else{
    		if(data == "success"){
    			alert("Hey " + $("#susername").val() + ", your account has been successfully created :-)");
    			$("#sstudentnumber").val("");
				$("#sidnumber").val("");
				$("#susername").val("");
				$("#semail").val("");
				$("#spassword").val("");
    		}else{
    			if(data == "error"){
    				alert("Server error");
    			}else{
    				alert("Unkown server response");
    			}
    		}
    	}
  	});
});