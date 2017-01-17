<?php include_once($dir.'html/header.php'); ?>
    <div class="container">
		<?php if(!empty($_SESSION['msg'])) { ?>
		<div class="alert alert-success">
		<?php echo $_SESSION['msg']; ?>
		</div>
		<?php } unset($_SESSION['msg']); ?>
      <!-- Main component for a primary marketing message or call to action -->
	  	<div class="col-lg-12">
			<div class = "center-me"><?php echo $originalVideoID[1]; ?></div>
		</div>
		<div class="col-lg-12">
				<h4 class="page-header">
					Reporting - <a href = "#" data-toggle="modal" data-target="#myModal" onclick = "$('.alert-success').hide();">Ajout Destinataires</a> | <a href = "http://www.youtube.com/watch?v=<?php echo $originalVideoID[0]; ?>" target = "_blank">http://www.youtube.com/watch?v=<?php echo $originalVideoID[0]; ?></a>
				</h4>
				<br />
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<!-- <th>S.No.</th> -->
							<th>Envoyer</th>
							<th>Nom</th>
							<th>Email or Phone</th>
							<th>Lien Cliqué</th>
							<th>Video Vue</th>
							<th>Réponse</th>
							<th>Commentaire</th>
							<th>IP</th>
							<th>Actioned</th>

							<th>Lien</th>
						</tr>
					</thead>
					<tbody>
						<?php $sno = 1 ;
						foreach ($result as $row) {  ?>
							<tr>
								<!-- <td><?php echo $sno;?></td> -->
								<td>
								<?php //if($device == 'desktop'){ ?>
								<?php if(1){ ?>
									<a href = "#" data-toggle="modal" data-target="#emailModal" data-senderName = "<?php echo $row['sender_name'];?>" data-emph = "<?php echo $row['email_phone'];?>"  data-name = "<?php echo $row['name']; ?>" data-slink = "<?php echo $site_url; ?><?php echo $row['unique_link'];?>" class = "emailPopup">Send</a>
								<?php }else{ ?>
										<?php	
									
											$row['name'] = empty($row['name']) ? '' : $row['name'];
											$message =	str_replace("{name}", $row['name'],EMAIL_MESSAGE); 
											$message =	str_replace('{unique_link}', $site_url . "{$row['unique_link']}",$message);
											//echo $message;
											//%0A - line feed
										?>
									<a href = "#" data-toggle="modal" data-target="#emailModal" data-senderName = "<?php echo $row['sender_name'];?>" data-emph = "<?php echo $row['email_phone'];?>"  data-name = "<?php echo $row['name']; ?>" data-slink = "<?php echo $site_url; ?><?php echo $row['unique_link'];?>" class = "emailPopup">Send via Server</a> | 
									<?php if(filter_var($row['email_phone'], FILTER_VALIDATE_EMAIL)){  //mobile email link ?>
										<a href="mailto:<?php echo $row['email_phone'];?>?subject=<?php echo  EMAIL_SUBJECT; ?>&body=<?php echo urlencode($message); ?>">via Phone</a>
									<?php }else{ ?>
										<?php if($device == 'android'){ ?>
											<a href="sms://+<?php echo $row['email_phone'];?>&body=<?php echo $message; ?>">via Phone</a>
										<?php }else{ ?>
											<a href="sms:+<?php echo $row['email_phone'];?>&body=<?php echo $message; ?>">via Phone</a>
										<?php } ?>
									<?php } ?>
								<?php } ?>
								</td>								
								<td><?php echo $row['name'];?></td>
								<td><?php echo $row['email_phone'];?></td>
								<td><?php echo $row['viewed'] ? $row['viewed']  : 'NA' ;?></td>
								<td><?php echo $row['video_started'] ? $row['video_started'] : 'NA'; ?></td>
								<td><b>
								
								<?php echo $row['answer']? $row['answer'] : 'NA' ; ?></b>
                                <br />
                                <?php echo $row['preposition_answer']? $row['preposition_answer'] : '';?>
                                
                                </td>
								<td>
								<?php echo $row['comments']? $row['comments'] : 'NA';?>
                                
								<?php echo $row['preposition_comments']? '<hr />'.$row['preposition_comments'] : '';?>
                                </td>
								<td><?php echo empty($row['ip_address']) ? 'NA' : $row['ip_address']; ?></td>
								<td><?php echo empty($row['added_on']) ?  'NA' : $row['added_on']; ?></td>

								<td><a href = "<?php echo $site_url; ?>?a=s&sid=<?php echo $row['unique_link'];?>&preview=1" target = "_blank"><?php echo $row['unique_link'];?></td>
							</tr>
						<?php $sno++; }  ?>
					</tbody>
				</table>
			</div>
		</div><!--- row -->
		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<!-- Modal content-->
			<form method = "post" id = "addRecepientsFrm" >
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title modal-label"	>Saisir email or ou numero de tel and Nom (optionel). Un par ligne.</h4>
			  </div><!-- modal header -->
			  <div class="modal-body">
  					<div class="alert alert-success" style = "display:none;">
					  <strong>Succes!</strong> Destinataires mis a jour.
					</div>
					<div class = "error"></div>
						<input type = "hidden" name = "action" value = "add-recepients" >
						<input type = "hidden" name = "video_id" value = "<?php echo $video_id; ?>" >
						<!-- <textarea name = "recepients" id= "recepients" class = "form-control"  style = "height:130px;" data-validation ="required"><?php //while($recRow = mysql_fetch_assoc($recepientsDataResult)){ ?><?php //echo $recRow['email_phone'].','. $recRow['name']. "\n"; ?><?php //} ?></textarea>-->
						<textarea name = "recepients" id= "recepients" class = "form-control"  style = "height:130px;" data-validation ="required"></textarea>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="submit" class="btn btn-default"  id = "addRecepients" >Entrer</button>
			  </div>
			</div><!-- modal content -->
			</form>

		  </div>
		</div>
		<!-- END Modal -->


		<!-- Start Email Modal -->
			<?php include_once($dir.'html/send-mail-popup.php'); ?>
		<!-- END Email Modal -->

	
	</div> <!-- /container -->
	<?php include_once($dir.'html/footer.php'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
	<script>
  	$.validate({
        validateOnBlur: true,
        showHelpOnFocus: false,
        addSuggestions: false,
        borderColorOnError: '',
        inputParentClassOnSuccess: false
    });
	
    </script>
	<script>
	function updatehref(){// I am not happy about this php back and forth
				<?php if($device == 'android'){ ?>

					var message = $(".modal-body #message").val();
					var message = message.replace(/&/gi, "-"); 
					var mailTo = 'sms:' + $(".modal-body #email").val() + '?body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php }else{ ?>
					var mailTo = 'sms:' + $(".modal-body #email").val() + '&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php } ?>		
		
			return true;
	}
	
	
	$('#message').on('input selectionchange propertychange',function(){
		     var defaultMessage = $('#defaultMessage').val();
			 var sLink = "<?php echo $site_url.'' ; ?>";
			 var message = $(".modal-body #message").val();
			 
			 var senderName = $('#sender2').val();
			 
			
			 console.log(message);
			 $(".modal-body #message").val(message);
			 //$(".modal-body #email").val(response.userData.email_phone);
			
			if(isEmail($(".modal-body #email").val())){
					var mailTo = 'mailto:' + $(".modal-body #email").val() + '?subject=<?php echo  EMAIL_SUBJECT; ?>&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
			}else{
				// what in the world is all this going back to php for?
				<?php if($device == 'android'){ ?>

					var message = message.replace(/&/gi, "-"); 
					var mailTo = 'sms:' + $(".modal-body #email").val() + '?body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php }else{ ?>
					var mailTo = 'sms:' + $(".modal-body #email").val() + '&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php } ?>
			}
		
	});
	
	

	$(document).on("click", ".emailPopup", function () {
		 $('.alert-success').hide();
		 
		 //var defaultMessage = $('#defaultMessage').val();
		 var name = $(this).data('name');
		 //console.log(name);
		 //alert($(this).data('slink'));
		 var emailPhone = $(this).data('emph');
		 var unLink = $(this).data('slink');
		 //var senderName = $(this).data('senderName'); why this did not work
		 var senderName = $('#sender2').val();
		 
		 
		 //var message =  $(".modal-body #message").val();
		 		 
		 //message =	message.replace(/\{sender_name\}/g, senderName); 
		 //message =	message.replace(/\{name\}/g, name); 
		 //message =	message.replace(/\{unique_link\}/g, unLink); 
		 //console.log(message);
		 		 
		 //$(".modal-body #message").val(message);
		 
		 
		 $(".modal-body #email").val(emailPhone);
		 
		 $(".modal-body #receiver2").val(name);
		 $(".modal-body #ulink2").val(unLink);
		 
		 changetp();

			if(isEmail($(".modal-body #email").val())){
					var mailTo = 'mailto:' + emailPhone + '?subject=<?php echo  EMAIL_SUBJECT; ?>&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
			}else{
				<?php if($device == 'android'){ ?>
					 var message = message.replace(/&/gi, "-"); 
					var mailTo = 'sms:' + emailPhone + '?body=' + encodeURIComponent(message);
					$('#sendViaPhone').attr('href',mailTo);
					//$('#m-link').html(mailTo);
				<?php }else{ ?>
					var mailTo = 'sms:' + emailPhone + '&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php } ?>
			}
		  $('#emailModal').modal('show');
	});



	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	$('#sendMail').click(function(evt){
		evt.preventDefault();
		if($.trim($('#email').val()) == '' ||  !isEmail($.trim($('#email').val())) ){
			//alert('ss');
			('#email').css({'border' : '1px solid red;'});
		}

		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: $('#sendMailFrm').serialize(),
			beforeSend: function(){
				$('#sendMail').html('Plesae wait..');
			},
			success: function(response){
				$('.alert-success').show('fast');
				//location.reload();
				$('#sendMail').html('Send via Server');
				console.log(response.msg);
				$('#emailModal').modal('hide');
				return false;
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
			}
		});
			
	});

	$('#addRecepients').click(function(evt){
		evt.preventDefault();
		if($.trim($('#recepients').val()) == ''){
			//alert('ss');
			('#recepients').css({'border' : '1px solid red;'});
		}
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: $('#addRecepientsFrm').serialize(),
			beforeSend: function(){
				
			},
			success: function(response){
				$('.alert-success').show('fast');
				location.reload();
				console.log(response.msg);

			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
			}
		});
	});

	
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	
	
	

	</script>
		<?php if($device != 'desktop') { ?><script> $('#sendViaPhone').show();</script><?php } ?>

	</body></html>
