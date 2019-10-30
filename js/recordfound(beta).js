/**
 * @author Masondo
 */
	var step = 1;

    var formData = null;
	
	//form elements
	var itemImageFile = null;
	
	
	
	$(document).on('click', '#btnproceed', function(){
		if(step == 1){
			var fileInput = document.getElementById('itemimage');
			itemImageFile = fileInput.files[0];
			formData = new FormData();
			formData.append('itemImage', itemImageFile);
			step = step + 1;	
			/* next form */
			$('#stepmessage').text("Step 2: What is the item e.g. student card");
			$('#step').html("<input");
		}else{
			if(step == 2){
				step = step + 1;
			}else{
				if(step == 3){
					
					step = step + 1;
				}else{
					if(step == 4){
						
						step = step + 1;
					}else{
						if(step == 5){
							
							step = step + 1;
						}
					}
				}
			}
		}
		//$('#stepmessage').text("Step 2");
	});
