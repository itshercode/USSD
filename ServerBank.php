<?php
include 'connectBank.php';

function balanceEnquiry($pin,$phone){

$balance = "Select * from accountDetails where mobile = '$phone'";
 
$result = mysql_query($balance);

	$response = "Available cash:";
	$balanceObject = mysql_fetch_object($result);

	$response = $response." Balance: ".$balanceObject->balance; 

	return $response;

}

function viewStatements($pin,$phone){


$statement = "Select * from Activities ac, accountDetails acc where ac.accountNo = acc.accountNo and mobile= '$phone'";

$result = mysql_query($statement); 

	$response = "Activities Done for the Account:"; 
    
	while($statementObject = mysql_fetch_object($result)){	
              

$response = $response."Account Number: ".$statementObject->accountNo." |Activity Type:".$statementObject->activityType." |Amount:".$statementObject->Amt." |Activity DateTime:".$statementObject->activityDateTime;


	return $response;

	}
	}

function Transfer($pin,$phone,$accNo,$amt){

$sql= "SELECT * FROM  accountDetails where mobile=$phone";
$result = mysql_query($sql); 
while($row = mysql_fetch_object($result)){
	$accNo1 = $row->accountNo;
}
$Type = "Funds Transfer";

$insertbal1 = "insert into Activities(accountNo,pin,Amt,activityType) values ('$accNo1','$pin',$amt *-1,'$Type')";

$sqlupdate1="UPDATE accountDetails set balance = balance-$amt where mobile='$phone'";

$insertbal2 = "insert into Activities(accountNo,pin,Amt,activityType) values('$accNo','$pin','$amt','$Type')";

$sqlupdate2="UPDATE accountDetails set balance = balance+$amt where mobile='$phone'";

	if((mysql_query($insertbal1)) && (mysql_query($insertbal2))){

		mysql_query($sqlupdate1);

		mysql_query($sqlupdate2);
			
		return "Your transfer has been updated with ".$amt."  successfully.";
	}
	else
	{
		return "Sorry, Account cant be updated";
	}
}

function Withdraw($pin,$phone,$accNo,$amt){

$sql= "SELECT * FROM  accountDetails where mobile=$phone";
$result = mysql_query($sql); 
$Type = "withdraw";
$insertbal = "insert into Activities(accountNo,pin,Amt,activityType) values ('$accNo','$pin',$amt *-1,'$Type')";

$sqlupdate = "UPDATE accountDetails set balance = balance-$amt where mobile='$phone'";
if(mysql_query($insertbal)){
       mysql_query($sqlupdate);

 return "You have withdrawn. ".$amt." from your account";
 }
 else
	{
		return "Sorry, Cant withdraw";
	
 }
}
function Deposit($pin,$phone,$accNo,$amt){

$sql= "SELECT * FROM  accountDetails where mobile=$phone";
$result = mysql_query($sql); 
$Type = "Deposit";
$insertbal = "insert into Activities(accountNo,pin,Amt,activityType) values ('$accNo','$pin',$amt,'$Type')";
$sqlupdate = "UPDATE accountDetails set balance = balance+$amt where mobile='$phone'";
	if(mysql_query($insertbal)){
       mysql_query($sqlupdate);

return "You have Deposited. ".$amt." to your account";
}
else
	{
		return "Sorry, Cant Deposit";
	
}
}

require_once('lib/nusoap.php');
$server = new nusoap_server();
$server->configureWSDL('mbanking', 'urn:mbanking');
$server->register("balanceEnquiry",
                array('pin'=>'xsd:string','phone'=>'xsd:string'),
                array('output' => 'xsd:string'),
                'urn:mbanking',
                'urn:mbanking#balanceEnquiry');

$server->register("viewStatements",
                array('pin'=>'xsd:string','phone'=>'xsd:string'),
                array('output' => 'xsd:string'),
                'urn:mbanking',
                'urn:mbanking#viewStatements');

$server->register("Transfer",
                array('pin' => 'xsd:string','phone'=>'xsd:string','accNo'=>'xsd:string','Amt'=>'xsd:string'),
                array('output' => 'xsd:string'),
                'urn:mbanking',
                'urn:mbanking#Transfer');

$server->register("Withdraw",
                array('pin' => 'xsd:string','phone'=>'xsd:string','accNo'=>'xsd:string','Amt'=>'xsd:string'),
                array('output' => 'xsd:string'),
                'urn:mbanking',
                'urn:mbanking#Withdraw');
$server->register("Deposit",
                array('pin' => 'xsd:string','phone'=>'xsd:string','accNo'=>'xsd:string','Amt'=>'xsd:string'),
                array('output' => 'xsd:string'),
                'urn:mbanking',
                'urn:mbanking#Deposit');



$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)
                      ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
