<?php

$admin_data = file_get_contents("../worldline_AdminData.json");
$mer_array = json_decode($admin_data, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arr_req = array(
        "merchant" => [
            "identifier" => $mer_array['merchantCode']
        ],
        "transaction" => [
            "deviceIdentifier" => "S",
            "currency" => $mer_array['currency'],
            "identifier" => $_POST['txn_id'],
            "dateTime" => date("d-m-Y"),
            "token" => $_POST['txn_id'],
            "requestType" => "S"
        ]
    );

    $finalJsonReq = json_encode($arr_req);

    function callAPI($method, $url, $finalJsonReq)
    {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($finalJsonReq) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                }
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($finalJsonReq) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                }
                break;
            default:
                if ($finalJsonReq) {
                    $url = sprintf("%s?%s", $url, http_build_query($finalJsonReq));
                }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        if (!$result) {
            die("Connection Failure !! Try after some time.");
        }
        curl_close($curl);
        return $result;
    }

    $method = 'POST';
    $url = "https://www.paynimo.com/api/paynimoV2.req";
    $res_result = callAPI($method, $url, $finalJsonReq);
    $mandateVerificationData = json_decode($res_result, true);

    echo '<div class="alert alert-info">
        <strong>Merchant Transaction Identifier:</strong> ' . htmlspecialchars($mandateVerificationData['merchantTransactionIdentifier']) . '
    </div>';
}
?>
<html>
<head>
    <title>Mandate Verification</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Mandate Verification</h2>
                <form method="post">
                    <table class="table table-bordered table-hover">
                        <tr class="info">
                            <th width="40%">Field Name</th>
                            <th width="60%">Field Value</th>
                        </tr>
                        <tr>
                            <td><label>Transaction ID</label></td>
                            <td><input type="text" name="txn_id" value="" /></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input class="btn btn-danger" type="submit" name="submit" value="Submit" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
