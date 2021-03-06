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
            $db = new APIOperation();

            $result = $db->checkProducts();

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Successfully fetched products';
                $response['products'] = $db->getProducts();
            }
            else {
                $response['error'] = true;
                $response['message'] = 'There is no product added yet';
            }
        break;

        case 'addproduct':
            checkParams(array('productName', 'productDescription', 'productPrice', 'productQuantity'));
            $db = new APIOperation();

            $result = $db->addProduct(
                $_POST['productName'],
                $_POST['productDescription'],
                $_POST['productPrice'],
                $_POST['productQuantity']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Successfully added the product';
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Failed to add the product';
            }
        break;

        case 'getproductinfo':
            checkParams(array('productId'));
            $db = new APIOperation();

            $result = $db->checkProductInfo(
                $_POST['productId']
            );

            if ($result) {
                $response['error'] = false;
                $response['message'] = 'Successfully fetched product info data';
                $response['product'] = $db->getProductInfo($_POST['productId']);
            }
            else {
                $response['error'] = true;
                $response['message'] = 'Failed to fetch the product info data';
            }
        break;
    }
} else {
    $response['error'] = true; 
    $response['message'] = 'Invalid API Call';
}

echo json_encode($response);