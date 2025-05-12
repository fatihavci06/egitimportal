<?php
require 'vendor/autoload.php'; // Composer kullanıyorsanız

$options = new \Iyzipay\Options();
$options->setApiKey("sandbox-ZB0Xiesca8CVZeURjTyOjwPugpDyfAos");
$options->setSecretKey("sandbox-aG8p6kUfR5tGRHLN2GzPJxTjpaWGYW5P");
// $options->setBaseUrl("https://api.iyzipay.com"); // Canlı ortam için
$options->setBaseUrl("https://sandbox-api.iyzipay.com"); // Test ortamı için

$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId("SiparisNumaraniz_".time()); // Benzersiz bir sipariş numarası

$request->setPrice("100.00"); // Ödenecek tutar (Kuruş olarak değil, TL/TRY olarak)
$request->setPaidPrice("100.00"); // Ödenen tutar
$request->setCurrency(\Iyzipay\Model\Currency::TL);
//$request->setInstallment(1); // Taksit sayısı (tek çekim için 1)
$request->setBasketId("BASKET_123"); // Sepet ID'niz
//$request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

// Alıcı Bilgileri
$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId("BY789");
$buyer->setName("John");
$buyer->setSurname("Doe");
$buyer->setGsmNumber("+905350000000");
$buyer->setEmail("email@email.com");
$buyer->setIdentityNumber("74300864791");
$buyer->setLastLoginDate("2015-10-05 12:43:35");
$buyer->setRegistrationDate("2013-04-21 15:12:09");
$buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$buyer->setIp("85.34.78.112");
$buyer->setCity("Istanbul");
$buyer->setCountry("Turkey");
$buyer->setZipCode("34732");
$request->setBuyer($buyer);

// Kargo Adresi (isteğe bağlı)
$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("Jane Doe");
$shippingAddress->setCity("Istanbul");
$shippingAddress->setCountry("Turkey");
$shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$shippingAddress->setZipCode("34742");
$request->setShippingAddress($shippingAddress);

// Fatura Adresi
$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("Jane Doe");
$billingAddress->setCity("Istanbul");
$billingAddress->setCountry("Turkey");
$billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$billingAddress->setZipCode("34742");
$request->setBillingAddress($billingAddress);

// Sepet Öğeleri
$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("BI101");
$firstBasketItem->setName("Ürün Adı 1");
$firstBasketItem->setCategory1("Kategori 1");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice("60.00");
$basketItems[0] = $firstBasketItem;

$secondBasketItem = new \Iyzipay\Model\BasketItem();
$secondBasketItem->setId("BI102");
$secondBasketItem->setName("Ürün Adı 2");
$secondBasketItem->setCategory1("Kategori 2");
$secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$secondBasketItem->setPrice("40.00");
$basketItems[1] = $secondBasketItem;
$request->setBasketItems($basketItems);

# make the call
//$payment = \Iyzipay\Model\Payment::create($request, $options);

$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

# print result
/*if ($payment->getStatus() == "success") {
    // Ödeme başarılı, $payment nesnesinden detayları alabilirsiniz
    echo "<pre>";
    print_r($payment);
    echo "</pre>";
    // Örneğin: $payment->getPaymentId()
    // Veritabanına ödeme bilgilerini kaydetme vb. işlemleri burada yapın
} else {
    // Ödeme başarısız, hata mesajlarını inceleyin
    echo "<pre>";
    //print_r($payment->getError());
    echo "</pre>";
    // Hata durumunda kullanıcıya bilgi verme vb. işlemleri burada yapın
}
*/

?>
<div id="iyzipay-checkout-form" class="responsive"></div>