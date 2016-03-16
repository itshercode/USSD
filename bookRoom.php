
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
			register($ussd_string_exploded,$phone);
		break;
		
		case 2:
			viewRooms($ussd_string_exploded,$phone);
		break;

		case 3:
			bookRoom($ussd_string_exploded,$phone);
		break;
	}
}	

function display_menu()
{
$ussd_text = "1. Register\n2. View rooms\n3. Book a room";
ussd_proceed($ussd_text);
}

function register($details,$phone)
{
	if(count($details) == 1)
	{
		$ussd_text = "Please enter your name";
		ussd_proceed($ussd_text);
	}

	if(count($details) == 2)
	{
		$param = array('name'=>$details[1],'phone'=>$phone);
		$client = new nusoap_client('http://127.0.0.1/soaptest/server.php');
		$response = $client->call('registerCustomer', $param);
		ussd_stop("Thank you. Your registration is being processed.");
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
