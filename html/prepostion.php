<?php /* if(!empty($result['prepositions'])){?>
<div class="product"><?php echo $result['title'];?></div>
<div class="row form-content">
<form role="form" id="prepostionFrm" method="POST" onSubmit="return prepositionSubmit();" >
<input type="hidden" name="action" value="setprepostion" >
<input type="hidden" name="sid" value="<?php echo $sid ;?>" >
<input type="hidden" name="qusid" value="<?php echo $qusid ;?>" >
<?php	foreach($result['prepositions'] as $indx => $pre){ ?>
<div class="radio ">
		<label><input type="checkbox" name="prepostions[<?php echo $pre['vs_fu_actions_id']; ?>]" id="prepostions_<?php echo $pre['vs_fu_actions_id']; ?>" value="<?php echo $pre['vs_fu_actions_id']; ?>" />&nbsp; <?php echo $pre['label']; ?></label>
</div>
<?php } ?>
<div class="form-group form-submit-backcolor"><input type = "submit" id = "prpostion_submit" value = "Valider" /></div>
<div class="form-group tpadding">
    <div class="comment-label">Commentaires:</div>
    <textarea class="form-control" name="comments" id="comments"></textarea>
</div>

</form>
</div>
<?php } */?>
<?php  if(!empty($formData)){?>

	<div class = "product" ><?php echo $formData['form_title']; ?>
         <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>{<?php echo $formData['vs_fu_actions_id']; ?>, 0}<?php } ?> 
        
        
        </div>
		<div class="row form-content">
			<form role="form" id = "questionFrm" method = "POST" >
            <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>
			 <input type = "hidden" name = "debug" value = "1" />
			 <?php } ?>
			<input type = "hidden" name = "vs_form_id" value = "<?php echo $formData['vs_form_id']; ?>" />
           	<input type = "hidden" name = "action" value = "survey-submit" />
				
		       <?php if(!empty($formData['formfields'])){ ?>
				<div class="col-md-6 form-content"> 
				<?php foreach($formData['formfields'] as $indx => $field){ ?>
                    <?php if(!empty($field['options'])){ ?>	
					<input type = "hidden" name = "vs_form_set_id_<?php echo $indx+1;?>" value = "<?php echo $field['vs_form_set_id']; ?>" />		    
				    <input type = "hidden" name = "fldcount" value = "<?php echo $indx+1;?>" />	
					<div class="form-group form-backcolor">	
                       <?php if($field['displaytype'] == 'select-one'){ ?>						
						<p><?php //echo $field['fldTitle']; ?></p>						
						<?php foreach($field['options'] as $idx => $option){ ?>						
											
							<div class="radio ">
								<label ><input type="radio" name="answer_<?php echo $indx+1;?>" <?php echo $idx === 0 ? 'checked' : ''; ?> value = "<?php echo $option['vs_fu_actions_id']; ?>" ><?php echo $option['label']; ?>
                                <input type = "hidden" name = "vs_fu_surveyactions_id_<?php echo $option['vs_fu_actions_id'];?>" value = "<?php echo $option['vs_fu_surveyactions_id']; ?>" />                                
                                <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>
                                   {<?php echo $option['vs_fu_actions_id']; ?>, <?php echo $option['vs_fu_surveyactions_id']; ?>}
                                <?php } ?>                                
                                </label>
							</div>
						<?php }} ?>
					</div>
				   <?php } ?>
				   <?php if($field['displaytype'] == 'button'){ ?> 				   
				     <div class="form-group form-submit-backcolor"><input type = "submit" id = "question_submit" value = "<?php echo $field['label']; ?>" /><?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>{<?php echo $field['vs_fu_actions_id']; ?>, 0}<?php } ?> </div>
				   <?php } ?>
				   
				   
				 <?php  } ?>
				 
                 </div>
                 
                 <?php foreach($formData['formfields'] as $indx => $field){ ?>
                 <?php if($field['displaytype'] == 'comment'){ ?> 
                 <div class="col-md-6">
                     <div class="form-group tpadding">
                        <div class = "comment-label"><?php echo $field['label']; ?> <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>{<?php echo $field['vs_fu_actions_id']; ?>, 0}<?php } ?> :</div>
                        <textarea   class = "form-control"  name = "comments" ></textarea>
                     </div>
			     </div>
				 
				<?php }} ?>	
                
                <?php } ?>				
					
			
			
			</form>
		</div><!--- row -->
        <script type="text/javascript">
		
		
     function getPrepositions(formid ,vs_fu_surveyactions_id, debug){
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: {action : 'survey-getprepositions', formid : formid , vs_fu_surveyactions_id : vs_fu_surveyactions_id, debug: debug},
			beforeSend: function(){
				
			},
			success: function(response){
				//console.log(response.prepostions);
				
				if (response.prepostions.length>5){
					$('.page-border').html('<div class="alert alert-success">(Cocher les cases qui vous correspondent)</div>' );
					$('div.page-border').css({'border':'none'});
						
				
					$('.page-border').append('<div class="prpostionval">'+response.prepostions+'</div>');
				}else{
					$('.page-border').html('<div class="alert alert-success">Je vous remercie de votre opinion.</div>' );
					$('div.page-border').css({'border':'none'});
				}
				//$('.page-border').append('<div class="prpostionval">'+response.prepostions+'</div>');
				//$('.page-border').html('<div class="alert alert-success">Je vous remercie de votre opinion.</div>' + );
				//$('div.page-border').css({'border':'none'});
				
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
			}
		});
	 }
