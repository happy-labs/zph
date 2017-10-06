<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Complete Response -demo</title>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/bootswatch.min.css">
    <script src="js/jquery-1.10.2.min.js"></script>
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
date_default_timezone_set('Asia/Colombo');
/* ------------------------------------------------------------------------------
  STEP1: Build PaycorpClientConfig object
  ------------------------------------------------------------------------------ */
$clientConfig = new ClientConfig();
$clientConfig->setServiceEndpoint("https://sampath.paycorp.com.au/rest/service/proxy/");
$clientConfig->setAuthToken("ef8aff82-bae4-4706-b3c3-87f72de2e2b9");
$clientConfig->setHmacSecret("77MHMnVPQEyDGspe");

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

// Parse Url
$urlParts = parse_url($_SERVER['REQUEST_URI']);

// Load parsed url into query object
parse_str($urlParts['query'], $query);

// Node registration success url
$redirect_url = "Location: http://localhost:4200/registration-success?secret=" . $query['secret'];

// Node server payment confirmation url
$payment_url = 'http://10.2.2.150:4000/registration/payment/36053764d32fa15450a622dab4ac3b4b';


function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    echo http_build_query($data);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

$data = array('hash' => $query['secret']);
$result = httpPost($payment_url, $data);
$json = json_decode($result, true);
echo $json['student_master'];
if($json['student_master']['registration_payment_status'] == "FULL") {
    header($redirect_url);
    die();
} else {
    echo "Credit card Error!!!";
}


?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Paycorp PayCentreWeb Client (v4) </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../HomeForAllServices.php">Home</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">All Services<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../RealTimePayments/RealTimePaymentsHome.php">Paycorp Real-time Client</a></li>
                        <li><a href="payment_init.php">Paycorp PayCentreWeb Client (v4)</a></li>

                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>

<div id="" class="col-lg-12"><h6 style="color: maroon">Payment Complete Request :</h6>

    <table class="table table-striped table-bordered " data-toggle="table" style="float: none;width:500px;">
        <thead>
        <tr>
            <th>Request Parameter</th>
            <th>Value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>ClientId</td>
            <td><?php echo $completeRequest->getClientId(); ?></td>
        </tr>
        <tr>
            <td>ReqId</td>
            <td><?php echo $completeRequest->getReqid(); ?></td>

        </tr>
        </tbody>
    </table>

</div>
<br/>

<div class="col-lg-12 ">

    <h5 style="color: maroon">Payment Complete Response parameters and values</h5>
    <table class="table table-striped table-bordered " style="width: 100%">
        <thead>
        <tr>
            <th>Response Parameter</th>
            <th>Value</th>

        </tr>
        </thead>
        <tbody>
        <tr>
            <td><b>clientId</b></td>
            <td><?php echo($completeResponse->getClientId()); ?>  </td>
        </tr>
        <tr>
            <td><b>clientIdHash</b></td>
            <td><?php echo($completeResponse->getClientIdHash()); ?></td>
        </tr>
        <tr>
            <td><b>type</b></td>
            <td><?php echo($completeResponse->getTransactionType()) ?> </td>
        </tr>
        <!--  Credit Card Details -->
        <tr>
            <td><b>creditCard Type</b></td>
            <td><?php echo($creditCard->getType()) ?>"</td>
        </tr>
        <tr>
            <td><b>card holder name</b></td>
            <td><?php echo($creditCard->getHolderName()) ?>  </td>
        </tr>
        <tr>
            <td><b>Card number</b></td>
            <td><?php echo($creditCard->getNumber()) ?></td>
        </tr>
        <tr>
            <td><b>card expiry</b></td>
            <td><?php echo($creditCard->getExpiry()) ?> </td>
        </tr>
        <!-- Transaction Details  -->
        <tr>
            <td><b>paymentAmount</b></td>
            <td><?php echo($transactionAmount->getPaymentAmount()) ?></td>
        </tr>
        <tr>
            <td><b>totalAmount</b></td>
            <td><?php echo($transactionAmount->getTotalAmount()) ?></td>
        </tr>
        <tr>
            <td><b>serviceFeeAmount</b></td>
            <td><?php echo($transactionAmount->getServiceFeeAmount()) ?></td>
        </tr>
        <tr>
            <td><b>Currency</b></td>
            <td><?php echo($transactionAmount->getCurrency()) ?></td>
        </tr>

        <tr>
            <td><b>clientRef</b></td>
            <td><?php echo($completeResponse->getClientRef()) ?></td>
        </tr>
        <tr>
            <td><b>comment</b></td>
            <td><?php echo($completeResponse->getComment()) ?></td>
        </tr>
        <tr>
            <td><b>txnReference</b></td>
            <td><?php echo($completeResponse->getTxnReference()) ?></td>
        </tr>
        <tr>
            <td><b>feeReference</b></td>
            <td><?php echo($completeResponse->getFeeReference()) ?></td>
        </tr>
        <tr>
            <td><b>responseCode</b></td>
            <td><?php echo($completeResponse->getResponseCode()) ?> </td>
        </tr>
        <tr>
            <td><b>responseText</b></td>
            <td><?php echo($completeResponse->getResponseText()) ?> </td>
        </tr>
        <tr>
            <td><b>settlementDate</b></td>
            <td><?php echo($completeResponse->getSettlementDate()); ?></td>
        </tr>
        <tr>
            <td><b>token</b></td>
            <td><?php echo($completeResponse->getToken()) ?></td>
        </tr>
        <tr>
            <td><b>tokenized</b></td>
            <td><?php echo($completeResponse->getTokenized()) ?></td>
        </tr>
        <tr>
            <td><b>tokenResponseText</b></td>
            <td><?php echo($completeResponse->getTokenResponseText()) ?></td>
        </tr>
        <tr>
            <td><b>authCode</b></td>
            <td><?php echo($completeResponse->getAuthCode()) ?></td>
        </tr>
        <tr>
            <td><b>cvcResponse</b></td>
            <td><?php echo($completeResponse->getCvcResponse()) ?></td>
        </tr>
        <tr>
            <td><b>extraData</b></td>
            <td><?php echo($completeResponse->getExtraData()) ?></td>
        </tr>
        </tbody>
    </table>


    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
</div>
</body>
</html>
