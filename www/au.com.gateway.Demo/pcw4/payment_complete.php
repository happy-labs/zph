<html>
    <head>
        <meta charset="UTF-8">
        <title>Payment Complete Response -demo</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
        <script src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        
        <?php include '../../au.com.gateway.client/GatewayClient.php'; ?>
        <?php include '../../au.com.gateway.client.config/ClientConfig.php'; ?>
        <?php include '../../au.com.gateway.client.component/RequestHeader.php'; ?>
        <?php include '../../au.com.gateway.client.component/CreditCard.php'; ?>
        <?php include '../../au.com.gateway.client.component/TransactionAmount.php'; ?>
        <?php include '../../au.com.gateway.client.component/Redirect.php'; ?>
        <?php include '../../au.com.gateway.client.facade/BaseFacade.php'; ?>
        <?php include '../../au.com.gateway.client.facade/Payment.php'; ?>
        <?php include '../../au.com.gateway.client.root/PaycorpRequest.php'; ?>
        <?php include '../../au.com.gateway.client.root/PaycorpResponse.php'; ?>
        <?php include '../../au.com.gateway.client.payment/PaymentCompleteRequest.php'; ?>
        <?php include '../../au.com.gateway.client.payment/PaymentCompleteResponse.php'; ?>
        <?php include '../../au.com.gateway.client.utils/IJsonHelper.php'; ?>
        <?php include '../../au.com.gateway.client.helpers/PaymentCompleteJsonHelper.php'; ?>
        <?php include '../../au.com.gateway.client.utils/HmacUtils.php'; ?>
        <?php include '../../au.com.gateway.client.utils/CommonUtils.php'; ?>
        <?php include '../../au.com.gateway.client.utils/RestClient.php'; ?>
        <?php include '../../au.com.gateway.client.enums/TransactionType.php'; ?>
        <?php include '../../au.com.gateway.client.enums/Version.php'; ?>
        <?php include '../../au.com.gateway.client.enums/Operation.php'; ?>
        <?php include '../../au.com.gateway.client.facade/Vault.php'; ?>

        <?php
        date_default_timezone_set('Asia/Colombo');
        /* ------------------------------------------------------------------------------
          STEP1: Build PaycorpClientConfig object
          ------------------------------------------------------------------------------ */
        $clientConfig = new ClientConfig();
        $clientConfig->setServiceEndpoint("https://test-combank.paycorp.com.au/rest/service/proxy/");
        $clientConfig->setAuthToken("912345ec-d7");
        $clientConfig->setHmacSecret("Test4");
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
        
        ?>
        <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
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
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">All Services<span class="caret"></span></a>
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

        </div><br />

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
                    <tr><td><b>clientId</b></td><td><?php echo($completeResponse->getClientId()); ?>  </td></tr>
                    <tr><td><b>clientIdHash</b></td><td><?php echo($completeResponse->getClientIdHash()); ?></td></tr>
                    <tr><td><b>type</b></td><td><?php echo($completeResponse->getTransactionType()) ?> </td></tr>
                    <!--  Credit Card Details -->
                    <tr><td><b>creditCard Type</b></td><td><?php echo($creditCard->getType()) ?>"</td></tr>
                    <tr><td><b>card holder name</b></td><td><?php echo($creditCard->getHolderName()) ?>  </td></tr>
                    <tr><td><b>Card number</b></td><td><?php echo($creditCard->getNumber()) ?></td></tr>
                    <tr><td><b>card expiry</b></td><td><?php echo($creditCard->getExpiry()) ?> </td></tr>
                    <!-- Transaction Details  -->
                    <tr><td><b>paymentAmount</b></td><td><?php echo($transactionAmount->getPaymentAmount()) ?></td></tr>
                    <tr><td><b>totalAmount</b></td><td><?php echo($transactionAmount->getTotalAmount()) ?></td></tr> 
                    <tr><td><b>serviceFeeAmount</b></td><td><?php echo($transactionAmount->getServiceFeeAmount()) ?></td></tr> 
                    <tr><td><b>Currency</b></td><td><?php echo($transactionAmount->getCurrency()) ?></td></tr> 

                    <tr><td><b>clientRef</b></td><td><?php echo($completeResponse->getClientRef()) ?></td></tr> 
                    <tr><td><b>comment</b></td><td><?php echo($completeResponse->getComment()) ?></td></tr>
                    <tr><td><b>txnReference</b></td><td><?php echo($completeResponse->getTxnReference()) ?></td></tr> 
                    <tr><td><b>feeReference</b></td><td><?php echo($completeResponse->getFeeReference()) ?></td></tr> 
                    <tr><td><b>responseCode</b></td><td><?php echo($completeResponse->getResponseCode()) ?> </td></tr> 
                    <tr><td><b>responseText</b></td><td><?php echo($completeResponse->getResponseText()) ?> </td></tr> 
                    <tr><td><b>settlementDate</b></td><td><?php echo($completeResponse->getSettlementDate()); ?></td></tr>
                    <tr><td><b>token</b></td><td><?php echo($completeResponse->getToken()) ?></td></tr> 
                    <tr><td><b>tokenized</b></td><td><?php echo($completeResponse->getTokenized()) ?></td></tr> 
                    <tr><td><b>tokenResponseText</b></td><td><?php echo($completeResponse->getTokenResponseText()) ?></td></tr> 
                    <tr><td><b>authCode</b></td><td><?php echo($completeResponse->getAuthCode()) ?></td></tr> 
                    <tr><td><b>cvcResponse</b></td><td><?php echo($completeResponse->getCvcResponse()) ?></td></tr> 
                    <tr><td><b>extraData</b></td><td><?php echo($completeResponse->getExtraData()) ?></td></tr> 
                </tbody>
            </table>


            <script src="js/bootstrap.min.js"></script>
            <script src="js/bootswatch.js"></script>
        </div>
    </body>
</html>
