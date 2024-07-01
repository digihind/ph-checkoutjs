<?php

$admin_data = file_get_contents("./worldline_AdminData.json");
$mer_array = json_decode($admin_data, true);

$response = $_POST;

$datastring = $response['merchantCode'] . "|" . $response['merchantTransactionIdentifier'] . "|" . $response['amount'] . "|" . $response['transactionIdentifier'] . "|" . $response['dateTime'] . "|" . $response['merchantTransactionRequestType'] . "|" . $mer_array['salt'];

$hashed = hash('sha512', $datastring);

if ($hashed == $response['hash']) {
    $statusCode = $response['statusCode'];
    if ($statusCode == "0300") {
        echo "s2s Transaction Successful.";
    } elseif ($statusCode == "0398") {
        echo "s2s Transaction Initiated.";
    } else {
        echo "s2s Transaction Failed.";
    }
} else {
    echo "Invalid response hash";
}
?>
