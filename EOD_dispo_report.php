<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
require '/srv/www/htdocs/lib.php';
$link = mysql_connect('localhost', 'root', 'ZXc21LKt');
mysql_select_db('vicidial', $link);

//$date = "2016-09-27";
$date = date('Y-m-d',strtotime('yesterday'));

$q = "SELECT vicidial_list.modify_date AS DATE, vicidial_list.phone_number AS phone, vicidial_list.address3 AS CompanyName, vicidial_list.last_name AS LastName, vicidial_list.first_name AS FirstName, vicidial_list.address1 AS street, vicidial_list.city AS City, vicidial_list.state AS State, vicidial_list.postal_code AS ZipCode 
		FROM vicidial_list
		WHERE vicidial_list.modify_date LIKE  '{$date}%' 
		AND vicidial_list.status IN ('NI',  'LB',  'DMNI',  'DEAD')";

$rslt = mysql_query($q,$link);

$header = '"'."DATE".'","'."phone".'","'."CompanyName".'","'."LastName".'","'."FirstName".'","'."street".'","'."City".'","'."State".'","'."ZipCode".'","'." ".'"'."\n";

$table_pd108="";
while($row = mysql_fetch_row($rslt))
{
$rowValue = '"'."{$row[0]}".'","'."{$row[1]}".'","'."{$row[2]}".'","'."{$row[3]}".'","'."{$row[4]}".'","'."{$row[5]}".'","'."{$row[6]}".'","'."{$row[7]}".'","'."{$row[8]}".'"';

$table_pd108 .=<<<EOH
{$rowValue}\n
EOH;
}

$table_pd108 = $header . $table_pd108;
//echo $table_av109;
$name = "dispo_report_".$date.".csv";
$filename = "/srv/www/htdocs/reports/eod_dispo_report/" . $name;

$fp = fopen($filename, 'w');
$boolFile = fwrite($fp,$table_pd108);
fclose($filename);

if($boolFile > 0){
	$from = "helpdesk@voicelesstechnologies.com";
	//$to = "jiezaa@voicelesstechnologies.com";
	$to = "jiezaa@voicelesstechnologies.com,raudyu@avatar.com.ph,jrquinones1120@gmail.com";
	

	$subject = "PD 108 - EOD Dispo Report " . $date;	
	$headers = "From: " . $from  . "\r\n";
	$headers .= "Reply-To: ". $from  . "\r\n";
    $content = file_get_contents($filename);
    $content = chunk_split(base64_encode($content));

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;

    // main header (multipart mandatory)
    $headers = "From: " . $from . $eol;
    $headers .= "Reply-To: ". $from  . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
    $headers .= "This is a MIME encoded message." . $eol;

    // message
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
    $headers .= "Content-Transfer-Encoding: 8bit" . $eol;
    $headers .= $message . $eol;

    // attachment
    $headers .= "--" . $separator . $eol;
    $headers .= "Content-Type: application/octet-stream; name=\"" . $name  . "\"" . $eol;
    $headers .= "Content-Transfer-Encoding: base64" . $eol;
    $headers .= "Content-Disposition: attachment" . $eol;
    $headers .= $content . $eol;
    $headers .= "--" . $separator . "--";

    $message = 'Attached file for EOD.';


	$checkMail = mail($to,$subject,$message,$headers);
	if($checkMail){
		echo "Sent";
	}
	else{
		echo "Send Failed";
	}
}

?>