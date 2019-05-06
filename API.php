<?php 

require_once 'APIOperation.php';
 
function checkParams($params) {
    //assuming all parameters are available 
    $available = true; 
    $missingparams = ""; 
    
    foreach($params as $param){
        if(!isset($_POST[$param]) || strlen($_POST[$param])<=0) {
            $available = false; 
            $missingparams = $missingparams . ", " . $param; 
        }
    }
 
    //if parameters are missing 
    if(!$available) {
        $response = array(); 
        $response['error'] = true; 
        $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
        
        //displaying error
        echo json_encode($response);
        
        //stopping further execution
        die();
    }
}
 
//an array to display response
$response = array();

if(isset($_GET['apicall'])) {

    switch($_GET['apicall']) {

        case 'registeracc':
            checkParams(array('ic', 'fullname', 'email', 'pass'));
            $db = new APIOperation();

            $result = $db->registerAcc(
                $_POST['ic'],
                $_POST['fullname'],
                $_POST['email'],
                $_POST['pass']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Successfully registered new account';
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Failed to register account';
            }
        break;

        case 'loginacc':
            checkParams(array('ic', 'pass'));
            $db = new APIOperation();

            $result = $db->loginAcc(
                $_POST['ic'],
                $_POST['pass']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Log in successful';
                $response['account'] = $db->getAcc($_POST['ic']);
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Log in failed';
            }
        break;
    }
} else {
    $response['error'] = true; 
    $response['message'] = 'Invalid API Call';
}

echo json_encode($response);