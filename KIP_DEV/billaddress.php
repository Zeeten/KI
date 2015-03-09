<?php
   $access_key = "UCK3T24R2T46IJJ9RWEO"; //put your own access_key - found in admin panel
   $secret_key = "6555a87ed9752f777d53eaa7a98d4cf0c0de9045"; //put your own secret_key - found in admin panel
   $return_url = "http://kissmatinternational.com/KIP_TEST/billurl.php"; //put your own return_url.php here.
   $txn_id = time() . rand(10000,99999);
   $value = $_GET["amount"]; //Charge amount is in INR by default
   $cur = $_GET["currency"];
   $data_string = "merchantAccessKey=" . $access_key
                  . "&transactionId="  . $txn_id 
                  . "&amount="         . $value;
   $signature = hash_hmac('sha1', $data_string, $secret_key);
   $amount = array('value' => $value, 'currency' => $cur);
   $bill = array('merchantTxnId' => $txn_id,
                 'amount' => $amount,
                 'requestSignature' => $signature,
                 'merchantAccessKey' => $access_key,
                 'returnUrl' => $return_url);
   echo json_encode($bill);
 ?>