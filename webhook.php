<?php
header('Content-Type: application/json');
ob_start();
$json = file_get_contents('php://input'); 
$request = json_decode($json, true);

// Picks up the Action Name
$action = $request["result"]["action"];

// Case statements to process the actions
switch ($action) {
    case "enquiryOpeningHrs":
      // This response will varied between depending on weekdays or weekend.
      // For the purpose of demonstrating, a random number (1 to 7) is used to
      // determine the day of the week
      
       $dayOfWeek =rand(1, 7);
       if ($dayOfWeek >= 1 &&  $dayOfWeek <= 5) 
           {$displayText = "We are open today from 9 am to 12 pm , and from 2:00 pm to 7 pm. " ;}
       elseif ( $dayOfWeek >= 6 &&  $dayOfWeek <= 7 ) 
           {$displayText = "We are open on weekends from 9 am to 12 pm. " ;}
       else 
           {$displayText = "!!!!!!!!!"  ;} 
    break;
       
    case "save_the_order":
//Picks up the parameter send from Dialog.ai        
      $phoneNumber = $request["result"]["parameters"]["phone-number"];
      $trailingNric= $request["result"]["parameters"]["trailing-nric"];
    // Save to somewhere
    // Generate queue number
    
      
      $serve_time = rand(2,10) ; // random serving time
// TODO: add business logic such as to check/save/send to POS    
// 
      $displayText = "Your order for $qty cup/cups of $temp $drink ";
      $displayText .= " will be served in $serve_time mins";
    break;  
 
    default:
      $displayText = "unknown action : $action . check api.ai that it is defined";
}

// Prepares a response JSON back to Dialog.ai
$output["speech"] = "$displayText";
$output["displayText"] = "$displayText";
$output["source"] = "labwebhook.php";

ob_end_clean();
echo json_encode($output);

?>
