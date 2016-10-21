<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if($_REQUEST['dispo'] == 'DNCC' || $_REQUEST['dispo'] == 'DNC' || $_REQUEST['dispo'] == 'XFER' || $_REQUEST['dispo'] == 'TSHU' || $_REQUEST['dispo'] == 'TNC'){
    $servers = array('79','118','120','87','78','38','115','119');
    $status       = $_REQUEST['dispo'];
    $phone_number = $_REQUEST['phone_number'];
    $campaign     = $_REQUEST['campaign_id'];
    if($campaign == 2000 || $campaign == '2000'){
        foreach ($servers as $key) {
            if($_REQUEST['dispo'] == 'DNCC' || $_REQUEST['dispo'] == 'DNC')
            {
                $q = "UPDATE vicidial_list LEFT JOIN vicidial_lists ON vicidial_lists.list_id = vicidial_list.list_id SET vicidial_list.status = 'DNCSYN' WHERE vicidial_list.phone_number = '{$phone_number}' AND vicidial_lists.campaign_id = '{$campaign}';";
            }
            else
            {
                $q = "UPDATE vicidial_list LEFT JOIN vicidial_lists ON vicidial_lists.list_id = vicidial_list.list_id SET vicidial_list.status = 'XFESYN' WHERE vicidial_list.phone_number = '{$phone_number}' AND vicidial_lists.campaign_id = '{$campaign}';";
            }
            q_num($q,$key);
            if($key == '38' || $key == '78'){
                $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','3000')";
                q_num($q,$key);  
            }             
            else if($key == '118'){
                $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','4000')";
                q_num($q,$key);  
            }              
            else if($key == '87' || $key == '119'){
                $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','5000')";
                q_num($q,$key);  
            }
            else if($key == '79' || $key == '120' || $key == '96' || $key == '115'){
                $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','2000')";
                q_num($q,$key);  
            }             
            else{
                $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','".$campaign."')";
                q_num($q,$key);  
            }
           
        }
        /*if($_REQUEST['dispo'] == 'DNCC' || $_REQUEST['dispo'] == 'DNC')
        {
            $q = "UPDATE vicidial_list SET status = 'DNCSYN' WHERE phone_number = '{$phone_number}'";
        }
        else
        {
            $q = "UPDATE vicidial_list SET status = 'XFESYN' WHERE phone_number = '{$phone_number}'";
        }
        q_num($q,'localhost');*/
        $q = "INSERT INTO vicidial_campaign_dnc (phone_number,campaign_id) VALUES ('".$phone_number."','".$campaign."')";
        q_num($q,'localhost');
        echo "<script>window.close();</script>";  
    }
}



function q_num($q,$serverNum)
{
        define("DBNAME","vicidial");
        define("DBUSER","root");
        define("DBPASS","ZXc21LKt");

        if(empty($serverNum)) {
                return "Server is not set.";
        }
        if($serverNum == 'localhost'){
            $server = $serverNum;
        }
        else{
            $server = "av{$serverNum}.avatar.tech";
        }
            
        try {
                $dbconn = new PDO("mysql:dbname=" . DBNAME . ";host=" . $server, DBUSER, DBPASS);
                $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sth = $dbconn->prepare($q);
                $sth->execute();
/*                if($serverNum == 'localhost'){
                    $rows = $sth->fetchAll();
                    return $rows;
                    $fp = fopen('dispo_log.txt', 'a') or die("Could not open log file.");
                    fwrite($fp, "DNC Server:" . $serverNum .  "\n\nQuery:" . $q . "\n");
                    fclose($fp);
                }
                else{
                    $fp = fopen('dispo_log.txt', 'a') or die("Could not open log file.");
                    fwrite($fp, "DNC Server:" . $serverNum .  "\n\nQuery:" . $q . "\n");
                    fclose($fp);
                    return 0;
                }*/
                $fp = fopen('dispo_log.txt', 'a') or die("Could not open log file.");
                    fwrite($fp, "DNC Server:" . $serverNum .  "\n\nQuery:" . $q . "\n");
                    fclose($fp);

        } catch(Exception $e) {
                $fp = fopen('dispo_log.txt', 'a') or die("Could not open log file.");
                fwrite($fp, "DNC Server:" . $serverNum .  "\n\nError:" . $e . "\n");
                fclose($fp);
                return "Error";
        }
}
?>