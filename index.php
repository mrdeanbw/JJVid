<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('max_execution_time', 0);
define(ADMIN_PASSWORD,'test@dmin');
define(EMAIL_SUBJECT, 'Video Survey');
//define(EMAIL_FROM,'admin@jstore.one');

//echo __FILE__;
$site_url = 'http://'.$_SERVER['HTTP_HOST'].'/video-survey/';
$site_url = 'http://j-vids.com/';

$dir = dirname(__FILE__).'/';
$email_message = file_get_contents($dir.'email_message.php');
define(EMAIL_MESSAGE,$email_message);



array_walk($_POST, 'escapeString');
array_walk($_GET, 'escapeString');
require_once($dir.'db.php');
require_once($dir.'model.php');
$model= new Model();
$surveyList = $model->getSurveyMaster();
$device =  isMobile();

//die($_SERVER['QUERY_STRING'] ."||". !(strpos($_SERVER['QUERY_STRING'],'=')) . "//" . (strlen($_SERVER['QUERY_STRING'])>0 && !strpos($_SERVER['QUERY_STRING'],'=')));
//[rc-20161218: default behavior
if (strlen($_SERVER['QUERY_STRING'])>0 && !strpos($_SERVER['QUERY_STRING'],'=')){
	//assume querystring is ID and action =s
	$_REQUEST['a'] = 's';
	$_REQUEST['sid'] = $_SERVER['QUERY_STRING'];
	 $_GET['sid'] = $_SERVER['QUERY_STRING'];
	
}
//rc]



//$device =  'android';

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout'){
	$_SESSION['users'] = '';
	echo 'Logged out successfully!';exit;

}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'survey-viewed'){
	$link_id = $_POST['link_id'];
	$model->addViewCount($link_id);
	echo json_encode(array('msg' => 'added'));
	exit;
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'survey-submit'){
	
	//$link_id = $_POST['link_id'];
	$result = $model->addSurveySubmit($_POST);
	
    $result['debug'] = $_POST['debug'] ? $_POST['debug'] : '';
	

	echo json_encode($result);
	exit;
	//print_r($result);
	
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'survey-getprepositions'){
	
	
/*	
	$sd = explode('-',$_REQUEST['sid']);
	$sid = $sd[1];
	if($qusid){
	$result = $model->getPrepostion($qusid);*/
	$formid = $_POST['formid'];	
	if($formid){
	$formData = $model->getFormData($formid);
	ob_start();
    require_once($dir.'html/prepostion.php');
	$outhtm = ob_get_contents();
    ob_end_clean();
	echo json_encode(array('formid' => $formid, 'prepostions' => $outhtm));
	
	}	
	exit;
	
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'setprepostion'){	
	$setperpostion = $model->setperpostion($_REQUEST);
	echo json_encode(array('msg' => 'success'));
	exit; 
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'appointment'){		
	require_once($dir.'html/appointment.php');
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'appointment2'){		
	require_once($dir.'html/appointment2.php');		
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'appointment-submit'){	
	$result = $model->addAppointmentSubmit($_POST);
	
	echo json_encode( array_merge ( $result, $_REQUEST ) );
	exit;
	
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'survey-watched'){
	$link_id = $_POST['link_id'];
	$model->addWatchCount($link_id);
	echo json_encode(array('msg' => 'survey-watched'));
	exit;
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'save-link'){
	if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $_POST['video_id'], $match)) {
		$video_id = $match[1];
	}else{
		$video_id = $_POST['video_id'];
	}
	$_POST['video_id'] = $video_id;
	$model->generateLink($_POST);
	$result = $model->listLinks();
	$_SESSION['msg']  = 'Video added successfully.';
	header('Location: index.php');
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'reports'){
	$video_id = $_GET['id'];
	if(empty($video_id)){echo 'Forbidden';exit;}
	//if(!$model->isReportAccess($video_id)){echo 'Forbidden';exit;}
	$result = $model->getVideoReport($video_id);
	$originalVideoID = $model->getOriginalVideoID($video_id);
	$recepientsDataResult = $model->recepientsData($video_id);
	$templateList = $model->templateList($_SESSION['users']['vs_users_id'], $_SESSION['users']['vs_team_id']);
	require_once($dir.'html/reports.php');
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add-recepients'){
	$model->addRecepients($_POST);
	echo json_encode(array('msg' => 'success'));exit;
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'send-video'){
	$addedUserData = $model->addUser($_POST);
	echo json_encode(array('msg' => 'success', 'userData' => $addedUserData ));exit;
}else if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'send-mail'){
	//print_r($_POST);exit;
	sendMail($_POST);
	echo json_encode(array('msg' => 'success'));exit;
}else if(isset($_REQUEST['a']) && $_REQUEST['a'] == 's'){
	//$survey_id = $_GET['sid'];
	
	if(isset($_GET['lang']) && !empty($_GET['lang'])) 
		$_SESSION['lang'] = $_GET['lang'];
	else 
		$_SESSION['lang'] = '';
	
	if(isset($_GET['sid']) && $video_id = $model->isValidSurveyID($_GET['sid'])){
		$link = explode('-',$_GET['sid']);
		$surveyData = $model->getSurveyData($_GET['sid']);
		
		
		$surveyprospectdata =$model->getServeprsopectdata($_GET['sid'], $link['1']);
		
		require_once($dir.'html/survey.php');
	}else{
		echo "Forbidden";
		exit;
	}

}else if(isset($_REQUEST['a']) && $_REQUEST['a'] == 'form'){
	//$survey_id = $_GET['sid'];
	
	if(isset($_GET['fid']) && $model->isValidFormID($_GET['fid'])){
		
		$formData = $model->getFormData($_GET['fid']);
		
		
		require_once($dir.'html/form.php');
	}else{
		echo "Forbidden";
		exit;
	}


}else{
	if(empty($_GET['u'])  || empty($_GET['p'])){
		if(empty($_SESSION['users'])){
			echo 'Forbidden';exit;
		}
	}else{
		$username = $_GET['u'];$password = $_GET['p'];
		$user = $model->checkUser($username,$password);
		if(is_array($user)){
			$_SESSION['users'] = $user;
		
		}else{
			echo 'Forbidden';exit;
		}
	}
	$result = $model->listLinks();
	
	$propactresult = $model->prospactData($_SESSION['users']['vs_users_id']);
	
	$templateList = $model->templateList($_SESSION['users']['vs_users_id'], $_SESSION['users']['vs_team_id']);
	
	
	require_once($dir.'html/home.php');
}


function escapeString(&$item1, $key)
{
	$item1 = addslashes($item1);
}


function sendMail($data){
	extract($data);
	$to = $email;
	$subject = $subject;
	$txt = nl2br($message);
	

	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	//$headers .= "From: admin@jstore.one" . "\r\n" .
	//$headers .= "From: admin@jstore.one" . "\r\n" .
	//$headers .= "Bcc: responsemee@gmail.com";
	mail($to,$subject,$txt,$headers);
}


function isMobile(){
	//Detect special conditions devices
	$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
	$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
	if($Android)return 'android';
	else if( $iPod || $iPhone || $iPad)return 'iphone';
	else return 'desktop';
}

?>