# USSD
<?php
require_once('lib/nusoap.php');

$cell_number = $_GET['MSISDN'];
$session_id = $_GET['SESSION_ID'];
$service_code = $_GET['SERVICE_CODE'];
$ussd_string = $_GET['USSD_STRING'];

//set default level to zero
$level = 0;
$ussd_string = str_replace("#","*",$ussd_string);
$ussd_string_exploded = explode("*",$ussd_string);
$ussd_string_exploded2 = array_shift($ussd_string_exploded);
//get level id from ussd_string reply
$level = count($ussd_string_exploded);

$phone = $cell_number;

if($level == 0)
{
	display_menu();
}


if($level > 0)
{
	switch($ussd_string_exploded[0])
	{
		case 1:
			balanceEnquiry($ussd_string_exploded,$phone);
		break;
		
		case 2:
			viewStatement($ussd_string_exploded,$phone);
		break;

		case 3:
			Transfer($ussd_string_exploded,$phone);
		break;

		case 4:
			Withdraw($ussd_string_exploded,$phone);
		break;

		case 5:
			Deposit($ussd_string_exploded,$phone);
		break;
	}
}	

function display_menu()
{
$ussd_text = "1. balanceEnquiry \n2. viewStatement \n3. Transfer \n4. Withdraw \n5. Deposit";
ussd_proceed($ussd_text);
}

function balanceEnquiry($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter your pin:";
		ussd_proceed($ussd_text);
	}
	
	if(count($details) == 2)
	{
		$param = array('pin'=>$details[1],'phone'=>$phone);
		$client = new nusoap_client('http://localhost/soaptest/serverBank.php');
		$response = $client->call('balanceEnquiry', $param);
		ussd_stop($response);
	}
}

function viewStatement($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter  your pin number:";
		ussd_proceed($ussd_text);
	}
	
	if(count($details) == 2)
	{
		$param = array('pin'=>$details[1],'phone'=>$phone);
		$client = new nusoap_client('http://localhost/soaptest/serverBank.php');
		$response = $client->call('viewStatements', $param);
		ussd_stop($response);
	}
}

function Transfer($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter pin Number:";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 2)
	{
		$ussd_text = "Please enter the account number of he receipient";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 3)
	{
		$ussd_text = "Please enter the amount to transfer";
		ussd_proceed($ussd_text);
	}

	if(count($details) == 4)
	{
		$param = array('pin'=>$details[1],'phone'=>$phone,'accNo'=>$details[2],'Amt'=>$details[3]);
		$client = new nusoap_client('http://localhost/soaptest/serverBank.php');
		$response = $client->call('Transfer', $param);
		ussd_stop($response);
	}
}
function Withdraw($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter  your pin number:";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 2)
	{
		$ussd_text = " enter the your account Number";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 3)
	{
		$ussd_text = "Please enter the amount to withdraw";
		ussd_proceed($ussd_text);
	}
	
	if(count($details) == 4)
	{
		$param = array('pin'=>$details[1],'phone'=>$phone,'accNo'=>$details[2],'Amt'=>$details[3]);
		$client = new nusoap_client('http://localhost/soaptest/serverBank.php');
		$response = $client->call('Withdraw', $param);
		ussd_stop($response);
	}
}

function Deposit($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter  your pin number:";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 2)
	{
		$ussd_text = " enter the your account Number";
		ussd_proceed($ussd_text);
	}
	
	if(count($details) == 3)
	{
		$ussd_text = "Please enter the amount to Deposit";
		ussd_proceed($ussd_text);
	}
	if(count($details) == 4)
	{
		$param = array('pin'=>$details[1],'phone'=>$phone,'accNo'=>$details[2],'Amt'=>$details[3]);
		$client = new nusoap_client('http://localhost/soaptest/serverBank.php');
		$response = $client->call('Deposit', $param);
		ussd_stop($response);
	}
}
function ussd_proceed($ussd_text)
{
echo "CON $ussd_text";
exit(0);
}

function ussd_stop($ussd_text)
{
echo "END $ussd_text";
exit(0);
}

?>
