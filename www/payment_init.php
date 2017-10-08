<html>
<head>
    <meta charset="UTF-8">
    <title>Paycentre Web 4.0 Iframe demo</title>
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/bootswatch.min.css">
    <link rel="stylesheet" href="css/jsonprettyprint.css">
    <link rel="stylesheet" href="css/layout.css">
    <script src="js/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootswatch.js"></script>
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
<?php include 'au.com.gateway.client.payment/PaymentInitRequest.php'; ?>
<?php include 'au.com.gateway.client.payment/PaymentInitResponse.php'; ?>
<?php include 'au.com.gateway.client.root/PaycorpRequest.php'; ?>
<?php include 'au.com.gateway.client.utils/IJsonHelper.php'; ?>
<?php include 'au.com.gateway.client.helpers/PaymentInitJsonHelper.php'; ?>
<?php include 'au.com.gateway.client.utils/HmacUtils.php'; ?>
<?php include 'au.com.gateway.client.utils/CommonUtils.php'; ?>
<?php include 'au.com.gateway.client.utils/RestClient.php'; ?>
<?php include 'au.com.gateway.client.enums/TransactionType.php'; ?>
<?php include 'au.com.gateway.client.enums/Version.php'; ?>
<?php include 'au.com.gateway.client.enums/Operation.php'; ?>
<?php include 'au.com.gateway.client.facade/Vault.php'; ?>

<?php

$urlParts = parse_url($_SERVER['REQUEST_URI']);
parse_str($urlParts['query'], $query);

// $url = "http://msc.scorelab.org:4000/payment_complete.php";

// Environment Variables
$nodeServerIp = getenv("NODE_HOST") ? getenv("NODE_HOST") : "10.2.2.150";
$nodeServerPort = getenv("NODE_PORT") ? getenv("NODE_PORT") : "4000";
$nodeServerProtocol = getenv("NODE_PROTOCOL") ? getenv("NODE_PROTOCOL") : "http";

$pgwHost = getenv("PGW_HOST");
$pgwPort = getenv("PGW_PORT");
$pgwProtocol = getenv("PGW_PROTOCOL");

$pgwEndPoint = getenv("PGW_END_POINT") ? getenv("PGW_END_POINT") : "https://sampath.paycorp.com.au/rest/service/proxy/";
$pgwAuthToken = getenv("PGW_AUTH_TOKEN") ? getenv("PGW_AUTH_TOKEN") : "ef8aff82-bae4-4706-b3c3-87f72de2e2b9";
$pgwHmacSecret = getenv("PGW_HMAC_SECRET") ? getenv("PGW_HMAC_SECRET") : "77MHMnVPQEyDGspe";

// Redirect Url on payment complete
$url = $pgwProtocol."://".$pgwHost.":".$pgwPort."/payment_complete.php" . "?secret=" . $query['secret'];

$operation = 'PAYMENT_INIT';
$requestDate = date('Y-m-d H:i:s');
$validateOnly = "FALSE";
$clientId = "14001254";
$transactionType = 'PURCHASE';
$tokenize = true;
$tokenReference = 'tokenReference';
$clientRef = 'merchant_reference';
$comment = 'merchent_additional_data';
$msisdn = '0432384947';
$sessionId = 'x2d2323r23r23';
$totalAmount = (float)0;
$serviceFeeAmount = (float)0;
$paymentAmount = (float)2500.00;
$currency = 'LKR';
$returnUrl = $url;
$returnMethod = 'GET';

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
  STEP3: Build PaymentInitRequest object
  ------------------------------------------------------------------------------ */
$initRequest = new PaymentInitRequest();

$initRequest->setClientId($clientId);
$initRequest->setTransactionType(TransactionType::$PURCHASE);
$initRequest->setClientRef($clientRef);
$initRequest->setComment($comment);
$initRequest->setTokenize(TRUE);
$initRequest->setExtraData(array("msisdn" => "$msisdn", "sessionId" => "$sessionId"));

// sets transaction-amounts details (all amounts are in cents)
$transactionAmount = new TransactionAmount($paymentAmount);
$transactionAmount->setTotalAmount($totalAmount);
$transactionAmount->setServiceFeeAmount($serviceFeeAmount);
$transactionAmount->setPaymentAmount((float)$paymentAmount);
$transactionAmount->setCurrency($currency);
$initRequest->setTransactionAmount($transactionAmount);
// sets redirect settings

$redirect = new Redirect($returnUrl);
$redirect->setReturnUrl($returnUrl);
$redirect->setReturnMethod($returnMethod);
$initRequest->setRedirect($redirect);
/* ------------------------------------------------------------------------------
  STEP4: Process PaymentInitRequest object
  ------------------------------------------------------------------------------ */
$initResponse = $client->getPayment()->init($initRequest);


$redirect_url = "Location: " . $initResponse->getPaymentPageUrl();
header($redirect_url);
die();


?>


