<?php 
include '../../au.com.gateway.client.enums/TransactionType.php';

        $type = $_GET['transactionType'];

        if($type == "PURCHASE"){
            $transactionType = TransactionType::$PURCHASE;
            
        }
        if($type == "AUTHORISATION"){
            $transactionType = TransactionType::$AUTHORISATION;
        }
        
?>
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
            
            <div id="page-header">
                <div class="page-width">
                    <div id="page-logo">
                        <img src="images/paycorp-logo.png" alt="Paycorp Payment Solutions" />

                    </div>
                </div>

            </div>
            <div id="content-header">
               
                <h4><b>Paycorp Real-time Payment Services</b></h4>
            </div>
            <div >
                <table id="content" class="table">
                    <tr id="content-title">
                        <td style="width: 200px;">
                            Service
                        </td>
                        <td>
                            Request Type
                        </td>
                        <td>
                            Response Type
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <a href="#">Payment Real-time <?php echo "(". $type. ")"; ?></a>
                        </td>
                        <td>
                            <p>PaymentRealTimeRequest</p>
                        </td>
                        <td>
                            <p>PaymentRealTimeResponse</p>
                        </td>
                       
                    </tr>
                </table>
            </div>
            <br>

            <div id="" class="col-lg-12 ">
                <br>
                <form action="response.php" name="purchase"  method="POST">
                    <div class="form-group col-lg-7 ">
                        <table class="table table-striped table-condensed ">
                            <caption ><b>Service Implementation</b></caption>
                            <tr>
                                <td>
                            <u>Service Authentication :</u>
                            </td>
                            <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Authtoken * : </b>
                                </td>
                                <td>
                                    <input type="text"  name="authToken" class="input-mysize input-sm "  required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>HMAC Secret * :</b>
                                </td>
                                <td>
                                    <input type="text"  name="hmac" class="input-mysize input-sm"  required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                            <u>Service Parameters :</u>
                            </td>
                            <td></td>
                            </tr>
                            <tr>
                                <td>
                                    Client ID * :
                                </td>
                                <td>
                                    <input type="text"  name="clientId" class=" input-sm"  required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Card Details :</b>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    Card Type :
                                </td>

                                <td>
                                    <select class="form-control input-sm" id="sel1" name ="cardType" required >
                                        <option value ="VISA" selected="true">VISA</option>
                                        <option value ="MASTER">MASTER</option>
                                        <option value ="CREDIT">CREDIT</option>

                                    </select>


                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Card No * :
                                </td>
                                <td>
                                    <input type="text"  name="cardNo" class=" input-sm" maxlength="16" value="4564456445644564"  required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Expiry Date * :
                                </td>
                                <td>
                                    <input type="text"  name="expiryDate" class=" input-sm" maxlength="5" value="0520" required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Secure Id (CCV) :
                                </td>
                                <td>
                                    <input type="number"  name="secureId" class=" input-sm" value="123"  required/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Currency * :
                                </td>
                                <td>
                                    <select class="form-control input-sm" id="sel1" name ="currency" >
                                        <option value ="AUD" selected="">AUD</option>
                                        <option value ="LKR">LKR</option>
                                        <option value ="NZD">NZD</option>

                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Purchase Amount * :
                                </td>
                                <td>
                                    <input type="text"  name="paymentAmount" class="input-sm" value="20.00"  required/>
                                </td>
                            </tr>
                            
                             <tr>
                                <td>
                                    <input type="hidden" name="transactionType" value="<?php echo $transactionType ?>">
                                </td>
                                <td>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="submit" name="submit" class="btn-sm btn-primary " value="Submit REAL_TIME Request" />
                                </td>
                                <td>

                                </td>
                            </tr>
                        </table>

                    </div>
                </form>

            </div>

        </div>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootswatch.js"></script>
    </body>
</html>