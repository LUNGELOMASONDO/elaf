/**
 * @author Masondo
 */
$(document).on('focusin', 'input', function() {
	$(this).select();
});

$(document).on('focusin', '#password', function() {
	$('#errormessage').text("");
});

$(document).on('focusin', '#securitynumber', function() {
	$('#errormessage').text("");
});

$(document).on('click', '#btnlogin', function(){
	var securitynumber = $("#securitynumber").val();
	var password = $("#password").val();
	$.post("security-login.php", {
		securitynumber: securitynumber,
		password: password
	},
	function(data, status){
    	if(data == "invalid"){
    		$('#errormessage').text("Incorrect username or password");
    	}
    	else{
    		if(data == "success"){
    			window.location.replace("securityaccount.php");
    		}else{
    			if(data == "error"){
    				$('#errormessage').text("Server error");
    			}
    			else{
    				$('#errormessage').text("Unkown server response");
    			}
    		}
    	}

  	});
});
