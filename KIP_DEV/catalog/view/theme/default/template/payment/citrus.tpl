<div id='result' style='display:none;'>
<iframe name='form_result' style='overflow: auto; width: 800px; height: 180px; border: 1px solid grey;'></iframe>
<br/><br/>
</div>

<link href="citrus_image/html-style.css" type="text/css" rel="stylesheet">
     

<form action="<?php echo $action;?>" method="POST" name="TransactionForm" id="transactionForm">
<p><input name="merchantAccessKey" type="hidden" value="<?php echo $citrus_access_key;?>" />
	<input name="merchantTxnId" type="hidden" value="<?php echo $citrus_merchant_trans_id;?>" />	
    <input name="addressState" type="hidden" value="<?php echo $state;?>" />
    <input name="addressCity" type="hidden" value="<?php echo $city;?>" />
    <input name="addressStreet1" type="hidden" value="<?php echo $addr1;?>" />
    <input name="addressCountry" type="hidden" value="<?php echo $country;?>" />
    <input name="addressZip" type="hidden" value="<?php echo $zip;?>" />
    <input name="firstName" type="hidden" value="<?php echo $firstname;?>"  />
    <input name="lastName" type="hidden" value="<?php echo $lastname;?>" />
    <input name="phoneNumber" type="hidden" value="<?php echo $phone;?>" />
    <input name="email" type="hidden" value="<?php echo $email;?>" />
    <input name="paymentMode" type="hidden" value="NET_BANKING" />
    
    <input name="returnUrl" type="hidden" value="<?php echo $redir_url;?>" />
    <input name="orderAmount" type="hidden" value="<?php echo $total;?>" />
    <input type="hidden" name="reqtime" value="<?php echo time()*1000; ?>" /> 
    <input type="hidden" name="secSignature" value="<?php echo $secSignature;?>" />
    <input type="hidden" name="currency" value="<?php echo $currency;?>" />
</p>

<div class="buttons">    
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" id="submit"/>
</div>

</form>

<style>
label {
float: left;
margin-right: 10px;
width: 150px;
}

.buttons{
 padding-right:75px; 
}


</style>