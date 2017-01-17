<?php include_once($dir.'html/header.php'); ?>
    <div class="container">
		<?php if(!empty($_SESSION['msg'])) { ?>
		<div class="alert alert-success">
		<?php echo $_SESSION['msg']; ?>
		</div>
		<?php } unset($_SESSION['msg']); ?>
      <!-- Main component for a primary marketing message or call to action -->
		<div class="col-lg-12">
				<h4 class="page-header">
					Envoyer une Vidéo
				</h4>
			<form class="form-inline" id = "sendVideoFrm">
			  <input type = "hidden" name = "action" value = "send-video" />
			  <div class="form-group">
					<label for="exampleInputName2">Choisir Vidéo</label>
					<select name = "video_id" id = "video_id" class="form-control" data-validation="required">
						<?php while ($row = mysql_fetch_assoc($result)) {  ?>
						<option value = "<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
						<?php }mysql_data_seek($result, 0);  ?>
					</select>
			  </div>
              
               <div class="form-group">
               
               
               <a href="#" data-toggle="modal" data-target="#listlModal" class="ListPopup" >List</a></div>
               
               
              
               
               
			  <div class="form-group">
				<label for="exampleInputEmail2">Email ou Phone</label>
				<input type="type" class="form-control"  id = "email_phone" name = "email_phone"  placeholder="Email or Phone" data-validation="required" >
			  </div>
              
              
			  <div class="form-group">
				<label for="exampleInputEmail2">Nom ou Prénom</label>
				<input type="type" class="form-control" id="fname_prospact"  name = "name"  placeholder="Name or First Name" >
			  </div>
				<button type="submit" class="btn btn-default" id = "send-video">Envoyer</button>
			</form>
		</div>

		<div class="col-lg-3">
        
        
        <?php if($_SESSION['users']['user_type'] == 'admin') { ?>
				<h4 class="page-header">
					Add Video
				</h4>
			<form role="form" id = "link" method = "POST" >
				<div class="form-group">
					<label> &nbsp;Survey Name</label>
					<input name = "name"  id= "name" class="form-control" data-validation="required">
					<label>&nbsp;Video ID ( Youtube )</label>
					<input name = "video_id"  id= "video_id" class="form-control" >
					<label> &nbsp; Questionnaire </label>
					<select name = "vs_survey_id" id = "survey_id" class="form-control" data-validation="required">
						<?php while ($row = mysql_fetch_assoc($surveyList)) {  ?>
						<option value = "<?php echo $row['vs_survey_id']; ?>"><?php echo $row['name']; ?></option>
						<?php } ?>
					</select>

					<input type = "hidden" name = "action" value = "save-link" />
				</div>
				<button type="submit" class="btn btn-default">Create</button>
			</form>
            <?php } ?>
		</div><!--- row -->
        
        
		<div class="col-lg-9">
				<h4 class="page-header">
					List Video
				</h4>
				<br />
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Nom</th>
							<th>Lien</th>
							<th>Ajouté le</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $sno = 1 ;
						while ($row = mysql_fetch_assoc($result)) {  ?>
							<tr>
								<td><a href = "index.php?action=reports&id=<?php echo $row['id']; ?>"  ><?php echo sprintf('%04d', $sno);?></td>
								<td><?php echo $row['name'];?></td>
								<td><a href = "http://www.youtube.com/watch?v=<?php echo $row['video_id']; ?>" target = "_blank" >http://www.youtube.com/watch?v=<?php echo $row['video_id']; ?></td>
								<td><?php echo $row['created_on'];?></td>
								<td><!-- <a href = "index.php?action=manage_questions&id=<?php echo $row['id']; ?>&quiz_id=<?php echo $quiz_id; ?>">Edit</a> | --> <a href = "index.php?action=reports&id=<?php echo $row['id']; ?>">Open</a> </td>
							</tr>
						<?php $sno++; } mysql_free_result ($result); ?>
					</tbody>
				</table>
			</div>
		</div><!--- row -->
    </div> <!-- /container -->
	<!-- Start Email Modal -->
		<?php include_once($dir.'html/send-mail-popup.php'); ?>
        <?php include_once($dir.'html/prospact-user-list.php'); ?>
	<!-- END Email Modal -->


	<?php include_once($dir.'html/footer.php'); ?>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
	<script>

	function isEmailAddress(str) {
	   var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	   return pattern.test(str);  // returns a boolean 
	}



    $.validate({
        validateOnBlur: true,
        showHelpOnFocus: false,
        addSuggestions: false,
        borderColorOnError: '',
        inputParentClassOnSuccess: false
    });

	$("#sendVideoFrm" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		event.preventDefault();
		$('.alert-success').hide();
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: $('#sendVideoFrm').serialize(),
			beforeSend: function(){
				$('#send-video').html('Plesae wait..');
			},
			success: function(response){
			console.log(response);
			$('#send-video').html('Send');

			 var defaultMessage = $('#defaultMessage').val();
			 var sLink = "<?php echo $site_url.'' ; ?>";
			 var message = defaultMessage;
			 
			 var senderName = $('#sender2').val();
			 
			 message =	message.replace(/\{sender_name\}/g, senderName); 
			 message =	message.replace(/\{name\}/g, response.userData.name); 
			 message =	message.replace(/\{unique_link\}/g, sLink + response.userData.unique_link); 
			 console.log(message);
			 $(".modal-body #message").val(message);
			 $(".modal-body #email").val(response.userData.email_phone);
			
			if(isEmailAddress($(".modal-body #email").val())){
					var mailTo = 'mailto:' + response.userData.email_phone + '?subject=<?php echo  EMAIL_SUBJECT; ?>&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
			}else{
				<?php if($device == 'android'){ ?>

					var message = message.replace(/&/gi, "-"); 
					var mailTo = 'sms:' + response.userData.email_phone + '?body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php }else{ ?>
					var mailTo = 'sms:' + response.userData.email_phone + '&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
				<?php } ?>
			}
			 $('#emailModal').modal('show');
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
	


	$("#sendMailFrm" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		event.preventDefault();
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
				$('#sendMail').html('Send');
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

  
	$(document).on("click", ".ListPopup", function () {
		 $('#listlModal').modal('show')
	});
	
	function fronfillup(fname ,emailphone){
	   $('#email_phone').val(emailphone);
	   $('#fname_prospact').val(fname);
	   
	   $('.close').click();
	   $('#listlModal').modal('hide');
	   $('#listlModal').style('display','none');
	 }

	$('#message').on('input selectionchange propertychange',function(){
		     var defaultMessage = $('#defaultMessage').val();
			 var sLink = "<?php echo $site_url.'' ; ?>";
			 var message = $(".modal-body #message").val();
			 
			 var senderName = $('#sender2').val();
			 
			
			 console.log(message);
			 $(".modal-body #message").val(message);
			 //$(".modal-body #email").val(response.userData.email_phone);
			
			if(isEmailAddress($(".modal-body #email").val())){
					var mailTo = 'mailto:' + $(".modal-body #email").val() + '?subject=<?php echo  EMAIL_SUBJECT; ?>&body=' + encodeURIComponent(message);
					//$('#m-link').html(mailTo);
					$('#sendViaPhone').attr('href',mailTo);
			}else{
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

    </script>
	<?php if($device != 'desktop') { ?><script> $('#sendViaPhone').show();</script><?php } ?>
</body></html>
