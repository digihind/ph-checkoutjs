<?php

ob_start();
error_reporting(E_ALL);
$strNo = rand(1,10000000000);
$strNo1 = rand(1,1000000);

date_default_timezone_set('Asia/Calcutta');

$strCurDate = date('d-m-Y');

$host = isset($_SERVER['HTTPS']) ? 'https' : 'http';

$admin_data = file_get_contents("./worldline_AdminData.json");
$mer_array = json_decode($admin_data, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $_POST['msg'];
    $response_msg = explode("|", $msg);
    echo '<div class="alert alert-info">
        <strong>' . htmlspecialchars($msg) . '</strong>
    </div>';
    echo "<br><br>";

    $res_msg = explode("|", $_POST['msg']);
    $arr_req = array(
        "merchant" => [
            "identifier" => $mer_array['merchantCode']
        ],
        "transaction" => [ 
            "deviceIdentifier" => "S",
            "currency" => $mer_array['currency'],
            "dateTime" => $strCurDate,
            "token" => $res_msg[5],
            "requestType" => "S"
        ]
    );

    $finalJsonReq = json_encode($arr_req);

    function callAPI($method, $url, $finalJsonReq) {
        $curl = curl_init();
        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($finalJsonReq)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($finalJsonReq)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                break;
            default:
                if ($finalJsonReq)
                    $url = sprintf("%s?%s", $url, http_build_query($finalJsonReq));
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
    $dualVerifyData = json_decode($res_result, true);

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2> Online Transaction Result : </h2>
            <table class="table" border="1" align="center" cellpadding="2" cellspacing="0" style="width: 50%;text-align: center;">
                <thead>
                    <tr class="info">
                        <th>Field Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Txn Status</td>
                        <td><?php echo htmlspecialchars($response_msg[0]); ?></td>
                    </tr>
                    <tr>
                        <td>Txn Msg</td>
                        <td><?php echo htmlspecialchars($response_msg[1]); ?></td>
                    </tr>
                    <tr>
                        <td>Txn Err Msg</td>
                        <td><?php echo htmlspecialchars($response_msg[2]); ?></td>
                    </tr>
                    <tr>
                        <td>Clnt Txn Ref</td>
                        <td><?php echo htmlspecialchars($response_msg[3]); ?></td>
                    </tr>
                    <tr>
                        <td>worldline Merchant Bank Cd</td>
                        <td><?php echo htmlspecialchars($response_msg[4]); ?></td>
                    </tr>
                    <tr>
                        <td>worldline Merchant Transaction ID</td>
                        <td><?php echo htmlspecialchars($response_msg[5]); ?></td>
                    </tr>
                    <tr>
                        <td>Txn Amt</td>
                        <td><?php echo htmlspecialchars($response_msg[6]); ?></td>
                    </tr>
                    <tr>
                        <td>Clnt Rqst Meta</td>
                        <td><?php echo htmlspecialchars($response_msg[7]); ?></td>
                    </tr>
                    <tr>
                        <td>worldline Merchant Transaction Time</td>
                        <td><?php echo htmlspecialchars($response_msg[8]); ?></td>
                    </tr>
                    <tr>
                        <td>Bal Amt</td>
                        <td><?php echo htmlspecialchars($response_msg[9]); ?></td>
                    </tr>
                    <tr>
                        <td>Card Id</td>
                        <td><?php echo htmlspecialchars($response_msg[10]); ?></td>
                    </tr>
                    <tr>
                        <td>Alias Name</td>
                        <td><?php echo htmlspecialchars($response_msg[11]); ?></td>
                    </tr>
                    <tr>
                        <td>Bank Transaction ID</td>
                        <td><?php echo htmlspecialchars($response_msg[12]); ?></td>
                    </tr>
                    <tr>
                        <td>Mandate Reg No</td>
                        <td><?php echo htmlspecialchars($response_msg[13]); ?></td>
                    </tr>
                    <tr>
                        <td>Token</td>
                        <td><?php echo htmlspecialchars($response_msg[14]); ?></td>
                    </tr>
                    <tr>
                        <td>Hash</td>
                        <td><?php echo htmlspecialchars($response_msg[15]); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br><br>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2> Dual-Verification : </h2>
            <table class="table" border="1" align="center" cellpadding="2" cellspacing="0" style="width: 50%;text-align: center;">
                <thead>
                    <tr class="info">
                        <th>Field Name</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Merchant Code</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['merchantCode']); ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Transaction Identifier</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['merchantTransactionIdentifier']); ?></td>
                    </tr>
                    <tr>
                        <td>Token Identifier</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['identifier']); ?></td>
                    </tr>
                    <tr>
                        <td>Amount</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['amount']); ?></td>
                    </tr>
                    <tr>
                        <td>Message</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['errorMessage']); ?></td>
                    </tr>
                    <tr>
                        <td>Status Code</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['statusCode']); ?></td>
                    </tr>
                    <tr>
                        <td>Status Message</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['statusMessage']); ?></td>
                    </tr>
                    <tr>
                        <td>Date & Time</td>
                        <td><?php echo htmlspecialchars($dualVerifyData['paymentMethod']['paymentTransaction']['dateTime']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<br>

<table class="table" border="1" cellpadding="2" cellspacing="0" style="width: 50%;text-align: center;">
    <tr>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . $_SERVER['SCRIPT_NAME']; ?>'>BACK TO PAYMENT PAGE</a><br></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/offline_verification.php"; ?>' target="_blank">GO TO OFFLINE-VERIFICATION</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/reconciliation.php"; ?>' target="_blank">GO TO RECONCILIATION</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/refund.php"; ?>' target="_blank">GO TO REFUND</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/emandate-si/mandate-verification.php"; ?>' target="_blank">GO TO MANDATE VERIFICATION</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/emandate-si/transaction-scheduling.php"; ?>' target="_blank">GO TO TRANSACTION SCHEDULING</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/emandate-si/transaction-verification.php"; ?>' target="_blank">GO TO TRANSACTION VERIFICATION</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/emandate-si/mandate-deactivation.php"; ?>' target="_blank">GO TO MANDATE DEACTIVATION</a></td>
        <td><a href='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . "/php-checkoutjs/emandate-si/stop-payment.php"; ?>' target="_blank">GO TO STOP PAYMENT</a></td>
    </tr>
</table>
<br>

<?php
    exit;
}
?>

