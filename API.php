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
            checkParams(array('userName', 'email', 'password'));
            $db = new APIOperation();

            $result = $db->registerAcc(
                $_POST['userName'],
                $_POST['email'],
                $_POST['password']
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
            checkParams(array('userName', 'password'));
            $db = new APIOperation();

            $result = $db->loginAcc(
                $_POST['userName'],
                $_POST['password']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Log in successful';
                $response['account'] = $db->getAcc($_POST['userName']);
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Log in failed';
            }
        break;

        case 'getproducts':
            checkParams(array('minQuantity'));
            $db = new APIOperation();

            $result = $db->getProducts(
                $_POST['minQuantity']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Successfully fetched products';
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Failed to fetch products';
            }
        break;
    }
} else {
    $response['error'] = true; 
    $response['message'] = 'Invalid API Call';
}

echo json_encode($response);