$(document).ready(function(){
	$("#questionFrm").on("submit", function(){
		<?php if(!empty($_GET['preview'])){ ?>
			return false;
		<?php } ?>

		//Code: Action (like ajax...)
		$.ajax({
			url: "index.php",
			type: "post",
			cache: false,
			data: $('#questionFrm').serialize(),
			beforeSend: function(){
				
			},
			success: function(response){
				
				//console.log(response);
			    var data = $.parseJSON(response)
				$('.page-border').html('<div class="alert alert-success">Je vous remercie de votre opinion.</div>' );
				$('div.page-border').css({'border':'none'});
				//window.location = "?a=s&sid="+response.sid+"&Qusid="+response.Qusid;
				if(data.r_type_code=='form'){
				  getPrepositions(data.r_value, data.vs_fu_surveyactions_id, data.debug);
				}
				
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
				//alert(response);
				// window.location = "html/prepostion.php?id"+response;
			}
		});
		return false;
	 });
	 
});
	 
function prepositionSubmit(){
	
		if($('input[type=checkbox]:checked').length == 0)
		{
			alert('Please select atleast one checkbox');
			return false;
		}
		
		//var surveyData = $('#prepostionFrm').serializeArray();
		
		var str=$('#prepostionFrm input:not([type="checkbox"])').serialize();
		var str1=$("#prepostionFrm input[type='checkbox']:checked").map(function(){return this.name+"="+this.value;}).get().join("&");
		if(str1!="" && str!="") str+="&"+str1;
		else str+=str1; 
		
		var txtCmt = $('#comments').val();
	   	
		var surveyData = str + '&comments=' + txtCmt;

		$.ajax({
			url: "index.php",
			type: "GET",
			cache: false,
			data: surveyData,
			beforeSend: function(){
				
			},
			success: function(response){
				
				console.log(response);
			    var data = $.parseJSON(response)
				
				//alert(data.qusid);
				$('.page-border').html('<div class="alert alert-success">Je vous remercie de votre opinion.</div>' );
				$('div.page-border').css({'border':'none'});
				//window.location = "?a=s&sid="+response.sid+"&Qusid="+response.Qusid;
				//getPrepositions(data.qusid,data.sid);
				
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
				//alert(response);
				// window.location = "html/prepostion.php?id"+response;
			}
		});
		return false;
}
	 
	</script>

<?php } ?>