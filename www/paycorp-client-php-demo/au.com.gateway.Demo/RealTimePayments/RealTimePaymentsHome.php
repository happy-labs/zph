<html>
    <head>
        <title>Paycorp Payment Solutions</title>
        <link rel="stylesheet" href="css/bootstrap.css" media="screen">
        <link rel="stylesheet" href="css/bootswatch.min.css">
        <link rel="stylesheet" href="css/jsonprettyprint.css">
        <link rel="stylesheet" href="css/layout.css" type="text/css" />
        <script src="js/jquery-1.10.2.min.js"></script>
    </head>
    <body>
        <div class="col-lg-10 col-lg-offset-1">
            
            <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Real Time Payment Services </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="../HomeForAllServices.php">Home</a></li>
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
            
            <div id="page-header" >
            <div class="page-width">
                <div id="page-logo">
                    <img src="images/paycorp-logo.png" alt="Paycorp Payment Solutions" />
                </div>
            </div>
        </div>
        <div id="content-header">
            Paycorp Real-time Payment Services
        </div>
        <table id="content" class="table">
            <tr id="content-title">
                <td style="width: 100px;">
                    Service
                </td>
                <td>
                    Request Type
                </td>
                <td>
                    Response Type
                </td>
                <td>
                    Description
                </td>
            </tr>
            <tr>
                <td>
                    <a href="Purchase.php?transactionType=PURCHASE">Payment Real-time (Purchase)</a>
                </td>
                <td>
                    <p>PaymentRealTimeRequest</p>
                </td>
                <td>
                    <p>PaymentRealTimeResponse</p>
                </td>
                <td>
                    <p>The Payment Real-time Purchase service-call is used to perform a single credit purchase transaction in real-time.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="refund.php?transactionType=REFUND">Payment Real-time (Refund)</a>
                </td>
                <td>
                    <p>PaymentRealTimeRequest</p>
                </td>
                <td>
                    <p>PaymentRealTimeResponse</p>
                </td>
                <td>
                    <p>The Payment Real-time Refund service-call is used to perform a single credit refund transaction in real-time.</p>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="Purchase.php?transactionType=AUTHORISATION">Payment Real-time (Authorisation)</a>
                </td>
                <td>
                    <p>PaymentRealTimeRequest</p>
                </td>
                <td>
                    <p>PaymentRealTimeResponse</p>
                </td>
                <td>
                    <p>The Payment Real-time Authorisation service-call is used to holds funds temporary.And the funds will be permanently transfered once the corresponding creditability
                    credit completion transaction is submitted.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="refund.php?transactionType=COMPLETION">Payment Real-time (completion)</a>
                </td>
                <td>
                    <p>PaymentRealTimeRequest</p>
                </td>
                <td>
                    <p>PaymentRealTimeResponse</p>
                </td>
                <td>
                    <p>The Payment Real-time Completion service-call is used to submit a credit completion transaction and to process it in real-time.
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="#">Token Processing in Payment Real-time (Purchase)</a>
                </td>
                <td>
                    <p>PaymentRealTimeRequest</p>
                </td>
                <td>
                    <p>PaymentRealTimeResponse</p>
                </td>
                <td>
                    <p>Token processing via Paycorp Real-time Services allows merchants to perform purchase transactions using Tokens. A token is basically a 16-digit unique number which is generated to represent a standard credit- card. Merchants are allowed to store these tokens in there databases, and use in recursive purchase transactions
                    </p>
                </td>
            </tr>
        </table>
        </div>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootswatch.js"></script>
    </body>
</html><?php