<html>
<head>
    <title>Payment Checkout</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . '/php-checkoutjs/assets/css/bootstrap.min.css'; ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . '/php-checkoutjs/assets/js/bootstrap.min.js'; ?>"></script>

    <?php if ($mer_array['enableEmandate'] == 1 && $mer_array['enableSIDetailsAtMerchantEnd'] == 1) { ?>
        <style type="text/css">
            .hid {
                display: none;
            }
        </style>
    <?php } else { ?>
        <style type="text/css">
            .hid {
                display: none;
            }
        </style>
    <?php } ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Payment Details</h2>
                <form method="post" id="form">
                    <table class="table table-bordered table-hover">
                        <tr class="info">
                            <th width="40%">Field Name</th>
                            <th width="60%">Field Value</th>
                        </tr>
                        <tr>
                            <td><label>Merchant ID</label></td>
                            <td><input type="text" name="mrctCode" value="<?php echo htmlspecialchars($mer_array['merchantCode'] ?? ''); ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Transaction ID</label></td>
                            <td><input type="text" name="txn_id" value="<?php echo $strNo; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Total Amount</label></td>
                            <td><input type="text" id="myamount" name="amount" value="" /></td>
                        </tr>
                        <tr>
                            <td><label>Scheme Code</label></td>
                            <td><input type="text" name="scheme" value="<?php echo htmlspecialchars($mer_array['merchantSchemeCode'] ?? ''); ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Consumer ID</label></td>
                            <td><input type="text" name="custID" value="<?php echo 'c' . $strNo1; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Mobile Number</label></td>
                            <td><input type="number" name="mobNo" value="" /></td>
                        </tr>
                        <tr>
                            <td><label>Email</label></td>
                            <td><input type="text" name="email" value="" /></td>
                        </tr>
                        <tr>
                            <td><label>Customer Name</label></td>
                            <td><input type="text" name="name" value="" /></td>
                        </tr>
                        <tr>
                            <td><label>Currency</label></td>
                            <td><input type="text" name="currency" value="<?php echo htmlspecialchars($mer_array['currency'] ?? ''); ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>SALT</label></td>
                            <td><input type="text" name="SALT" value="<?php echo htmlspecialchars($mer_array['salt'] ?? ''); ?>" /></td>
                        </tr>
                        <tr>
                            <td><label>Return url</label></td>
                            <td><input type="text" name="returnUrl" value='<?php echo $host . "://" . $_SERVER["HTTP_HOST"] . $_SERVER['SCRIPT_NAME']; ?>' /></td>
                        </tr>

                        <tr class="hid">
                            <td><label>Account Number</label></td>
                            <td><input type="number" name="accNo" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>Account Type</label></td>
                            <td>
                                <select class="form-control" name="accountType">
                                    <option value="">-- SELECT -- </option>
                                    <option value="Saving">Saving</option>
                                    <option value="Current">Current</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="hid">
                            <td><label>Account Name</label></td>
                            <td><input type="text" name="accountName" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>Aadhar Number</label></td>
                            <td><input type="text" name="aadharNumber" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>IFSC Code</label></td>
                            <td><input type="text" name="ifscCode" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>Debit Start Date</label></td>
                            <td><input type="date" name="debitStartDate" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>Debit End Date</label></td>
                            <td><input type="date" name="debitEndDate" value="" /></td>
                        </tr>
                        <tr class="hid">
                            <td><label>maxAmount</label></td>
                            <td><input type="number" id="mymaxAmount" name="maxAmount" value="" /></td>
                        </tr>
                        <?php 
                        if ($mer_array['enableEmandate'] == 1) {
                        ?>
                            <tr class="hid">
                                <td><label>Amount Type</label></td>
                                <td>
                                    <select class="form-control" name="amountType">
                                        <option value="M" selected="selected">Variable</option>
                                        <option value="F">Fixed</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="hid">
                                <td><label>Frequency</label></td>
                                <td>
                                    <select class="form-control" name="frequency">
                                        <option value="ADHO" selected="selected"> As and when presented </option>
                                        <option value="DAIL"> Daily </option>
                                        <option value="WEEK"> Weekly </option>
                                        <option value="MNTH"> Monthly </option>
                                        <option value="BIMN"> Bi- monthly </option>
                                        <option value="QURT"> Quarterly </option>
                                        <option value="MIAN"> Semi annually </option>
                                        <option value="YEAR"> Yearly </option>
                                    </select>
                                </td>
                            </tr>
                        <?php } else { ?>
                            <tr class="hid">
                                <td><label>Amount Type</label></td>
                                <td>
                                    <select class="form-control" name="amountType">
                                        <option value="" selected="selected">SELECT OPTION</option>
                                        <option value="M">Variable</option>
                                        <option value="F">Fixed</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="hid">
                                <td><label>Frequency</label></td>
                                <td>
                                    <select class="form-control" name="frequency">
                                        <option value="" selected="selected">SELECT OPTION</option>
                                        <option value="ADHO"> As and when presented </option>
                                        <option value="DAIL"> Daily </option>
                                        <option value="WEEK"> Weekly </option>
                                        <option value="MNTH"> Monthly </option>
                                        <option value="BIMN"> Bi- monthly </option>
                                        <option value="QURT"> Quarterly </option>
                                        <option value="MIAN"> Semi annually </option>
                                        <option value="YEAR"> Yearly </option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr class="hidden">
                            <td><label>Card Number</label></td>
                            <td><input type="text" name="cardNumber" value="" /></td>
                        </tr>
                        <tr class="hidden">
                            <td><label>Exp Month</label></td>
                            <td><input type="text" name="expMonth" value="" /></td>
                        </tr>
                        <tr class="hidden">
                            <td><label>Exp Year</label></td>
                            <td><input type="text" name="expYear" value="" /></td>
                        </tr>
                        <tr class="hidden">
                            <td><label>Cvv Code</label></td>
                            <td><input type="text" name="cvvCode" value="" /></td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <input class="btn btn-danger" id="btnSubmit" type="submit" name="submit" value="Make Payment" />
                            </td>
                        </tr>
                    </table>
                </form>
                <div id="worldline_embeded_popup"></div>
            </div>
        </div>
    </div>
    <?php 
    if ($mer_array['enableEmandate'] == 1) {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myamount').change(function() {
                var amt = $(this).val();
                var maxamt = amt * 2;
                $('#mymaxAmount').val(maxamt);
            });
        });
    </script>
    <?php } ?>
    <script type="text/javascript" src="https://www.paynimo.com/Paynimocheckout/server/lib/checkout.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#btnSubmit").click(function(e) {

                e.preventDefault();
                var str = $("#form").serialize();

                $.ajax({
                    type: 'POST',
                    cache: false,
                    data: str,
                    url: "payment_request.php",
                    success: function(response) {
                        var obj = JSON.parse(response);

                        function handleResponse(res) {
                            if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0300') {
                                // success block
                            } else if (typeof res != 'undefined' && typeof res.paymentMethod != 'undefined' && typeof res.paymentMethod.paymentTransaction != 'undefined' && typeof res.paymentMethod.paymentTransaction.statusCode != 'undefined' && res.paymentMethod.paymentTransaction.statusCode == '0398') {
                                // initiated block
                            } else {
                                // error block
                            }
                        };

                        var configJson = {
                            'tarCall': false,
                            'features': {
                                'showPGResponseMsg': true,
                                'enableNewWindowFlow': <?php echo ($mer_array['enableNewWindowFlow'] == 1) ? 'true' : 'false'; ?>, //for hybrid applications please disable this by passing false
                                'enableAbortResponse': true,
                                'enableExpressPay': <?php echo ($mer_array['enableExpressPay'] == 1) ? 'true' : 'false'; ?>, //if unique customer identifier is passed then save card functionality for end  end customer
                                'enableInstrumentDeRegistration': <?php echo ($mer_array['enableInstrumentDeRegistration'] == 1) ? 'true' : 'false'; ?>, //if unique customer identifier is passed then option to delete saved card by end customer
                                'enableMerTxnDetails': true,
                                'siDetailsAtMerchantEnd': <?php echo ($mer_array['enableSIDetailsAtMerchantEnd'] == 1) ? 'true' : 'false'; ?>,
                                'enableSI': <?php echo ($mer_array['enableEmandate'] == 1) ? 'true' : 'false'; ?>,
                                'hideSIDetails': <?php echo ($mer_array['hideSIConfirmation'] == 1) ? 'true' : 'false'; ?>,
                                'enableDebitDay': <?php echo ($mer_array['enableDebitDay'] == 1) ? 'true' : 'false'; ?>,
                                'expandSIDetails': <?php echo ($mer_array['expandSIDetails'] == 1) ? 'true' : 'false'; ?>,
                                'enableTxnForNonSICards': <?php echo ($mer_array['enableTxnForNonSICards'] == 1) ? 'true' : 'false'; ?>,
                                'showSIConfirmation': <?php echo ($mer_array['showSIConfirmation'] == 1) ? 'true' : 'false'; ?>,
                                'showSIResponseMsg': <?php echo ($mer_array['showSIResponseMsg'] == 1) ? 'true' : 'false'; ?>,
                            },

                            'consumerData': {
                                'deviceId': 'WEBSH2',
                                'token': obj['hash'],
                                'returnUrl': obj['data'][12],
                                'responseHandler': handleResponse,
                                'paymentMode': '<?php echo htmlspecialchars($mer_array['paymentMode'] ?? ''); ?>',
                                'checkoutElement': '<?php echo ($mer_array['embedPaymentGatewayOnPage'] == "1") ? "#worldline_embeded_popup" : ""; ?>',
                                'merchantLogoUrl': '<?php echo htmlspecialchars($mer_array['logoURL'] ?? ''); ?>', //provided merchant logo will be displayed
                                'merchantId': obj['data'][0],
                                'currency': obj['data'][15],
                                'consumerId': obj['data'][8], //Your unique consumer identifier to register a eMandate/eNACH
                                'consumerMobileNo': obj['data'][9],
                                'consumerEmailId': obj['data'][10],
                                'txnId': obj['data'][1], //Unique merchant transaction ID
                                'items': [{
                                    'itemId': obj['data'][14],
                                    'amount': obj['data'][2],
                                    'comAmt': '0'
                                }],
                                'cartDescription': '}{custname:' + obj['data'][13],
                                'merRefDetails': [{
                                    "name": "Txn. Ref. ID",
                                    "value": obj['data'][1]
                                }],
                                'customStyle': {
                                    'PRIMARY_COLOR_CODE': '<?php echo htmlspecialchars($mer_array['primaryColor'] ?? ''); ?>', //merchant primary color code
                                    'SECONDARY_COLOR_CODE': '<?php echo htmlspecialchars($mer_array['secondaryColor'] ?? ''); ?>', //provide merchant's suitable color code
                                    'BUTTON_COLOR_CODE_1': '<?php echo htmlspecialchars($mer_array['buttonColor1'] ?? ''); ?>', //merchant's button background color code
                                    'BUTTON_COLOR_CODE_2': '<?php echo htmlspecialchars($mer_array['buttonColor2'] ?? ''); ?>' //provide merchant's suitable color code for button text
                                },
                                'accountNo': obj['data'][11], //Pass this if accountNo is captured at merchant side for eMandate/eNACH
                                'accountHolderName': obj['data'][16], //Pass this if accountHolderName is captured at merchant side for ICICI eMandate & eNACH registration this is mandatory field, if not passed from merchant Customer need to enter in Checkout UI.
                                'ifscCode': obj['data'][17], //Pass this if ifscCode is captured at merchant side.
                                'accountType': obj['data'][18], //Required for eNACH registration this is mandatory field
                                'debitStartDate': obj['data'][3],
                                'debitEndDate': obj['data'][4],
                                'maxAmount': obj['data'][5],
                                'amountType': obj['data'][6],
                                'frequency': obj['data'][7] //  Available options DAIL, WEEK, MNTH, QURT, MIAN, YEAR, BIMN and ADHO
                            }
                        };

                        $.pnCheckout(configJson);
                        if (configJson.features.enableNewWindowFlow) {
                            pnCheckoutShared.openNewWindow();
                        }
                    }
                });

            });
        });
    </script>
</body>
</html>
