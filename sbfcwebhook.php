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

  
    case "genQueueNumber":
      $phoneNumber = $request["result"]["parameters"]["phone-number"];
      $trailingNric= $request["result"]["parameters"]["trailing-nric"];
      $patientName = $request["result"]["parameters"]["patient-name"];

      $queueNumber = rand(32,100) ; // random queue number. In practice it is a running number

// Modify the reply message to contain the queue number and phoneNumber.        
      $displayText = "Hi $patientName , your queue number is $queueNumber . We will SMS you at $phoneNumber 15 minutes before your turn is due. ";
    
// Save to a text file queueFile.txt        
       $myfile = fopen("queueFile.txt", "a") or die("Unable to open file!");
      $txt = "$patientName $phoneNumber  $trailingNric  $queueNumber \n";
      fwrite($myfile, $txt);
      fclose($myfile);
    break;  
 
    default:
      $displayText = "unknown action : $action . check api.ai that it is defined";
}

// Prepares a response JSON back to Dialog.ai
$output["speech"] = "$displayText";
$output["displayText"] = "$displayText";
$output["source"] = "sbfcwebhook.php";

ob_end_clean();
echo json_encode($output);

?>