<div class="col-lg-12 ">
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
    <div class="well well-sm">
        <h4>Iframe Example for PCW4 using PHP</h4>
        <p> Below IFrame is generating After the Initial Request to the new PCW Service.<br/> The corresponding Request
            is shown in the left side of the page You can add corresponding correct parameters for the form to check
            response from PCW.</p> <br/>
    </div>

    <table class="table table-condensed " style="width: 100%;">
        <thead style="color: maroon">
        <tr>
            <th>Initial Request</th>
            <th>Iframe and Complete Response</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="payment_init.php" method="post" id="submitform">
                    <table class="table table-condensed" style="">

                        <tr>
                            <td><b>Client ID * </b></td>
                            <td><input type="text" name="clientId" class="input-sm" value="14001254"/></td>
                        </tr>
                        <tr>
                            <td><b>Operation</b></td>
                            <td><input type="text" readonly="readonly" name="operation" class="input-sm"
                                       value="PAYMENT_INIT"/></td>
                        </tr>
                        <tr>
                            <td><b>Request Date</b></td>
                            <td><input type="text" readonly="readonly" name="requestDate" class="input-sm"
                                       value="<?php echo date('Y-m-d H:i:s'); ?>"/></td>
                        </tr>
                        <tr>
                            <td><b>Validate Only</b></td>
                            <td><input type="text" readonly="readonly" name="validateOnly" class="input-sm"
                                       value="FALSE"/></td>
                        </tr>


                        <tr>
                            <td><b>Transaction Type</b></td>
                            <td><input type="text" readonly="readonly" name="transactionType" class="input-sm"
                                       value="PURCHASE"/></td>
                        </tr>
                        <tr>
                            <td><b>Tokenize</b></td>
                            <td><input type="text" readonly="readonly" name="tokenize" class="input-sm" value="true"/>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Token Reference</b></td>
                            <td><input type="text" readonly="readonly" name="tokenReference" class="input-sm"
                                       value="tokenReference"/></td>
                        </tr>
                        <tr>
                            <td><b>clientRef</b></td>
                            <td><input type="text" readonly="readonly" name="clientRef" class="input-sm"
                                       value="merchant_reference"/></td>
                        </tr>
                        <tr>
                            <td><b>comment</b></td>
                            <td><input type="text" readonly="readonly" name="comment" class="input-sm"
                                       value="merchent_additional_data"/></td>
                        </tr>
                        <tr>
                            <td><b>msisdn</b></td>
                            <td><input type="text" readonly="readonly" name="msisdn" class="input-sm"
                                       value="0432384947"/></td>
                        </tr>
                        <tr>
                            <td><b>sessionId</b></td>
                            <td><input type="text" readonly="readonly" name="sessionId" class="input-sm"
                                       value="x2d2323r23r23"/></td>
                        </tr>

                        <tr>
                            <td><b>Payment Amount</b></td>
                            <td><input type="text" readonly="readonly" name="paymentAmount" class="input-sm"
                                       value="1000"/></td>
                        </tr>
                        <tr>
                            <td><b>Total Amount</b></td>
                            <td><input type="text" readonly="readonly" name="totalAmount" class="input-sm" value="0"/>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Service Fee Amount</b></td>
                            <td><input type="text" readonly="readonly" name="serviceFeeAmount" class="input-sm"
                                       value="0"/></td>
                        </tr>

                        <tr>
                            <td><b>Currency</b></td>
                            <td><input type="text" name="currency" class="input-sm" value="AUD"/></td>
                        </tr>

                        <tr>
                            <td><b>Return Method</b></td>
                            <td><input type="text" readonly="readonly" name="returnMethod" class="input-sm"
                                       value="GET"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="hidden" readonly="readonly" name="returnUrl" class="input-sm"
                                       value="<?php echo $url ?>"/></td>
                        </tr>

                        <tr>
                            <td><b></b></td>
                            <td><input type="submit" name="submit" class="btn btn-primary "
                                       value="Submit INIT Request"/></td>
                        </tr>

                    </table>
                </form>

                <div class="" id="initresponsearea"><h6 style="color: maroon">Initial Response is for above request</h6>
                    <table class="table table-striped table-bordered " data-toggle="table"
                           style="float: none;width:500px;">
                        <thead>
                        <tr>
                            <th>Response Parameter</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>ReqId</td>
                            <td><?php echo $initResponse->getReqid(); ?></td>
                        </tr>
                        <tr>
                            <td>PaymentPageUrl</td>
                            <td><?php echo $initResponse->getPaymentPageUrl(); ?></td>
                        </tr>
                        <tr>
                            <td>Expire At</td>
                            <td><?php echo $initResponse->getExpireAt(); ?></td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </td>
            <td>

                <iframe class="col-lg-12" height="1500px" width="100%"
                        src="<?php echo $initResponse->getPaymentPageUrl(); ?>">
            </td>
        </tr>

        </tbody>
    </table>

</div>


<script src="js/bootstrap.min.js"></script>
<script src="js/bootswatch.js"></script>


</body>
</html>
