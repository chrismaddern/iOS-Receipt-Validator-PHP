<?php


// Custom exceptions
abstract class itunesReceiptException extends Exception { }
class itunesReceiptInvalidException extends itunesReceiptException { }
class itunesReceiptMalformedException extends itunesReceiptException { }

// itunes receipt validator
class itunesReceiptValidator {

    const SANDBOX_URL    = 'https://sandbox.itunes.apple.com/verifyReceipt';
    const PRODUCTION_URL = 'https://buy.itunes.apple.com/verifyReceipt';

    function __construct($endpoint, $receipt = NULL, $password = NULL) {
        $this->setEndPoint($endpoint);

        if ($receipt) {
            $this->setReceipt($receipt);
        }
        if ($password) {
            $this->setPassword($password);
        }
    }

    function getReceipt() {
        return $this->receipt;
    }

    function setReceipt($receipt) {
        if (is_string($receipt) && strpos($receipt, '{') !== false) {
            $this->receipt = base64_encode($receipt);
        } else {
            $this->receipt = $receipt;
        }
    }

    function getPassword(){
        return $this->password;
    }

    function setPassword($password){
        $this->password = $password;
    }

    function getEndpoint() {
        return $this->endpoint;
    }

    function setEndPoint($endpoint) {
        $this->endpoint = $endpoint;
    }

    function validateReceipt($returnAll = false) {
        $response = $this->makeRequest();

        $decoded_response = $this->decodeResponse($response);

        if (!isset($decoded_response->status) || $decoded_response->status != 0) {
            throw new itunesReceiptInvalidException (
                'Invalid receipt. Status code: ' . (!empty($decoded_response->status) ?
                    $decoded_response->status : 'N/A')
            );
        }

        if (!is_object($decoded_response)) {
            throw new Exception('Invalid response data');
        }
		return $returnAll?$decoded_response:$decoded_response->receipt;
    }

    private function encodeRequest() {
        $request_data = array('receipt-data' => $this->getReceipt());

        if ($this->getPassword()) {
            $request_data['password'] = $this->getPassword();
        }

        return json_encode($request_data);
    }

    private function decodeResponse($response) {
        return json_decode($response);
    }

    private function makeRequest() {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->encodeRequest());

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);

        if ($errno != 0) {
            throw new Exception($errmsg, $errno);
        }

        return $response;
    }
}
