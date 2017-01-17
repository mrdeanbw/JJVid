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
		<div class="row form-content">
			<form role="form" id = "appointmentFrm" method = "POST" >			
				<input type = "hidden" name = "action" value = "appointment-submit" />
				<input type = "hidden" name = "formname" value = "biz_form_1" />
				<input type = "hidden" name = "vs_prospect_id" value = "<?php echo $_GET['pid'];?>" />
				<div class="col-md-12 form-content">
					<div class="form-group form-submit-backcolor">
							<p>Pour aller plus loin il faudrait que nous prenions 30min pour discuter. Le mieux serait en personne mais, cela peut se faire aussi par telephone/skype etc.</p>
					</div>
					<div class="form-group form-backcolor">	
						<p>Je suis disponible pour un rdv</p>
						  <div class="radio">
							<p><label ><input type="radio" name="achoice" class="achoiceTxt" id="achoice1" value = "en personne" checked>en personne</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice2" value = "telephonique" >telephonique</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice3" value = "Autre" >Autre</label></p>  
						  </div>
					</div>
					<div class="form-group form-backcolor" id = "choiceTxtDiv"  style="display:none;">
						<div class="comment-label">Commentaires:</div>
						<input type="type" class="form-control" id = "choiceTxt" name = "choiceTxt" />
					</div>
					<div class="form-group form-submit-backcolor"><input type = "submit" id = "appointment_submit" value = "Valider" /></div>

				</div>
			</form>
		</div><!--- row -->
	 </div><!-- main-row-->
    </div> <!-- /container -->
	<?php include_once($dir.'html/footer.php'); ?>
	<script>
		$(document).ready(function(){
			$(".achoiceTxt").on("click",function(){	
				if($(this).val() == 'Autre'){
					$("#choiceTxtDiv").show();
				}else{
					$("#choiceTxtDiv").hide();
				}
			});
			
			$("#appointmentFrm").on("submit", function(){
				
				$.ajax({
					url: "index.php",
					type: "post",
					cache: false,
					data: $('#appointmentFrm').serialize(),
					beforeSend: function(){

					},
					success: function(response){
						//console.log("----" + response);
						
    					var res = JSON.parse(response);
    					if(res['achoice']=='Autre'){
							$('.page-border').html('<div class="alert alert-success">Merci de votre retour.</div>' );
							$('div.page-border').css({'border':'none'});
    					}else{
							$('.page-border').html('<div class="alert alert-success">Merci de votre retour.</div>' );
							$('div.page-border').css({'border':'none'});
    						
    						
    						var url = 'https://jeunesse.youcanbook.me/?LNAME=' + res['lastname'] + '&FNAME=' + res['firstname'] + '&TEL=' + res['phone'] + '&EMAIL=' + res['email'] + '&REF=' + res['vs_prospect_id'];
    						console.log(url);	
    						window.location.replace(url);
    					}						
					},
					error: function(xhr) { // if error occured
						console.log('Error : ' + xhr.statusText);
						//alert('Error : ' + xhr.statusText )
					},
					complete: function(response){
						
					}
				});
				return false;
			 });
		 });
	</script>
  </body>
</html>
