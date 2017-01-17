		<div id="emailModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<!-- Modal content-->
			<form method = "post" id = "sendMailFrm" >
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title modal-label"	>Send Mail</h4>
			  </div><!-- modal header -->
			  <div class="modal-body">
  					<div class="alert alert-success" style = "display:none;">
					  <strong>Mail envoyé</strong>
					</div>
					<div class = "error"></div>
						<input type = "hidden" name = "action" value = "send-mail" >
						<input type = "hidden" name = "video_id" value = "<?php echo $video_id; ?>" >
						<input type = "text" class = "form-control" id = "email" name = "email" placeholder = "Recipient Email" data-validation="email" readonly="readonly" >
                        
                        <?php 
                        
                        if(!empty($templateList) && is_array($templateList)){
                        
	                        $outstring  = "
	                        
	                        <script>
	                        	function changetp(){
	                        		var x = document.getElementById('mail_template_select');
	                        		var msg = document.getElementById('message');
	                        		var tpbody = document.getElementById('tp_body_'+x.value);
	                        		var sender_name = document.getElementById('sender2').value;
	                        		var name = document.getElementById('receiver2').value;
	                        		var unLink = document.getElementById('ulink2').value;
	                        		
	                        		
	                        		var message =	tpbody.value.replace(/\{sender_name\}/g, sender_name); 
									message =	message.replace(/\{name\}/g, name); 
		 							message =	message.replace(/\{unique_link\}/g, unLink); 
		 							msg.value = message;	
		 							updatehref();                        		
	                        			                        	
	                       		}
	                        	
	                        </script>
	                       	                        
	                        ";
	                        
	                        $os_dd = '<select name="mail_template" class = "form-control" id="mail_template_select" onChange="changetp();">';
	                        $os_tp_body_str="";
	                        $os_tp_subject_str="";
	                        foreach ($templateList as $tmpl) {
	                        	$os_dd .= '<option value="' .  $tmpl['vs_templates_id'] .'">' . $tmpl['name'] . '</option>'; 
	                        
	                        	$os_tp_body_str .=  '<input type = "hidden" value = "' . $tmpl['body'] . '" id = "tp_body_' .  $tmpl['vs_templates_id'] .'" >';
	                        	$os_tp_subject_str .=  '<input type = "hidden" value = "' . $tmpl['subject'] . '" id = "tp_subject_' .  $tmpl['vs_templates_id'] .'" >';
	                        	                      	
	                        }
	                        $os_dd .= '</select>';
	                        
	                        $outstring .= $os_tp_body_str;
	                        $outstring .= $os_tp_subject_str;
	                                             
	                        $outstring .= "
	                       
	                        ";
	                              
	                        $outstring .= $os_dd;                   
	                        echo $outstring;
                        }                        
                        
                         ?>
                        
						
						<input type = "text" class = "form-control" id = "subject" name = "subject" value = "<?php echo  EMAIL_SUBJECT; ?>" placeholder = "Mail Subject" data-validation="required" >
						<textarea style = "height:120px;" name = "message" id= "message" class = "form-control"   placeholder = "Message" data-validation="required"><?php echo EMAIL_MESSAGE; ?></textarea>
						<input type = "hidden" value = "<?php echo EMAIL_MESSAGE; ?>" id = "defaultMessage" >
						<input type="hidden" value = "<?php echo $_SESSION['users']['displayname']; ?>" id = "sender2" >
						<input type="hidden" value = "" id = "receiver2" >
						<input type="hidden" value = "" id = "ulink2" >
			  </div>
			  <div class="modal-footer">
				<div id = "m-link"></div>
                        <?php if($_SESSION['users']['user_type'] == 'admin') { ?>
				<button type="submit" class="btn btn-default"  id = "sendMail" >Send via Server</button>
                <?php } ?>
				&nbsp;<a href = "#" id = "sendViaPhone" class="btn btn-default"  style = "display:none;">Send via Phone</a>
			  </div>
			</div><!-- modal content -->
			</form>
		  </div>
		</div>
		