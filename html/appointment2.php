<?php

//echo "<pre> " . print_r($_REQUEST, true) . "</pre>";

?>

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
	  
	  <div class = "product" >RDV confirmé pour le<br><? echo $_REQUEST['start']; ?>
	  				
	  </div>
		<div class="row form-content">
		
			<form role="form" id = "appointmentFrm" method = "POST" >			
				<input type = "hidden" name = "action" value = "appointment2-submit" />
				<input type = "hidden" name = "formname" value = "biz_form_2" />
				<input type = "hidden" name = "vs_prospect_id" value = "<?php echo $_GET['pid'];?>" />
				<div class="col-md-12 form-content">
					<div class="form-group form-submit-backcolor">
							<p>
Pour préparer au mieux notre rdv, veuillez fournir quelques renseignment complémentaires sur votre situation actuelle. 
<p> 																					
							
					</div>
					<div class="form-group form-backcolor">	
						<p>Selectionnez la situation professionelle qui vous correspond le mieuxL</p>
						  <div class="radio">
						    <p><label ><input type="radio" name="achoice" class="achoiceTxt" id="achoice1" value = "Etudiant" checked>Etudiant</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt" id="achoice2" value = "Chomeur" checked>Chomeur</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt" id="achoice3" value = "Cadre" checked>Cadre</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice4" value = "Artisan" >Artisan</label></p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice5" value = "Dirigeant d'entreprise (CA>250 000)" ></label>Dirigeant d'entreprise (CA>250 000)</p>
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice6" value = "Retraité" >Retraité</label></p>  
							<p><label ><input type="radio" name="achoice" class="achoiceTxt"  id="achoice7" value = "Autre" >Autre</label></p>  
						  </div>
					</div>
					<div class="form-group form-backcolor" id = "choiceTxtDiv"  style="display:none;">
						<div class="comment-label">Commentaires:</div>
						<input type="type" class="form-control" id = "choiceTxt" name = "choiceTxt" />
					</div>

					<div class="form-group form-backcolor">	
						<p>Une affaire idéale qui marche m'interesse surtout si:</p>
						  <div class="radio">
						    <p><label ><input type="radio" name="bchoice" class="achoiceTxt" id="achoice1" value = "Etudiant" checked>Elle me Challenge</label></p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt" id="achoice2" value = "Chomeur" checked>Elle me permet d'avoir du temps libre</label></p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt" id="achoice3" value = "Cadre" checked>Elle me rapporte un revenu complémentaire</label></p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt"  id="achoice4" value = "Artisan" >Je peux la faire a plein temps</label></p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt"  id="achoice4" value = "Artisan" >Je peux la faire a mes heures perdues</label></p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt"  id="achoice5" value = "" ></label>Je peux la faire avec un proche ou en famille</p>
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt"  id="achoice6" value = "Retraité" >Elle me permet de travailler avec d'autres</label></p>  
							<p><label ><input type="radio" name="bchoice" class="achoiceTxt"  id="achoice7" value = "Autre" >Elle me permet de rencontrer du monder</label></p>  
						  </div>
					</div>
					<div class="form-group form-backcolor" id = "bchoiceTxtDiv"  style="display:none;">
						<div class="comment-label">Commentaires:</div>
						<input type="type" class="form-control" id = "bchoiceTxt" name = "bchoiceTxt" />
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
			
			$(".bchoiceTxt").on("click",function(){	
				if($(this).val() == 'Autre'){
					$("#bchoiceTxtDiv").show();
				}else{
					$("#bchoiceTxtDiv").hide();
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
							$('.page-border').html('<div class="alert alert-success">Merci de votre retour.</div>' );
							$('div.page-border').css({'border':'none'});
					
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
