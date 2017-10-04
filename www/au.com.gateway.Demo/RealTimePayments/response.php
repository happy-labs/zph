<?php include '../../au.com.gateway.client/GatewayClient.php'; ?>
<?php include '../../au.com.gateway.client.config/ClientConfig.php'; ?>
<?php include '../../au.com.gateway.client.component/RequestHeader.php'; ?>
<?php include '../../au.com.gateway.client.component/CreditCard.php'; ?>
<?php include '../../au.com.gateway.client.component/TransactionAmount.php'; ?>
<?php include '../../au.com.gateway.client.component/Redirect.php'; ?>
<?php include '../../au.com.gateway.client.facade/BaseFacade.php'; ?>
<?php include '../../au.com.gateway.client.facade/Payment.php'; ?>
<?php include '../../au.com.gateway.client.payment/PaymentRealTimeRequest.php'; ?>
<?php include '../../au.com.gateway.client.payment/PaymentRealTimeResponse.php'; ?>
<?php include '../../au.com.gateway.client.root/PaycorpRequest.php'; ?>
<?php include '../../au.com.gateway.client.utils/IJsonHelper.php'; ?>
<?php include '../../au.com.gateway.client.helpers/PaymentRealTimeJsonHelper.php'; ?>
<?php include '../../au.com.gateway.client.utils/HmacUtils.php'; ?>
<?php include '../../au.com.gateway.client.utils/CommonUtils.php'; ?>
<?php include '../../au.com.gateway.client.utils/RestClient.php'; ?>
<?php include '../../au.com.gateway.client.enums/TransactionType.php'; ?>
<?php include '../../au.com.gateway.client.enums/Version.php'; ?>
<?php include '../../au.com.gateway.client.enums/Operation.php'; ?>
<?php include '../../au.com.gateway.client.facade/Vault.php'; ?>

<?php
if (isset($_POST['submit'])) {
    $authToken = $_POST['authToken'];
    $hmac = $_POST['hmac'];
    $clientId = (int) $_POST['clientId'];
    $originalTxnReference = $_POST['originalTxnReference'];
    $transactionType = $_POST['transactionType'];

    $cardType = $_POST['cardType'];
    $cardNo = $_POST['cardNo'];
    $expiryDate = $_POST['expiryDate'];
    $secureId = $_POST['secureId'];
    
    $totalAmount = (float) $_POST['totalAmount'];
    $serviceFeeAmount = (float) $_POST['serviceFeeAmount'];
    $paymentAmount = (float) $_POST['paymentAmount'];
    $currency = $_POST['currency'];

    date_default_timezone_set('Asia/Colombo');

    /* ------------------------------------------------------------------------------
      STEP1: Build PaycorpClientConfig object
      ------------------------------------------------------------------------------ */
    $clientConfig = new ClientConfig();
    $clientConfig->setServiceEndpoint("https://test-combank.paycorp.com.au/rest/service/proxy/");
    $clientConfig->setAuthToken($authToken);
    $clientConfig->setHmacSecret($hmac);

    /* ------------------------------------------------------------------------------
      STEP2: Build PaycorpClient object
      ------------------------------------------------------------------------------ */
    $client = new GatewayClient($clientConfig);
    /* ------------------------------------------------------------------------------
      STEP3: Build PaymentRealTimeRequest object
      ------------------------------------------------------------------------------ */

    $realTimeRequest = new PaymentRealTimeRequest();
    $realTimeRequest->setClientId($clientId);
    $realTimeRequest->setTransactionType($transactionType);
    $realTimeRequest->setOriginalTxnReference($originalTxnReference);
    $realTimeRequest->setClientRef("cvdb-123");
    $realTimeRequest->setComment("testing");
    $extraData = array("invoice-no" => "I99999", "job-no" => "J10101");
    $realTimeRequest->setExtraData($exData);
    // sets credit-card details
    $creditCard = new CreditCard();
    $creditCard->setType($cardType);
    $creditCard->setHolderName("Bob Marley");
    $creditCard->setExpiry($expiryDate);
    $creditCard->setNumber($cardNo);
    $creditCard->setSecureId($secureId);
    $creditCard->setSecureIdSupplied(TRUE);
    $realTimeRequest->setCreditCard($creditCard);
    // sets transaction-amounts details (all amounts are in cents)

    $transactionAmount = new TransactionAmount($paymentAmount);
    $transactionAmount->setTotalAmount();
    //$transactionAmount->setPaymentAmount($paymentAmount);
    $transactionAmount->setServiceFeeAmount();
    $transactionAmount->setCurrency($currency);
    $realTimeRequest->setTransactionAmount($transactionAmount);

    /* ------------------------------------------------------------------------------
      STEP4: Process PaycorpRealTimeRequest object
      ------------------------------------------------------------------------------ */
    $realTimeResponse = $client->getPayment()->realTime($realTimeRequest);
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>RealTime Payments -demo</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
        <link rel="stylesheet" href="css/jsonprettyprint.css">
        
        <script src="js/jquery-1.10.2.min.js"></script>

    </head>
    <body>
        <div class="col-lg-8 col-lg-offset-2">
            <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Real Time Response Page </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../HomeForAllServices.php">Home</a></li>
                <li><a href="../RealTimePayments/RealTimePaymentsHome.php">Go Back</a></li>
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">RealTime Services <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="Purchase.php?transactionType=PURCHASE">Purchase</a></li>
                    <li><a href="Purchase.php?transactionType=AUTHORISATION">Authorisation</a></li>
                    <li><a href="refund.php?transactionType=REFUND">Refund</a></li>
                    <li><a href="refund.php?transactionType=COMPLETION">Completion</a></li>
                  
                </ul>
              </li>
            </ul>      
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
            <div class="" id="initresponsearea" ><h6 style="color: maroon"></h6>
                <table class="table table-striped table-bordered " data-toggle="table" ">
                    <thead>
                        <tr>
                            <th>Response Parameter</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TxnRef</td>
                            <td><?php echo $realTimeResponse->getTxnReference(); ?></td>
                        </tr>
                        <tr>
                            <td>Response Code</td>
                            <td><?php echo $realTimeResponse->getResponseCode(); ?></td>
                        </tr>
                        <tr>
                            <td>Response Text</td>
                            <td><?php echo $realTimeResponse->getResponseText(); ?></td>
                        </tr>
                        <tr>
                            <td>Settlement Date</td>
                            <td><?php echo $realTimeResponse->getSettlementDate(); ?></td>
                        </tr>
                        <tr>
                            <td>Auth Code</td>
                            <td><?php echo $realTimeResponse->getAuthCode(); ?></td>
                        </tr>
                       
                        
                    </tbody>
                </table>
            </div></td>
    </tr>

</tbody>
</table>
</div>
        
<script src="js/bootstrap.min.js"></script>
<script src="js/bootswatch.js"></script>
</body>
</html>
