<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Complete Response</title>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/bootswatch.min.css">
    <link rel="stylesheet" href="css/studentportal.css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <style>
        .alert {
            display: none !important;
        }

        b {
            font-weight: 400 !important;
        }

        .green {
            color: green !important;
        }

        .red {
            color: red !important;
        }

        .print{
            display: none;
        }

        @media print {
            btn, .not-print {
                display: none !important;
            }

            .print{
                display: block !important;
            }
        }
    </style>
</head>
<body>

<?php include 'au.com.gateway.client/GatewayClient.php'; ?>
<?php include 'au.com.gateway.client.config/ClientConfig.php'; ?>
<?php include 'au.com.gateway.client.component/RequestHeader.php'; ?>
<?php include 'au.com.gateway.client.component/CreditCard.php'; ?>
<?php include 'au.com.gateway.client.component/TransactionAmount.php'; ?>
<?php include 'au.com.gateway.client.component/Redirect.php'; ?>
<?php include 'au.com.gateway.client.facade/BaseFacade.php'; ?>
<?php include 'au.com.gateway.client.facade/Payment.php'; ?>
<?php include 'au.com.gateway.client.root/PaycorpRequest.php'; ?>
<?php include 'au.com.gateway.client.root/PaycorpResponse.php'; ?>
<?php include 'au.com.gateway.client.payment/PaymentCompleteRequest.php'; ?>
<?php include 'au.com.gateway.client.payment/PaymentCompleteResponse.php'; ?>
<?php include 'au.com.gateway.client.utils/IJsonHelper.php'; ?>
<?php include 'au.com.gateway.client.helpers/PaymentCompleteJsonHelper.php'; ?>
<?php include 'au.com.gateway.client.utils/HmacUtils.php'; ?>
<?php include 'au.com.gateway.client.utils/CommonUtils.php'; ?>
<?php include 'au.com.gateway.client.utils/RestClient.php'; ?>
<?php include 'au.com.gateway.client.enums/TransactionType.php'; ?>
<?php include 'au.com.gateway.client.enums/Version.php'; ?>
<?php include 'au.com.gateway.client.enums/Operation.php'; ?>
<?php include 'au.com.gateway.client.facade/Vault.php'; ?>

<?php

$responseSuccessCode = "00";
$redirectUrl = "";

// Environment Variables
$pgwEndPoint = getenv("PGW_END_POINT");
$pgwAuthToken = getenv("PGW_AUTH_TOKEN");
$pgwHmacSecret = getenv("PGW_HMAC_SECRET");

$pgwHost = getenv("PGW_HOST");
$pgwPort = getenv("PGW_PORT");
$pgwProtocol = getenv("PGW_PROTOCOL");

$pgwMode = getenv("PGW_MODE");

$nodeServerIp = getenv("NODE_HOST") ? getenv("NODE_HOST") : "10.2.2.150";
$nodeServerPort = getenv("NODE_PORT") ? getenv("NODE_PORT") : "4000";
$nodeServerProtocol = getenv("NODE_PROTOCOL") ? getenv("NODE_PROTOCOL") : "http";


date_default_timezone_set('Asia/Colombo');
/* ------------------------------------------------------------------------------
  STEP1: Build PaycorpClientConfig object
  ------------------------------------------------------------------------------ */
$clientConfig = new ClientConfig();
$clientConfig->setServiceEndpoint($pgwEndPoint);
$clientConfig->setAuthToken($pgwAuthToken);
$clientConfig->setHmacSecret($pgwHmacSecret);

$clientConfig->setValidateOnly(FALSE);
/* ------------------------------------------------------------------------------
  STEP2: Build PaycorpClient object
  ------------------------------------------------------------------------------ */
$client = new GatewayClient($clientConfig);
/* ------------------------------------------------------------------------------
  STEP3: Build PaymentCompleteRequest object
  ------------------------------------------------------------------------------ */
$completeRequest = new PaymentCompleteRequest();
//$completeRequest->setClientId(123);
$completeRequest->setReqid($_GET['reqid']);
/* ------------------------------------------------------------------------------
  STEP4: Process PaymentCompleteRequest object
  ------------------------------------------------------------------------------ */
$completeResponse = $client->getPayment()->complete($completeRequest);

$creditCard = $completeResponse->getCreditCard();
$transactionAmount = $completeResponse->getTransactionAmount();
$responseCode = $completeResponse->getResponseCode();

