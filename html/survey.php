<?php include_once($dir.'html/header.php'); ?>
<?php include_once($dir.'html/footer.php'); ?>
    <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
	  <div class = "row page-border" >
		<div class = "videoWrapper">
		    <div id="player"></div>
		</div>
		<div class = "product" ><?php echo $surveyData['form_title']; ?>
         <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>{<?php echo $surveyData['vs_fu_actions_id']; ?>, 0}<?php } ?> 
        
        
        </div>
		<div class="row form-content">
			<form role="form" id = "questionFrm" method = "POST" >
             <?php if(!empty($_SESSION['users']) && !empty($_REQUEST['debug'])){?>
			 <input type = "hidden" name = "debug" value = "1" />
			 <?php } ?>
			<input type = "hidden" name = "vs_form_id" value = "<?php echo $surveyData['vs_form_id']; ?>" />
           	<input type = "hidden" name = "action" value = "survey-submit" />
			
		       <?php if(!empty($surveyData['formfields'])){ ?>
				<div class="col-md-6 form-content"> 
				<?php foreach($surveyData['formfields'] as $indx => $field){ ?>
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
                 
                 <?php foreach($surveyData['formfields'] as $indx => $field){ ?>
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
	 </div><!-- main-row-->
    </div> <!-- /container -->
    
    
	

    <script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          videoId: "<?php echo $video_id; ?>",
		 playerVars: {
                    autoplay: 1,
                    showinfo: 0,
                    rel:0
		  },
		  /*width : '1168',
		  height : '441',*/
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        //event.target.playVideo();
      }

	function onPlayerStateChange(event) {
		var stateCode = event.data;
		switch (stateCode) {
			case YT.PlayerState.UNSTARTED:
				console.log('unstarted');
				break;
			case YT.PlayerState.ENDED:
				console.log('ended');
				break;
			case YT.PlayerState.PLAYING:
				console.log('playing');
				<?php if(empty($_GET['preview'])){ ?>
				updateWatchStatus();
				<?php } ?>
				break;
			case YT.PlayerState.PAUSED:
				console.log('paused');
				break;
			case YT.PlayerState.BUFFERING:
				console.log('buffering');
				break;
			case YT.PlayerState.CUED:
				console.log('cued');
				break;
			default:
				console.log('unkonwn state');
		}
	}//end  function

      function stopVideo() {
        player.stopVideo();
      }
    </script>

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
</script>


  </body>
</html>
