<?php
function validateReceipt($receipt, $endpoint) {

    $postData = json_encode(
        array('receipt-data' => $receipt)
    );

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
 
    $response = curl_exec($ch);
    $errno    = curl_errno($ch);
    $errmsg   = curl_error($ch);
    curl_close($ch);

    if ($errno != 0) {
        throw new Exception($errmsg, $errno);
    }

    $data = json_decode($response);

    if (!is_object($data)) {
        throw new Exception('Invalid response data');
    }
 
    if (!isset($data->status) || $data->status != 0) {
        throw new Exception('Invalid receipt. Status code: ' . $data->status);
    }

    return array(
        'quantity'       =>  $data->receipt->quantity,
        'product_id'     =>  $data->receipt->product_id,
        'transaction_id' =>  $data->receipt->transaction_id,
        'purchase_date'  =>  $data->receipt->purchase_date,
        'app_item_id'    =>  $data->receipt->app_item_id,
        'bid'            =>  $data->receipt->bid,
        'bvrs'           =>  $data->receipt->bvrs
    );
}

$receipt   = $_GET['receipt'];
$isSandbox = (bool) $_GET['sandbox'];
 
if ($isSandbox) {
    $endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';
    print "Environment: Sandbox (use 'sandbox' URL argument to toggle)<br />";
}
else {
    $endpoint = 'https://buy.itunes.apple.com/verifyReceipt';
    print "Environment: Production (use 'sandbox' URL argument to toggle)<br />";
}

try {
    if(strpos($receipt,'{') !== false) {
        $receipt = base64_encode($receipt);
    }
    $info = validateReceipt($receipt, $endpoint);
    echo 'Success';
}
catch (Exception $ex) {
    echo $ex->getMessage().'<br />';
}
?>
