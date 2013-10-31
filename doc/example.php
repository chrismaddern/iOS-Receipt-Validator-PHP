<?php

include __DIR__ . '/../itunesReceiptValidator.php';

if (isset($_GET['receipt'])) {
    $receipt  = $_GET['receipt'];
}
else {
    print 'No receipt to validate. Exiting.<br />';
    return;
}

$endpoint = isset($_GET['sandbox']) ? itunesReceiptValidator::SANDBOX_URL : itunesReceiptValidator::PRODUCTION_URL;

try {
    $rv = new itunesReceiptValidator($endpoint, 'fake_receipt');

    print 'Environment: ' .
      ($rv->getEndpoint() === itunesReceiptValidator::SANDBOX_URL) ? 'Sandbox' : 'Production' .
      '<br />';

    $info = $rv->validateReceipt();
    echo 'Success';
    var_dump($info);
}
catch (Exception $ex) {
    echo $ex->getMessage() . '<br />';
}
