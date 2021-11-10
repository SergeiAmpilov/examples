<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Sale;

// debug +++
// $_REQUEST['orderid'] = '1345';
// $_REQUEST['orderid'] = '1344';
// debug ---

if (empty($_REQUEST['orderid'])) {
    echo json_encode( ["coupon" => false] , JSON_UNESCAPED_UNICODE); // возвращаем данные
    die();
}

$orderId = $_REQUEST['orderid'];


$order = Sale\Order::load($orderId);
$discountData = $order->getDiscount()->getApplyResult();

if (empty($discountData["COUPON_LIST"])) {
    echo json_encode( ["coupon" => false] , JSON_UNESCAPED_UNICODE); // возвращаем данные
    die();
}

foreach ($discountData["COUPON_LIST"] as $key => $value) {
    // echo "<pre>" . print_r($value["COUPON"], 1) . "</pre>"; die();
    echo json_encode(["coupon" => $value["COUPON"]], JSON_UNESCAPED_UNICODE); // возвращаем данные
    die();
}

?>