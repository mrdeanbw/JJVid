<?
/*
$dir = dirname(__FILE__).'/';
$email_message = file_get_contents($dir.'email_message.php');
define(EMAIL_MESSAGE,$email_message);

$subject = "This is my subject";
$in_to = 
function msg_format_viaphone($in_to, $in_msg, $in_device, ){
	
	$msg_type=str_pos('@',$in_to)>0?'sms':'email';
				
	if($in_type=='sms'){
		switch($in_device){
			case 'iphone':
								
								
			break;
			default: //android
		}		
	}else{
		$out_msg = $in_msg;
	}
		
	return $out_msg;	
}

*/


print "<HR> QUERY STUFF<hR>" . print_r($_REQUEST,true) . " length : " . count($_REQUEST) . "<hr>". '<pre>'.print_r($_SERVER, TRUE).'</pre>';;

?>




<h1>

<a href="sms:+13125238524&body=what's going on I %0a am already going%0a ape %0ashit This me">IPHONE: Link TEST</a>
<hr>
<a href="sms:14035550185?body=I%27m%20interested%20in%20your%20product.%0a%20Please%20%0acontact%20me."> ANDROID-x: Send a SMS message</a>
<hr>
<br>
<a href="mailto:email@address.com?subject=test&body=type%20your%0Amessage%20here."> EMAIL: Send a EMAIL message</a>
</h1>


<iframe src="https://jeunesse.youcanbook.me/?noframe=true&skipHeaderFooter=true" id="ycbmiframejeunesse" style="width:100%;height:1000px;border:0px;background-color:transparent;" frameborder="0" allowtransparency="true"></iframe><script>window.addEventListener && window.addEventListener("message", function(event){if (event.origin === "https://jeunesse.youcanbook.me"){document.getElementById("ycbmiframejeunesse").style.height = event.data + "px";}}, false);</script>
