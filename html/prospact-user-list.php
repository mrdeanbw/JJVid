		<div id="listlModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<!-- Modal content-->
		
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title modal-label"	>User List</h4>
			  </div><!-- modal header -->
			  <div class="modal-body">
  					<?php if(!empty($propactresult)){ ?>
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" width="100%">
                   <thead> <tr><th width="50%"> Email or  Phone </th> <th width="50%">  Nom ou Prénom</th> </tr></thead>
                   <tbody>
                    
						<?php foreach($propactresult as $k => $v){
							if($v['email'] != ''){
								$email_phone = $v['email'];
							}else if($v['phone'] != ''){
								$email_phone = $v['phone'];
							}else{
								$email_phone = '' ;
							}
							
							?>
                       <tr>
                        <td><a href="javascript:void(0)" onclick="fronfillup('<?php echo $v['firstname']; ?>','<?php echo $email_phone; ?>')" ><?php echo $email_phone; ?></a></td>
                        
                        <td><?php echo $v['firstname']; ?></td>
                        
					    </tr>
                    <?php }  ?>
                    </tbody>
					</table>
					</div>
					<?php }
					?>
					
			  </div>
			  <div class="modal-footer">
				
			</div><!-- modal content -->
			</form>
		  </div>
		</div>
		