// Parse Url
$urlParts = parse_url($_SERVER['REQUEST_URI']);

// Load parsed url into query object
parse_str($urlParts['query'], $query);

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

?>

<script type="text/javascript">
    var redirectUrl = "<?php



        if ($query['secret'] != "test") {
            // Production Mode

            // Node server payment confirmation url
            $payment_url = $nodeServerProtocol . "://" . $nodeServerIp . ":" . $nodeServerPort . "/registration/payment/36053764d32fa15450a622dab4ac3b4b";

            if ($responseCode == $responseSuccessCode) {

                $transId = $completeResponse->getTxnReference();
                $amount = $transactionAmount->getPaymentAmount();
                $data = array('hash' => $query['secret'], 'transactionId' => $transId, 'timestamp' => time(), 'amount' => $amount);
                $result = httpPost($payment_url, $data);
                $json = json_decode($result, true);
                if ($json['student_master']['registration_payment_status'] == "FULL") {
                    $redirectUrl = $nodeServerProtocol . "://" . $nodeServerIp . ":" . $nodeServerPort . "/summary?secret=" . $query['secret'];
                } else {
                    $redirectUrl = $nodeServerProtocol . "://" . $nodeServerIp . ":" . $nodeServerPort . "/request-error";
                }
            } else {
                $redirectUrl = $nodeServerProtocol . "://" . $nodeServerIp . ":" . $nodeServerPort . "/request-error";
            }


        } else {

            // Test Mode
            $redirectUrl = $nodeServerProtocol . "://" . $nodeServerIp . ":" . $nodeServerPort;

        }

        ?>"

    //top.window.location.href = redirectUrl;
</script>


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1">
            <div class="app-card">
                <div class="app__header">
                    <div class="pull-left">
                        <h3 class="not-print">Payment Status</h3>
                        <h3 class="print">Masters Registration Payment Receipt</h3>
                        <span><strong class="not-print <?php
                            if ($responseCode == $responseSuccessCode) {
                                echo "green";
                            } else {
                                echo "red";
                            }
                            ?>"><?php
                                if ($responseCode == $responseSuccessCode) {
                                    echo "Your payment was successful.";
                                } else {
                                    echo "Your payment has Failed!";
                                }
                                ?></strong></span>
                    </div>
                    <img *ngIf="!removeDetail" class="pull-right"
                         src="data:image/png;base64,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"
                         alt="logo"/>
                </div>
                <div class="clearfix"></div>
                <hr>

                <section>

                    <table class="table table-striped table-bordered " style="width: 100%">
                        <thead>
                        <tr>
                            <th colspan="2">Payment Details</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td><b>Card Holder Name</b></td>
                            <td><?php echo($creditCard->getHolderName()) ?>  </td>
                        </tr>

                        <tr>
                            <td><b>Card Number</b></td>
                            <td><?php echo($creditCard->getNumber()) ?></td>
                        </tr>

                        <tr>
                            <td><b>Payment Amount</b></td>
                            <td><?php echo($transactionAmount->getPaymentAmount() . " (" . $transactionAmount->getCurrency() . ")") ?>
                            </td>
                        </tr>

                        <?php
                        if ($responseCode != $responseSuccessCode) {
                            echo "<tr>
                                    <td><b>Reason</b></td>
                                    <td>" . $completeResponse->getResponseText() . " </td>
                                </tr>";
                        }
                        ?>


                        <tr>
                            <td><b>Reference No.</b></td>
                            <td><?php echo($completeResponse->getTxnReference()) ?></td>
                        </tr>

                        </tbody>
                    </table>

                </section>


                <hr>
                <div class="not-print">
                    <button class="btn btn-primary pull-left" type="button" id="printBtn">Print</button>
                    <button class="btn btn-primary pull-right" type="button" id="actionBtn"><?php
                        if ($responseCode == $responseSuccessCode) {
                            echo "Done";
                        } else {
                            echo "Back";
                        }
                        ?></button>
                </div>
                <div class="clearfix"></div>
                <br>
            </div>
        </div>
    </div>
</div>
<br/>
<script>
    document.getElementById("actionBtn").addEventListener("click", function () {

        window.location.href = "<?php
            echo $redirectUrl;
            ?>";
    }, false);

    document.getElementById("printBtn").addEventListener("click", function () {
        window.print()
    }, false);
</script>
</body>
</html>
