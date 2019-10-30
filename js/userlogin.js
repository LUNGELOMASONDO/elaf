/**
 *
 * @author Masondo
 *
 */
$(document).on('focusin', 'input', function() {
	$(this).select();
});

$(document).on('focusin', '#password', function() {
	$('#errormessage').text("");
});

$(document).on('focusin', '#studentnumber', function() {
	$('#errormessage').text("");
});

$(document).on('click', '#btnlogin', function(){
	$.post("user-login.php", {
		studentnumber: $("#studentnumber").val(),
		password: $("#password").val()
	},
	function(data, status){
		
    	if(data == "invalid"){
    		$('#errormessage').text("Incorrect username or password");
    	}
    	else{
    		if(data == "success"){
    			window.location.replace("useracc.php");
    		}else{
    			if(data == "error"){
    				$('#errormessage').text("Server error");
    			}else{
    				$('#errormessage').text("Unkown server response");
    			}
    		}
    	}
  	});
});