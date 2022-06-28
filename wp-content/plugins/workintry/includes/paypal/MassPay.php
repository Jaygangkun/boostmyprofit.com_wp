<?php
// Include required library files.
if( !function_exists('pay_to_my_clinets') ){
	function pay_to_my_clinets($purpose, $username, $password, $signature, $users){		
// Create PayPal object.
$PayPalConfig = array(
	'Sandbox' => $purpose,
	'APIUsername' => $username,
	'APIPassword' => $password,
	'APISignature' => $signature, 
	'PrintHeaders' => '', 
	'LogResults' => '',
	'LogPath' => '',
);

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

// Prepare request arrays
$MPFields = array(
	'emailsubject' => 'You got a pyament!', 						// The subject line of the email that PayPal sends when the transaction is completed.  Same for all recipients.  255 char max.
	'currencycode' => 'USD', 						// Three-letter currency code.
	'receivertype' => 'EmailAddress' 						// Indicates how you identify the recipients of payments in this call to MassPay.  Must be EmailAddress or UserID
);

// Typically, you'll loop through some sort of records to build your MPItems array. 		
if( empty( $users[0]['l_email'] ) || empty( $users[0]['l_amt'] ) || empty( $users[0]['l_uniqueid'] ) ){
	return 'Error';
}
$item = array(
	'l_email' => $users[0]['l_email'], 							// Required.  Email address of recipient.  You must specify either L_EMAIL or L_RECEIVERID but you must not mix the two.
	'l_receiverid' => '', 						// Required.  ReceiverID of recipient.  Must specify this or email address, but not both.
	'l_amt' => $users[0]['l_amt'], 								// Required.  Payment amount.
	'l_uniqueid' => $users[0]['l_uniqueid'], 						// Transaction-specific ID number for tracking in an accounting system.
	'l_note' => 'hi, you are you ?' 								// Custom note for each recipient.
);		

//You can add more like $MPItems = array($item, $item2, $item3);
$MPItems = array($item);
$PayPalRequestData = array('MPFields'=>$MPFields, 'MPItems' => $MPItems);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->MassPay($PayPalRequestData);
return $PayPalResult['ACK'];
// Write the contents of the response array to the screen for demo purposes.
}
}