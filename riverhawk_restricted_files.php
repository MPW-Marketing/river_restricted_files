<?php
/**
 * Plugin Name: Riverhawk Restricted Downloads
 * Plugin URI: 
 * Description: Restrict Downloads of some files from some companies
 * Version: 0.1
 * Author: DMM
 * Author URI: \
 * License: GPL2
 */
 
 function access_form () {
 $cont .= '<p>Please Enter Your Information To Access These Files<br /><br /><sup>All fields are required</sup></p>
 <script>function validateForm() {
    var x = document.forms["file-access"]["fullname"].value;
	var y = document.forms["file-access"]["emailadd"].value;
	var z = document.forms["file-access"]["company"].value;
    if (x == null || x == "" || y == null || y == "" || z == null || z == "") {
		document.getElementById("alert-area").innerHTML = "<p style=\"border:1px solid red;\">All fields are required</p>";
        return false;
    }
}
</script>
 <form name="file-access" action="'.$_SERVER[REQUEST_URI].'" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
   <input type="hidden" name="form_title" value="Restricted Download Request"/>
   Name: <br />
   <input type="text" name="fullname" value=""/><br/>
   Email: <br/>
   <input type="text" name="emailadd" value=""/><br/>
   Company:<br/>
   <input type="text" name="company" value=""/><br/><br />
   <span id="alert-area"></span>
   <input type="submit" value="Submit" />
</form>';

return do_shortcode( $cont );
}

add_shortcode ('file_access' , 'access_form' );


function display_restricted_files ($atts, $content = null ) {
$company = $_POST["company"];
$submit_name = $_POST["fullname"];
$submit_email = $_POST["emailadd"];
$send_to = "info@riverhawk.com";
$send_headers = 'Bcc: damon@mpwmarketing.com' . "\r\n";
$send_subject = "Restricted File Access Attempted";

$dl_cookie = $_COOKIE["allowdownload"];
$access_granted = false;
if ($company != '' || $dl_cookie == "YES"){$access_granted = true;}
$match_made = false;
$restricted_companies = array(
	'mpwmarketing'
	,'technofast'
	,'novamachineproducts'
	,'hytorc'
	,'hydratight'
	,'tentec'
	,'ith'
	,'hti'
	,'ameridrives'
	,'altra'
	,'altracoupling'
	,'goodrich'
	,'unitedtechnologies'
	,'utcareospacesystems'
	,'johncrane'
	,'emerson'
	,'kop-flex'
	,'torquemeters'
	,'lovejoy'
	,'rexnord'
	,'couplingcorporationofamerica'
);
$restricted_emails = array(
	'ameridrives.com'
	,'goodrich.com'
	,'utas.utc.com'
	,'johncrane.com'
	,'emerson.com'
	,'torquemeters.com'
	,'couplingcorp.com'
	,'rexnord.com'
	,'lovejoy-inc.com'
	,'gmail.com'
	,'ymail.com'
	,'zoho.com'
	,'yandex.mail'
	,'outlook.com'
	,'aol.com'
	,'aim.com'
	,'icloud.com'
	,'mail.com'
	,'inbox.com'	
);
$restricted_names = array(
	'markoneil'
	,'scottwilke'
	,'steveallard'
	,'chriswolford'
	,'codyhodgdon'
	,'chucksakers'
	,'aaronjulian'
	,'bobfindlay'
	,'chrisrackham'
	,'joecorcoran'
	,'clivesleath'
	,'collinmcconnell'
	,'jimanderson'
	,'peterarmstong'
	,'johnbucknell'
	,'karlwerdunn'
	,'jimpaluh'
	,'jimsherred'
	,'douglyle'
	,'samsteiner'
	,'johnfolga'
	,'williammanos'
	,'monicacrowe'
	,'timlashinger'	
);
$cond_comp = str_replace(" ","",$company);
$cond_comp = strtolower ($cond_comp);
$cond_email = str_replace(" ","",$submit_email);
$cond_email = strtolower ($cond_email);
$cond_name = str_replace(" ","",$submit_name);
$cond_name = str_replace("\'","",$cond_name);
$cond_name = strtolower ($cond_name);
$cont .= '[cfdb-save-form-post]';
if (!$access_granted){
	$cont .= '[file_access]';
} elseif ($access_granted){
	if ($dl_cookie != "YES"){
	if ($cond_comp != ''){
	foreach ($restricted_companies as $rest_comp) {
	/*if (strpos($rest_comp,$cond_comp) !== false) {
	    $match_made = true;
	}*/
	if (strpos($cond_comp,$rest_comp) !== false) {
	    $match_made = true;
	}
	}
}
	if ($cond_name != ''){
	foreach ($restricted_names as $rest_name) {
	/*if (strpos($rest_comp,$cond_comp) !== false) {
	    $match_made = true;
	}*/
	if (strpos($cond_name,$rest_name) !== false) {
	    $match_made = true;
	}
	}
}
	if ($cond_email != ''){
	foreach ($restricted_emails as $rest_email) {
	/*if (strpos($rest_comp,$cond_comp) !== false) {
	    $match_made = true;
	}*/
	if (strpos($cond_email,$rest_email) !== false) {
	    $match_made = true;
	}
	}
}
}
if ($match_made == true){$cont .= '<p>We\'re sorry. Your request cannot be processed at this time. Please contact us at <a href="mailto:info@riverhawk.com">info@riverhawk.com</a> for assistance with downloading this manual.</p>';}
if ($match_made == false){
	$cookie_nam = "allowdownload";
	$cookie_val = "YES";
setcookie($cookie_nam, $cookie_val, time()+2592000, "/");


	$cont .= '<script>jQuery( document ).ready(function() {
				jQuery(" .displayed a[href$=\'pdf\']").attr(\'target\',\'_blank\');
			})
		</script>';
$cont .= '<span class="displayed">'. $content.'</span>';
}
if($cond_name != ''||$cond_comp != ''||$cond_email != ''){
if ($match_made){$granted_or_denied = "Denied";} elseif(!$match_made){$granted_or_denied = "Allowed";}
$send_message = "The following user attempted to download the IM-354 manual from Riverhawk.com:\n
Name: ".$submit_name."\n
Email: ".$submit_email."\n
Company: ".$company."\n
\n
Access was " . $granted_or_denied . "\n
This is an informational message only. No action is required";

wp_mail( $send_to, $send_subject, $send_message, $send_headers );
}
}
return do_shortcode ( $cont );
}

add_shortcode ('restricted_files' , 'display_restricted_files');