<?php
class Model{
	
	public function Model(){
	
	}//end function

	public function generateLink($data){
		extract($data);
		$user_id  = $_SESSION['users']['vs_users_id'];
		$sql = "INSERT into links (name,video_id,vs_users_id,vs_survey_id) values('$name','$video_id',$user_id,$vs_survey_id);";
		mysql_query($sql);
	}

	function listLinks(){
		$user_id  = $_SESSION['users']['vs_users_id'];
		$team_id  = $_SESSION['users']['vs_team_id'];
		//$sql = "SELECT l.name,l.video_id,l.vs_survey_id,vs.name as survey_name from links l inner join vs_survey vs on vs.vs_survey_id = l.vs_survey_id where vs_users_id = $user_id order by id desc limit 800";
		if(strtolower($_SESSION['users']['user_type']) != 'admin')
		//$sql = "SELECT * from links l where l.vs_users_id = $user_id order by id desc limit 800";
		$sql = "SELECT * from links l where l.vs_team_id = $team_id order by id desc limit 800";
		else
		$sql = "SELECT * from links l  order by id desc limit 800";

		$result = mysql_query($sql);

		return $result;
	}//end fucntion


	function checkUser($username,$password){
		$sql = "SELECT * from vs_users where username = '$username' and password = '$password' limit 1; ";
		$result = mysql_query($sql);
		$data = mysql_fetch_assoc($result);
		if(mysql_num_rows($result) > 0 ){
			return $data;
		}else{
			return 0 ;
		}
	}

	function getSurveyMaster(){
		$sql = "SELECT * from vs_survey order by name asc; ";
		$result = mysql_query($sql);
		return $result;
	}


