<?php

    date_default_timezone_set('Asia/Calcutta');
    $strCurDate = date('d-m-Y');

    if( isset($_SERVER['HTTPS'] ) ) {
       $host ='https';
    }else{
        $host = 'http';
    }

?>

<html>
<head>
    <title>Reconciliation</title>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="<?php echo $host."://".$_SERVER["HTTP_HOST"].'/verify/assets/css/bootstrap.min.css';?>">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2>Reconciliation :</h2>
                <form method="POST" id="myform" action="reconcile_request.php">
                    <table class="table table-bordered table-hover">
                        <tr class="info">
                            <th width="40%">Field Name</th>
                            <th width="60%">Value</th>
                        </tr>         
                        <tr>
                            <td><label>Transaction Identifier <span style="color:red;">*</span></label></td>
                            <td><input class="form-control" type="text" name="transactionIdentifier" value=""/></td>
                        </tr>
                        <tr>
                            <td><label>Transaction Date From <span style="color:red;">*</span></label></td>
                            <td>
                                <input class="form-control" type="date" name="fromtransactionDate" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Transaction Date To <span style="color:red;">*</span></label></td>
                            <td>
                                <input class="form-control" type="date" name="totransactionDate" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2>
                                <input class="btn btn-info" type="submit" name="submit" value="Submit" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12" id="mytable">
                
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo $host."://".$_SERVER["HTTP_HOST"].'/verify/assets/js/bootstrap.min.js';?>"></script>
</body>
</html>