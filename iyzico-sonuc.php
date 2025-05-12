<?php
ob_start();
session_start();
require_once('iyzico/config.php');
require_once('classes/adduser.classes.php');

$token=$_POST['token'];
$siparis_no=$_GET['siparis_no'];


$request = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("$siparis_no");
$request->setToken("$token");
$checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($request, Config::options());

//print_r($checkoutForm->getStatus());
echo $odeme_durum=$checkoutForm->getPaymentStatus();
echo "<br>" . $paidPrice=$checkoutForm->getPaidPrice() . "<br>";
echo "<br>" . $commissionRate=$checkoutForm->getiyziCommissionRateAmount() . "<br>";
echo "<br>" . $commissionFee=$checkoutForm->getiyziCommissionFee() . "<br>";
echo "<pre>";
print_r($checkoutForm);
echo "</pre>";
//gonderılen orderid
$orderid=$checkoutForm->getbasketId();




if ($odeme_durum=="FAILURE") {
	
	echo "tamamlanamadı";


} elseif ($odeme_durum=="SUCCESS") {

	echo "Tamamlandı :";

	
}
?>