	function getVideoReport($video_id){
		
		// rc - 20161218: update to include sender name
		//$sql = "SELECT sl.id ,sl.link_id, sl.unique_link,sl.name,sl.email_phone, u.displayname as sender_name from survey_links sl join links on (links.id = sl.link_id) join vs_users u using(vs_users_id) where sl.link_id = '$video_id' order by sl.id asc";
		
		$user_id  = $_SESSION['users']['vs_users_id'];
				
		$sql = "SELECT sl.id ,sl.link_id, sl.unique_link,sl.name,sl.email_phone from survey_links sl where sl.link_id = $video_id AND vs_users_id = $user_id order by sl.id asc ";
		$res = mysql_query($sql);
		$resultantData = array();
		while($submitQ = mysql_fetch_assoc($res)){
			$sql = "SELECT count(lh.viewed) as viewed,count(lh.video_started) as video_started, lh.ip_address,lh.added_on FROM `link_history` lh where lh.survey_link_id = {$submitQ['id']} group by lh.survey_link_id order by lh.survey_link_id asc";
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);
			$sql = "SELECT lh.answer,lh.comments from link_history lh where lh.survey_link_id = {$submitQ['id']} and (lh.answer IS NOT NULL OR  lh.comments IS NOT NULL) order by lh.id desc ";
			$res1 = mysql_query($sql);
			while($submitQ1 = mysql_fetch_assoc($res1)){
				if(empty($row['answer'])){
					$row['answer']  = $submitQ1['answer'];
				}
				if(empty($row['comments'])){
					$row['comments']  = $submitQ1['comments'];
				}
			}
			
			
			$sql2 = "SELECT * FROM `vs_survey_feedback` WHERE `survey_id` = {$submitQ['id']} order by id desc LIMIT 0, 1";
			$res2 = mysql_query($sql2);
			$feedback = mysql_fetch_assoc($res2);
			//print_r($feedback);
			if($feedback){
				
				 $prepostion_ids = implode(',', unserialize($feedback['prepostion_ids']));
				
				if($prepostion_ids){
					
						$row['preposition_comments']  = $feedback['comments'];
									
				    $sql3 = "SELECT * FROM `vs_fu_actions` WHERE `vs_fu_actions_id` IN ($prepostion_ids) ORDER BY `vs_fu_actions_id`";
			        $res3 = mysql_query($sql3);
					$number = mysql_num_rows($sql3);
					
					
			       while($fdb = mysql_fetch_assoc($res3)){
							
					    $temp = '<b> &bull; </b> '.$fdb['label'];
					   	
						$row['preposition_answer']  .= $temp.'<br> ';
					  
				   }
				   
				   
				 
				}				
			}
						
			$sqLastUpdatedTime = "SELECT lh.id,lh.added_on from link_history lh where lh.survey_link_id = {$submitQ['id']} order by lh.id desc ";
			$resLastUpdatedTime = mysql_query($sqLastUpdatedTime);
			$resLastUpdatedTime = mysql_fetch_assoc($resLastUpdatedTime);
			$row['added_on'] = $resLastUpdatedTime['added_on'];
			$row['name'] = $submitQ['name'];
			$row['unique_link'] = $submitQ['unique_link'];
			$row['email_phone'] = $submitQ['email_phone'];
			$row['sender_name'] = $submitQ['sender_name'];
			$resultantData[] = $row;
			//print "<pre>" . print_r($row,true) . "</pre>";	
		}
		mysql_free_result($result);
		mysql_free_result($res);
		mysql_free_result($res1);
		return $resultantData;
	}//end function
	

	function getSurveyData($un_survey_link){
		$surveyData = array();
		$sql = "SELECT l.vs_form_id from links l inner join survey_links sl on sl.link_id =l.id where sl.unique_link = '$un_survey_link' order by l.id asc limit 1";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		/*$sql = "SELECT vsq.vs_surveyquestions_id,vs.name,vs.title,vs.comment,vsq.label,vsq.ord from vs_survey vs inner join vs_surveyquestions vsq on vsq.vs_survey_id=vs.vs_survey_id where vs.vs_survey_id = {$row['vs_survey_id']} order by vsq.ord asc ;";
		$res = mysql_query($sql);
		while($submitQ = mysql_fetch_assoc($res)){
			$surveyData[] = $submitQ;
		}*/
		
		$surveyData = $this->getFormData($row['vs_form_id']);
		//echo '<pre>';
		//print_r($surveyData);
		//echo '</pre>'; die;
		
		return $surveyData;
	}
	
	function getServeprsopectdata($un_survey_link, $link){
		
		$sql = "SELECT name, email_phone,link_id,vs_users_id,vs_prospect_id  FROM survey_links WHERE unique_link = '$un_survey_link'";
		$res = mysql_query($sql);
		$submitQ = mysql_fetch_assoc($res);
		
		//print_r($submitQ);
		
		if($submitQ['vs_users_id']){
			$sql2 = "SELECT vs_team_id FROM vs_users WHERE vs_users_id = ".$submitQ['vs_users_id'];
			$res2 = mysql_query($sql2);
			$submitQ2 = mysql_fetch_assoc($res2);
			$vsteamid = $submitQ2['vs_team_id'];
		}else{
			$vsteamid = 0;
		}
		
		 preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $submitQ['email_phone'], $matches);
         $email = ($matches[0][0] != '') ? $matches[0][0] : '';	
		// print_r($email);
		 
		 $submitQ['email_phone'] = str_replace($email,"",$submitQ['email_phone']);
		
		
		if(!empty($submitQ) && $submitQ['vs_prospect_id'] > 0){
			
			 $prospectsql = "UPDATE vs_prospect SET survey_link_id = '".$link."' ,firstname = '".$submitQ['name']."' ,phone = '".$submitQ['email_phone']."' ,vs_users_id = '".$submitQ['vs_users_id']."', vs_team_id = '$vsteamid', email = '".$email."' WHERE vs_prospect_id = '".$submitQ['vs_prospect_id']."'";
		    $prospectres = mysql_query($prospectsql);
			
		}
		if(!empty($submitQ) && $submitQ['vs_prospect_id'] == 0){
			
			$prospectsql = "INSERT INTO vs_prospect (survey_link_id,firstname,phone,email, vs_users_id, vs_team_id) VALUES ('$link','".$submitQ['name']."','".$submitQ['email_phone']."','".$email."','".$submitQ['vs_users_id']."', '$vsteamid')";
		    $prospectres = mysql_query($prospectsql);
			
			 $propectid = mysql_insert_id();
			
			$sql1 = "UPDATE survey_links SET vs_prospect_id = '$propectid' WHERE unique_link = '$un_survey_link' ";
		    $res1 = mysql_query($sql1);
			
		}
	
		//return $submitQ;
		
	}


