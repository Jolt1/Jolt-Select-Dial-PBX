<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
$strUser = "user123"; #specify the asterisk manager username you want to login with
$strSecret = "pass123"; #specify the password for the above user


$strHost = "127.0.0.1";
$ext = $_REQUEST['exten'];
$strChannel = "SIP/".$ext;
$strContext = "from-internal";
#specify the amount of time you want to try calling the specified channel before hangin up
$strWaitTime = "30";
#specify the priority you wish to place on making this call
$strPriority = "1";
#specify the maximum amount of retries
$strMaxRetry = "2";
if(isset($_GET['number'])){
$number=strtolower($_GET['number']);}
elseif(isset($_GET['phone'])){
$number=strtolower($_GET['phone']);
}

//Clean up number
$number = str_replace("-","",$number);
$number = str_replace(" ","",$number);
$number = str_replace(") ","",$number);
$number = str_replace(")","",$number);
$number = str_replace("(","",$number);
 $number = preg_replace('/[^(\x20-\x7F)]*/','', $number); 

$number = str_replace(".","",$number); 
$number = str_replace(",","",$number); 
$number = preg_replace("/[^0-9,.]/", "", $number);


//Line 40 adds a 1 before the number if its not there...
//You can remove this if you already added this into your outbound route or you dont need a 1
#if(substr($number,0,1) != 1){$number = "1".$number;}
 
$pos=strpos ($number,"local");
if ($number == null) :
exit() ;
endif ;
if ($pos===false) :
$errno=0 ;
$errstr=0 ;
$strCallerId = "Web Call $number <$number>";
$oSocket = fsockopen ("localhost", 5038, &$errno, &$errstr, 20);
if (!$oSocket) {
echo "$errstr ($errno)<br>\n";
} else {
fputs($oSocket, "Action: login\r\n");
fputs($oSocket, "Events: off\r\n");
fputs($oSocket, "Username: $strUser\r\n");
fputs($oSocket, "Secret: $strSecret\r\n\r\n");
fputs($oSocket, "Action: originate\r\n");
fputs($oSocket, "Channel: $strChannel\r\n");
fputs($oSocket, "WaitTime: $strWaitTime\r\n");
fputs($oSocket, "CallerId: $strCallerId\r\n");
fputs($oSocket, "Exten: $number\r\n");
fputs($oSocket, "Context: $strContext\r\n");
fputs($oSocket, "Priority: $strPriority\r\n\r\n");
fputs($oSocket, "Action: Logoff\r\n\r\n");
sleep(2);
fclose($oSocket);
}
echo "Extension $strChannel should be calling $number." ;
else :
exit() ;
endif ;
?>
 
