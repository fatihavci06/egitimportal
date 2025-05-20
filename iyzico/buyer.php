<?php

require_once('config.php');




# create request class
$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("$siparis_no");
$request->setPrice($sepettoplam);
$request->setPaidPrice($sepettoplam);
$request->setCurrency(\Iyzipay\Model\Currency::TL);
$request->setBasketId("$siparis_no");
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("http://localhost/lineup_campus/odeme-sonuc");
$request->setEnabledInstallments([1]);


$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId("1");
$buyer->setName("$kullanici_ad");
$buyer->setSurname("$kullanici_soyad");
$buyer->setGsmNumber("05422918575");
$buyer->setEmail("$kullanici_mail");
$buyer->setIdentityNumber("$kullanici_tckn");
$buyer->setLastLoginDate("$kullanici_zaman");
$buyer->setRegistrationDate("$kullanici_zaman");
$buyer->setRegistrationAddress("$district/$kullanici_il");
$buyer->setIp("85.34.78.112");
$buyer->setCity("$kullanici_il");
$buyer->setCountry("Turkey");
$buyer->setZipCode("$postcode");
$request->setBuyer($buyer);

$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("$kullanici_ad");
$shippingAddress->setCity("$kullanici_il");
$shippingAddress->setCountry("Turkey");
$shippingAddress->setAddress("$kullanici_adresiyaz");
$shippingAddress->setZipCode("$postcode");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("$kullanici_ad");
$billingAddress->setCity("$kullanici_il");
$billingAddress->setCountry("Turkey");
$billingAddress->setAddress("$kullanici_adresiyaz");
$billingAddress->setZipCode("34742");
$request->setBillingAddress($billingAddress);

$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("$siparis_no");
$firstBasketItem->setName("Binocular");
$firstBasketItem->setCategory1("Collectibles");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice($sepettoplam);
$basketItems[0] = $firstBasketItem;
$request->setBasketItems($basketItems);


# make request
$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, Config::options());

# print result
//print_r($checkoutFormInitialize);
//print_r($checkoutFormInitialize->getstatus());
print_r($checkoutFormInitialize->getErrorMessage());
print_r($checkoutFormInitialize->getCheckoutFormContent());



?>