/*	function getVideoReport($video_id){

		$sql = "SELECT count(lh.id) as hasRows FROM `link_history` lh inner join survey_links sl on lh.survey_link_id = sl.id where sl.link_id = $video_id group by lh.survey_link_id order by lh.survey_link_id asc";
		$result = mysql_query($sql);
		//$row = mysql_fetch_assoc($result);
		//print_R($row);exit;
		if(mysql_num_rows($result) <= 0 ){
			$row = array();
			$sql = "SELECT sl.unique_link,sl.name,sl.email_phone from survey_links sl where sl.link_id = $video_id order by sl.id asc ";
			$res = mysql_query($sql);
			while($submitQ = mysql_fetch_assoc($res)){
				$row[]  = $submitQ;
			}
			return $row;
		}
		
	

		$sql = "SELECT sl.id as survey_link_id,count(lh.viewed) as viewed,count(lh.video_started) as video_started, lh.ip_address,lh.added_on FROM `link_history` lh inner join survey_links sl on lh.survey_link_id = sl.id where sl.link_id = $video_id group by lh.survey_link_id order by lh.survey_link_id asc";
		$result = mysql_query  ($sql);
		$returnArray = array();
		while($row = mysql_fetch_assoc($result)){
			$sql = "SELECT sl.unique_link,sl.name,sl.email_phone,lh.answer,lh.comments from link_history lh inner join survey_links sl on lh.survey_link_id = sl.id where sl.link_id = $video_id and  lh.survey_link_id = '{$row['survey_link_id']}' and (lh.answer IS NOT NULL OR  lh.comments IS NOT NULL) order by lh.id desc ";
			//echo $sql. '<br />';
			$res = mysql_query($sql);
			while($submitQ = mysql_fetch_assoc($res)){
				if(empty($row['answer'])){
					$row['answer']  = $submitQ['answer'];
				}
				if(empty($row['comments'])){
					$row['comments']  = $submitQ['comments'];
				}
				$row['unique_link']  = $submitQ['unique_link'];
				$row['name']  = $submitQ['name'];
				$row['email_phone']  = $submitQ['email_phone'];
			}
			$sqLastUpdatedTime = "SELECT lh.id,lh.added_on from link_history lh order by lh.id desc ";
			$resLastUpdatedTime = mysql_query($sqLastUpdatedTime);
			$resLastUpdatedTime = mysql_fetch_assoc($resLastUpdatedTime);
			$row['added_on'] = $resLastUpdatedTime['added_on'];
			$returnArray[] = $row;
		}
			mysql_free_result ($result);
			mysql_free_result ($res);
			//print_R($returnArray);
			return $returnArray;
	} */

	function addRecepients($data){
		//print_r($data);
		extract($data);
		$user_id  = $_SESSION['users']['vs_users_id'];
		//echo $recepients;exit;
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $recepients) as $line){
			$line = explode(',',$line);
			if(!empty($line[0])){
				$id = $this->userExists($line[0],$video_id, $user_id);
				if(!$id){
					$line[1] = trim($line[1]);
					$name  = empty($line[1]) ? '' : $line[1]; 
					$name  = empty($line[1]) ? '' : $line[1]; 
					$email_phone = trim($line[0]);
					$sql = "INSERT into survey_links (link_id,name,email_phone, vs_users_id) values($video_id,'$name','$email_phone','$user_id');";
					mysql_query($sql);
					$last_insert_id = mysql_insert_id(); 
					$prefix = 'survey-' . $last_insert_id.'-';
					$uniqid = uniqid($prefix);
					$sql = "UPDATE survey_links set unique_link = '$uniqid' where id=$last_insert_id limit 1;";
					mysql_query($sql);
				}else{ //update user
					$email_phone = trim($line[0]);
					$line[1] = trim($line[1]);
					$name  = empty($line[1]) ? '' : $line[1]; 
					$sql = "UPDATE survey_links SET email_phone = '$email_phone', name = '$name', vs_users_id = '$user_id'  where id = $id limit 1 ;";
					mysql_query($sql);
				}
			}//end not empty
		}//end foreach line
	}

	function addUser($data){
		//print_r($data);exit;
		extract($data);
		$user_id  = $_SESSION['users']['vs_users_id'];
		$email_phone = trim($email_phone);
		$id = $this->userExists($email_phone,$video_id, $user_id);
		$name = empty($name) ? '' : $name;
		if(!$id){
			$sql = "INSERT into survey_links (link_id,name,email_phone,vs_users_id) values($video_id,'$name','$email_phone','$user_id');";
			mysql_query($sql);
			$last_insert_id = mysql_insert_id(); 
			$prefix = 'survey-' . $last_insert_id.'-';
			$uniqid = uniqid($prefix);
			$sql = "UPDATE survey_links set unique_link = '$uniqid' where id=$last_insert_id limit 1;";
			mysql_query($sql);
		}else{ //update user
			$name  = empty($name) ? '' : $name ; 
			$sql = "UPDATE survey_links SET email_phone = '$email_phone', name = '$name', vs_users_id = '$user_id'  where id = $id limit 1 ;";
			mysql_query($sql);
		}
		$sql = "SELECT * from survey_links where link_id = $video_id and email_phone = '$email_phone' order by id asc limit 1";
		$result = mysql_query($sql);
		return mysql_fetch_assoc($result);
	}//end function


	function userExists($email_phone,$link_id, $user_id){
		$email_phone = trim($email_phone);
		$sql = "SELECT id from survey_links where email_phone = '$email_phone' and link_id = $link_id and vs_users_id = $user_id order by id desc limit 1";
		$result = mysql_query($sql);
		$data = mysql_fetch_assoc($result);
		if(mysql_num_rows($result) > 0 ){
			return $data['id'];
		}else{
			return 0;
		}
	}//end function

	function getOriginalVideoID($video_id){
		$sql = "SELECT video_id,name from links where id = $video_id order by id desc limit 1";
		$result = mysql_query($sql);
		$data = mysql_fetch_assoc($result);
		if(mysql_num_rows($result) > 0 ){
			return array($data['video_id'],$data['name']);
		}else{
			return 0;
		}
	}

	function recepientsData($video_id){
		$sql = "SELECT  * from survey_links where link_id = $video_id order by id asc limit 800";
		$result = mysql_query($sql);
		return $result;
	}

	function getLinkHistory($link_id){
		$sql = "SELECT * from link_history where link_id = $link_id order by id desc limit 800";
		echo $sql;
		$result = mysql_query  ($sql);
		return $result;
	}//end fucntion

	function getLinkStats($link_id){
		$sql = "SELECT count(viewed) as viewed,count(video_started) as video_started, ip_address,added_on FROM `link_history` where link_id = $link_id group by ip_address order by id desc ";
		$result = mysql_query  ($sql);
		$returnArray = array();
		while($row = mysql_fetch_assoc($result)){
			$sql = "SELECT answer,comments from link_history where ip_address = '{$row['ip_address']}' and answer IS NOT NULL order by id desc limit 1";
			$res = mysql_query($sql);
			$submitQ = mysql_fetch_assoc($res);
			$row['answer']  = $submitQ['answer'];
			$row['comments']  = $submitQ['comments'];
			$returnArray[] = $row;
		}
			mysql_free_result ($result);
			mysql_free_result ($res);
			//print_R($returnArray);
			return $returnArray;
	}//end fucntion



	function isValidSurveyID($surveyID){
		$sql = "SELECT sl.id,l.video_id as video_id from survey_links sl inner join links l on l.id = sl.link_id where unique_link = '$surveyID' order by id desc limit 1";
		$result = mysql_query($sql);
		$data = mysql_fetch_assoc($result);
		if(mysql_num_rows($result) > 0 ){
			return $data['video_id'];
		}else{
			return 0;
		}
	}

	function addSurveySubmit($data){
		extract($data);
		//echo '<pre>';
		//print_r($data);die;
		$ip_address = $_SERVER['REMOTE_ADDR'];
		//$sql = "INSERT into link_history (survey_link_id,answer,comments,ip_address) values($link_id,'$answer','$comments','$ip_address');";
		//mysql_query($sql);
		$vs_data = array();
		$vs_data['vs_form_id'] = $data['vs_form_id'];
		
		for($i = 1; $i <= $data['fldcount']; $i++){
		
		$vs_data['vs_form_sets'][$data['vs_form_set_id_'.$i]] = array(
											'vs_form_set_id'=>$data['vs_form_set_id_'.$i],
											'vs_fu_surveyactions_id'=>$data['vs_fu_surveyactions_id_'.$data['answer_'.$i]],
											'answer'=>$data['answer_'.$i]
									  );
									  
		 $actionid = $data['vs_fu_surveyactions_id_'.$data['answer_'.$i]];
		 
		
		
		}
		$ansdata = serialize($vs_data);
		
		 $sql = "INSERT into vs_prospect_ans(vs_form_id,ans)values(".$data['vs_form_id'].",'$ansdata')";
		 $insdata = mysql_query($sql);
		
		 $getsql = "select * from vs_fu_surveyactions WHERE vs_fu_surveyactions_id=".$actionid;
		 $getdata = mysql_query($getsql);
		 $selectdata = mysql_fetch_assoc($getdata);
		 $result = array();
		 $result['vs_fu_surveyactions_id']= $selectdata['vs_fu_surveyactions_id'];
		 $result['vs_fu_actions_id'] = $selectdata['vs_fu_actions_id'];
		 $result['r_type_code'] = $selectdata['r_type_code'];
		 $result['r_value'] = $selectdata['r_value'];
		 
		
		 
		 if($result){
			 return $result;
			 }
			 else{
				 return 0;
				 }
		 //echo '<pre>';
		 //print_r($selectdata);die;
		
		
		//$sql_one = "SELECT vs_surveyquestions_id FROM vs_surveyquestions WHERE label = '$answer'";
		
		//$result = mysql_query($sql_one);
		//$data = mysql_fetch_assoc($result);
		
		
		/*if($data){
			$sql_two = "SELECT sq.vs_surveyquestions_id, sq.label, fu_s.name, fu_s.label, fu_a.label 
						FROM vs_surveyquestions sq join vs_fu_sets fu_s using (vs_fu_sets_id) 
						join vs_fu_surveyactions fu_sa using (vs_fu_sets_id) 
						join vs_fu_actions fu_a using (vs_fu_actions_id)
						WHERE sq.vs_surveyquestions_id='".$data['vs_surveyquestions_id']."'
						ORDER BY sq.vs_surveyquestions_id, fu_sa.ord, fu_sa.vs_fu_surveyactions_id";
				$res = mysql_query($sql_two);
				$prpostion = mysql_fetch_assoc($res);
				
				
				while($prpostion = mysql_fetch_assoc($res)){		
					$res_data[] = $prpostion;
				}
				
				
			}*/
			
		/*if($data){
			return $data['vs_surveyquestions_id'];
		}else{
			return 0;
		}*/	
		
	}
	
	function getPrepostion($id){
			
					
						
			$sql_two = "SELECT sq.vs_surveyquestions_id, sq.label as lbl, fu_s.name, fu_s.label as title, fu_a.label, fu_a.vs_fu_actions_id
						FROM vs_surveyquestions sq
						JOIN vs_fu_sets fu_s
						USING ( vs_fu_sets_id )
						JOIN vs_fu_surveyactions fu_sa
						USING ( vs_fu_sets_id )
						JOIN vs_fu_actions fu_a
						USING ( vs_fu_actions_id )
						WHERE sq.vs_surveyquestions_id ='$id'
						ORDER BY sq.vs_surveyquestions_id, fu_sa.ord, fu_sa.vs_fu_surveyactions_id";

						
				$res = mysql_query($sql_two);
				//$prpostion = mysql_fetch_assoc($res);				
				$rawData = array();
	            
				while($prpostion = mysql_fetch_assoc($res)){		
					$rawData[] = $prpostion;
				}
				
				//print_r($rawData);
				
				$prepData = array();
				$quesData = array();
				
				foreach($rawData as $rd){
					$quesData['vs_surveyquestions_id'] = $rd['vs_surveyquestions_id'];
					$quesData['name'] = $rd['name'];
					$quesData['title'] = $rd['title'];		
					$prepData[] = array('vs_fu_actions_id' => $rd['vs_fu_actions_id'], 'label' => $rd['label']);
				}
				
				
				$res_data = array(
				             'vs_surveyquestions_id' => $quesData['vs_surveyquestions_id'],
							 'name' => $quesData['name'], 
							 'title' => $quesData['title'],
							 'prepositions' => $prepData
							 );
			//print_r($res_data);
			
		if($res_data){
			return $res_data;
		}else{
			return 0;
		}
	}
	function setperpostion($data){
		
		$survey_id = $data['sid'] ;	
		$prepostion_ids = serialize($data['prepostions']) ;
		$question_id = $data['qusid'] ;
		$comments = $data['comments'] ;
		$feedback_time = time();
	    $sql="INSERT into vs_survey_feedback (survey_id,prepostion_ids,question_id,comments,feedback_time) values('$survey_id','$prepostion_ids','$question_id','$comments','$feedback_time');";
		mysql_query($sql);
	}

	function addViewCount($link_id){
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql = "INSERT into link_history (survey_link_id,viewed,ip_address) values($link_id,1,'$ip_address');";
		mysql_query($sql);
	}

	function addWatchCount($link_id){
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql = "INSERT into link_history (survey_link_id,video_started,ip_address) values($link_id,1,'$ip_address');";
		mysql_query($sql);
	}
	
	function prospactData($userid){
		
		$sql = "SELECT * FROM vs_prospect WHERE vs_users_id = '$userid'";
		$result = mysql_query($sql);
		while($submitQ = mysql_fetch_assoc($result)){
			$propactData[] = $submitQ;
		}
		return $propactData;
	
	}
	
	
	function templateList($userid, $team_id){
		
		$sql = "select vs_templates_id, name, body, subject, ord from vs_templates where enabled > 0 and vs_team_id = '$team_id' Union select vs_templates_id, name, body, subject, ord from vs_templates where enabled > 0 and vs_users_id = '$userid' order by ord, name";
		$result = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)){
			$tmplList[] = $row;
		}
		return $tmplList;
	
	}
	
	function addAppointmentSubmit($data){
		
		$formname = $data['formname'] ;	
		$vs_prospect_id = $data['vs_prospect_id'] ;
		$achoice = $data['achoice'];
		$choiceTxt = '';
		if($achoice == 'Autre'){
			$choiceTxt = $data['choiceTxt'] ;
		}
		$notes = $achoice."\n---------------\n".$choiceTxt;
		
	    $sql="INSERT into vs_prospect_notes (vs_prospect_id, form, notes) values('$vs_prospect_id','$formname','$notes');";
		mysql_query($sql);
		
		$vs_notes_id = mysql_insert_id();
		
		if ($vs_notes_id){
			
			$sql = "select $vs_notes_id vs_notes_id, vs_prospect_id, firstname, lastname, phone, email from vs_prospect where vs_prospect_id='$vs_prospect_id' LIMIT 1" ;
			
			$result = mysql_query($sql);
			$row = mysql_fetch_assoc($result);			
			//print_r($row,true) . " --xxxx";
			
			if ($row){
				return $row;
			}else{
				return false;
			}			
			
		}else{
			return false;
		}
	}
	
	
	function isValidFormID($formID){
		$sql = "SELECT * FROM `vs_form_set` WHERE `vs_form_id` = '$formID'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) > 0 ){
			return true;
		}else{
			return false;
		}
	}
	
	
	function getFormData($formID){
		
		
		$formData = array();
		$lng = $_SESSION['lang'];
				
		$sql1 = "SELECT fs.`vs_form_id`, fu_a.label AS form_title, fu_a.`vs_fu_actions_id` FROM `vs_form_set` fs INNER JOIN `vs_fu_actions` fu_a ON fs.`vs_fu_actions_id` = fu_a.`vs_fu_actions_id` WHERE `vs_form_id` = '$formID' and `role` = 'title' and `type` = 'elm' and `displaytype` = 'label' ";
		$result1 = mysql_query($sql1);		
		$titlerow = mysql_fetch_assoc($result1);		
		
		$sql2 = "SELECT fs.*, fu_s.name AS fldTitle FROM `vs_form_set` fs LEFT JOIN vs_fu_sets fu_s ON fs.`vs_fu_sets_id` = fu_s.vs_fu_sets_id WHERE fs.`vs_form_id` = '$formID' and fs.`type` = 'set' ORDER BY fs.ord";
		$result2 = mysql_query($sql2);
			
		$formElm = array();
		while($row = mysql_fetch_assoc($result2)){			
			
			$sql3 = "SELECT fu_s.`vs_fu_sets_id`,fu_s.`name`,fu_sa.vs_fu_actions_id, fu_sa.vs_fu_surveyactions_id, IF(tr.label IS NOT NULL,tr.label,fu_a.label) AS label, fu_a.label AS label_default, tr.label AS label_trans FROM `vs_fu_sets` fu_s LEFT JOIN `vs_fu_surveyactions` fu_sa ON fu_s.`vs_fu_sets_id` = fu_sa.`vs_fu_sets_id` LEFT JOIN `vs_fu_actions` fu_a ON fu_sa.vs_fu_actions_id = fu_a.vs_fu_actions_id LEFT JOIN vs_lang_trans tr ON (tr.vs_fu_actions_id = fu_a.vs_fu_actions_id AND tr.vs_lang_code = '$lng') WHERE fu_s.`vs_fu_sets_id` = ".$row['vs_fu_sets_id']." ORDER BY fu_sa.ord";
			
		    $result3 = mysql_query($sql3);
			
			while($arow = mysql_fetch_assoc($result3)){
				
				$row['options'][] = $arow;
				
			}
			
			$formElm[] = $row;
						
		}
		
		
		
		$sql4 = "SELECT fs.*, fu_a.vs_fu_actions_id, IF(tr.label IS NOT NULL,tr.label,fu_a.label) AS label, fu_a.label AS label_default, tr.label AS label_trans FROM `vs_form_set` fs LEFT JOIN `vs_fu_actions` fu_a ON fs.vs_fu_actions_id = fu_a.vs_fu_actions_id LEFT JOIN vs_lang_trans tr ON (tr.vs_fu_actions_id = fu_a.vs_fu_actions_id AND tr.vs_lang_code = '$lng') WHERE fs.`vs_form_set_id` = -99 AND fs.`type` = 'elm'";
		
		$result4 = mysql_query($sql4);		
		$buttonrow = mysql_fetch_assoc($result4);
		
		$formElm[] =  $buttonrow;
		
		
		$sql5 = "SELECT fs.*, fu_a.vs_fu_actions_id, IF(tr.label IS NOT NULL,tr.label,fu_a.label) AS label, fu_a.label AS label_default, tr.label AS label_trans FROM `vs_form_set` fs LEFT JOIN `vs_fu_actions` fu_a ON fs.vs_fu_actions_id = fu_a.vs_fu_actions_id LEFT JOIN vs_lang_trans tr ON (tr.vs_fu_actions_id = fu_a.vs_fu_actions_id AND tr.vs_lang_code = '$lng') WHERE fs.`vs_form_set_id` = -98 AND fs.`type` = 'elm'";
		
		$result5 = mysql_query($sql5);		
		$commentsbox = mysql_fetch_assoc($result5);
		
		$formElm[] =  $commentsbox;		
				
		$formData = $titlerow;
		
		$formData['formfields'] = $formElm;		
		return $formData;
	}
	


}//end class

?>