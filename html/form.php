<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Video Survey</title>

    <!-- Bootstrap core CSS -->
    <link href="/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
        
  </head>
  <body>
    <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
	  <div class = "row page-border" >
		<div class = "product" ><?php echo $formData['form_title']; ?></div>
		<div class="row form-content">
			
			<form role="form" id = "questionFrm" method = "POST" >
			<input type = "hidden" name = "vs_form_id" value = "<?php echo $formData['vs_form_id']; ?>" />
           	<input type = "hidden" name = "action" value = "form-submit" />
			<div class="col-md-6 form-content">
				
		       <?php if(!empty($formData['formfields'])){ 
				
					foreach($formData['formfields'] as $indx => $field){ ?>	
							    
				    <?php if(!empty($field['options'])){ ?>
					<div class="form-group form-backcolor">
						
						<p><?php echo $field['fldTitle']; ?></p>
						
						<?php foreach($field['options'] as $idx => $option){ ?>
						
						<?php if($field['displaytype'] == 'select-one'){ ?>						
							<div class="radio ">
								<label ><input type="radio" name="answer_<?php echo $option['vs_fu_sets_id']; ?>" <?php echo $idx === 0 ? 'checked' : ''; ?> value = "<?php echo $option['vs_fu_actions_id']; ?>" ><?php echo $option['label']; ?></label>
							</div>
						<?php }} ?>
					</div>
				   <?php }}} ?>				
				
					<div class="form-group form-submit-backcolor"><input type = "submit" id = "question_submit" value = "Valider" /></div>

			</div>
			<div class="col-md-6">
				<div class="form-group tpadding">
					<div class = "comment-label">Commentaires:</div>
					<textarea   class = "form-control"  name = "comments" ></textarea>
				</div>
			</div>
			</form>
		</div><!--- row -->
	 </div><!-- main-row-->
    </div> <!-- /container -->
	<?php include_once($dir.'html/footer.php'); ?>
<?php /* ?>
	<script>
	$(document).ready(function(){
		<?php if(empty($_GET['preview'])){ ?>
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: {action : 'survey-viewed', link_id : '<?php echo $link[1]; ?>'},
			beforeSend: function(){
				
			},
			success: function(response){
				console.log(response.msg);
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
			}
		});
		<?php } ?>
	 });

	 function updateWatchStatus(){
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: {action : 'survey-watched', link_id : '<?php echo $link[1]; ?>'},
			beforeSend: function(){
				
			},
			success: function(response){
				console.log(response.msg);
			},
			error: function(xhr) { // if error occured
				console.log('Error : ' + xhr.statusText);
				//alert('Error : ' + xhr.statusText )
			},
			complete: function(response){
			}
		});
	 }

     function getPrepositions(quesid ,sid){
		$.ajax({
			url: "index.php",
			type: "post",
			dataType: "json",
			cache: false,
			data: {action : 'survey-getprepositions', qusid : quesid , sid : sid},
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
				
				//alert(data.qusid);
				$('.page-border').html('<div class="alert alert-success">Je vous remercie de votre opinion.</div>' );
				$('div.page-border').css({'border':'none'});
				//window.location = "?a=s&sid="+response.sid+"&Qusid="+response.Qusid;
				getPrepositions(data.qusid,data.sid);
				
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
	 
	 
	 
function prepositionSubmit(){
	
		if($('input[type=checkbox]:checked').length == 0)
		{
			alert('Please select atleast one checkbox');
			return false;
		}
		
		//var formData = $('#prepostionFrm').serializeArray();
		
		var str=$('#prepostionFrm input:not([type="checkbox"])').serialize();
		var str1=$("#prepostionFrm input[type='checkbox']:checked").map(function(){return this.name+"="+this.value;}).get().join("&");
		if(str1!="" && str!="") str+="&"+str1;
		else str+=str1; 
		
		var txtCmt = $('#comments').val();
	   	
		var formData = str + '&comments=' + txtCmt;

		$.ajax({
			url: "index.php",
			type: "GET",
			cache: false,
			data: formData,
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
	<?php */ ?>

  </body>
</html>
