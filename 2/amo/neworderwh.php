<?
 
// neworderwh.php
// https://wineexpress.ru/wenewexchange/amo/neworderwh.php?ID=4760

require_once 'config.php';
require_once 'amowh.php';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); // Bitrix

// логи
file_put_contents('neworderwh.log',  "\n" . print_r(date('l jS \of F Y h:i:s A'), 1), FILE_APPEND);
file_put_contents('neworderwh.log',  "\n" . print_r($_REQUEST, 1), FILE_APPEND);

/* ставим защиту от авторизаций от демо-сайтов */
if ($_REQUEST["auth"]["domain"] && $_REQUEST["auth"]["domain"] !=  "wineexpress.ru" && $_REQUEST["auth"]["domain"] !=  "bx24.wineexpress.ru" && $_REQUEST["auth"]["domain"] !=  "sochi.wineexpress.ru")
{
    file_put_contents('neworderwh.log',  "\n Die - not main domain", FILE_APPEND);
    die();
}

$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);


if (!($orderId = $_REQUEST["ID"]))
{
    file_put_contents('neworderwh.log',  "\n Empty order ID. die!", FILE_APPEND);
    die;
}

// добавим проверку на то, что сделка по этому ИД уже создавалась ранее
// временно отключаю
// $resGetPreviousOrder = $amoApi->getPreviousOrderById($orderId);
// if ( $resGetPreviousOrder !== false)
// {
//     file_put_contents('neworderwh.log',  "\n Allready exist in AMO order with external id $orderId! die!", FILE_APPEND);
//     file_put_contents('neworderwh.log',  "\n Founr id in AMO " . print_r($resGetPreviousOrder, 1), FILE_APPEND);
//     die();
// }


$order = Bitrix\Sale\Order::load($orderId);
$userDescription = $order->getField('USER_DESCRIPTION');

/**
 * Для заказов с билетами добавляем дату мероприятия в комментарий
 */
$property = $order->getPropertyCollection()->getItemByOrderPropertyCode('IS_TICKET_PURCHASE');
if ($property && $property->getValue() == 'Y')
{
    foreach ($order->getBasket() as $item)
    {
        $propertyValues = $item->getPropertyCollection()->getPropertyValues();
        if ($propertyValues['EVENT_DATE'])
            $userDescription = $propertyValues['EVENT_DATE']['VALUE'] . ' ' . $userDescription;
    }
}

$propsData = [];
$reqItemsData = []; /* это массив элементов для запроса вида ид амо - колво */
$db_vals = CSaleOrderPropsValue::GetList([],["ORDER_ID" => $orderId]);

while($arVals = $db_vals->Fetch())
{
    $propsData[] = $arVals;
}

// echo "<pre> Props data \n" . print_r($propsData, 1) . "</pre>";


$name  = getPropsByName('FIO', $propsData);
$email  = getPropsByName('EMAIL', $propsData);
$phone  = getPropsByName('PHONE', $propsData);
$sourceCode  = getPropsByName('SOURCE', $propsData);
$couponVal = getCoupon($orderId);

if (empty($couponVal) || $couponVal == false) {
    $couponVal = '';
}


// получаем товары по заказу
$discountFieldData = []; // собираем данные о скидках в кастомное поле
$itemData = [];
$basketItems = [];
$db_items = CSaleBasket::GetList([], ['ORDER_ID' => $orderId]);
while ($ar_item = $db_items->Fetch())
    $basketItems[$ar_item['PRODUCT_ID']] = $ar_item;

$res = CIBlockElement::GetList(false, ['ID' => array_keys($basketItems), '!PROPERTY_ARTNUMBER' => false], false, false, ['ID', 'PROPERTY_ARTNUMBER']);
while ($ar = $res->Fetch())
    $basketItems[$ar['ID']]['ARTNUMBER'] = $ar['PROPERTY_ARTNUMBER_VALUE'];

foreach ($basketItems as $item)
{
    echo "<pre>AR _ ITEM data=\n" . print_r($item, 1) . "</pre>";
    
    // ищем ИД элемента в АМО
    $elementId = $amoApi->findCatalogElementByArtnumberFast($item["ARTNUMBER"]);
    $item["AMO_ID"] = $elementId;



    $itemData[] =
    [
        "to_entity_id" => (int) $elementId, // id элемента каталога
        "to_entity_type" => "catalog_elements",
        "metadata" =>
        [
            "quantity" => (int) $item["QUANTITY"],
            "catalog_id" => 4111
        ]
    ];

    // добавляем данные по скидкам в кастомное поле

    if ($sourceCode == 'wineexpress.ru')
    {
        // берем размер скидки из соответствующего поля в заказе
        $discount_val = (int) $item["DISCOUNT_PRICE"];

    } else
    {
        // рассчитываем размер скидки как разницу между ценой в заказе и базовой ценой
        $res_base_price = CPrice::GetBasePrice($item['PRODUCT_ID']);
        
        // echo "<pre>Product id111=\n" . print_r($item['PRODUCT_ID'], 1) . "</pre>";
    

        // echo "<pre>Base_prise111=\n" . print_r($res_base_price, 1) . "</pre>";

        $discount_val = $res_base_price["PRICE"] - $item["PRICE"];
        

    }

    $discountFieldData[ $elementId ] = [
        "type" => "abs",
        "value" => $discount_val,
    ];
}

// echo json_encode($discountFieldData);
echo "<pre>ITEMS data=\n" . print_r($itemData, 1) . "</pre>";


$contactIdinAmo = $amoApi->getContactByPhone($phone);
echo "<pre>CIIA=\n" . print_r($contactIdinAmo, 1) . "</pre>";
file_put_contents('neworderwh.log',  "\nCIIA=\n" . print_r($contactIdinAmo, 1), FILE_APPEND);


$pipelineId = 3794653; /* Хантер */

if ($contactIdinAmo)
{
    $isNewCustomer = false;

    // $pipeStatusId = 36621253; /* Новая заявка */


} else
{
    $isNewCustomer = true;

    // предварительно создадим контакт в Амо - этого пока делать не нужно

    
    $creationResult = $amoApi->createContactSm(
        $name,
        $phone,
        $email
    );

    $contactIdinAmo = $creationResult['_embedded']['contacts'][0]['id'];
    
    // $pipeStatusId = 37304599; /* Новая заявка (неразобрано) */
}
echo "<pre>RESP_ID=\n" . print_r($amoApi->getRespId($contactIdinAmo), 1) . "</pre>";


// массив с данными полей по контакту
$customArr = [];

if ($phone)
{
    $customArr[] = [
        "field_id" => 241167,
        "values" => [
            [ 'value' => $amoApi->formatPhone($phone) ]
        ]
    ];
}

if ($email)
{
    $customArr[] = [
        "field_id" => 241169,
        "values" => [
            [ 'value' => $email ]
        ]
    ];
}


/* определяем ИД ответственного пользователя  */
if (!empty($sourceCode) && $sourceCode != 'wineexpress.ru')
{
    // берем из кода источника
    $mngEmail = $amoApi->getItemList(4475, ['query' => $sourceCode]);
    $respId = $amoApi->getMngIdByField($mngEmail);
}
elseif ($isNewCustomer)
{
    if ($sourceCode == 'wineexpress.ru' && !empty($couponVal))
    {
        // берем из промокода
        $mngEmail = $amoApi->getItemList(4473, ['query' => $couponVal]);
        $respId = $amoApi->getMngIdByField($mngEmail);
    }
    else
    {
        // $respId = 6426454; // id ответственного пользователя по-умолчанию
    }
}
else
{
    $respId = $amoApi->getRespId($contactIdinAmo);
}

// var_dump($isNewCustomer);
file_put_contents('neworderwh.log',  "\nIs new customer=\n" . print_r($isNewCustomer, 1), FILE_APPEND);


$respId = empty($respId)
    ? $amoApi->getRespId($contactIdinAmo)
    : $respId;

if ($respId == 6426454 || empty($respId))
{
    $pipeStatusId = 37304599; /* Новая заявка (неразобрано) */
} else
{
    $pipeStatusId = 36621253; /* Новая заявка */
}

// ставим заплатку на промокод ASTAFF
if ($couponVal == 'ASTAFF')
{
    $pipelineId = 3794710;
    $pipeStatusId = 36621661;
    $respId = 7330249; // Никитина Валентина
}
//

echo "<pre>Resp ID=\n" . print_r($respId, 1) . "</pre>";
file_put_contents('neworderwh.log',  "\nResp ID=\n" . print_r($respId, 1), FILE_APPEND);

/* данные конкретной сделки */
$newOrderData = 
[
    'name' => 'Новый заказ на сайте wineexpress.ru',
    'created_at' => time(),
    'price' => (int) round($order->getPrice(), 0),
    'status_id' => $pipeStatusId, 
    'pipeline_id' => $pipelineId,
    '_embedded' => [
        'contacts' => [
            [ 'id' => $contactIdinAmo ],
        ]
    ],
    'responsible_user_id' => $respId,
    
    'custom_fields_values' => [
        /* свойство id in bitrix */
        [
            'field_id' => 364641,
            "values" => [
                [ 'value' => $orderId ]
            ]
        ],
        /* свойство Код Источника */
        [
            'field_id' => 364771,
            "values" => [
                [ 'value' => $sourceCode ]
            ]
        ],
        /* свойство Комментарий пользователя */
        [
            'field_id' => 346517,
            "values" => [
                [ 'value' => \TruncateText($userDescription, 253) ]
            ]
        ],
        /* свойство промокод */
        [
            // 'field_id' => 364773,
            'field_id' => 870929,            
            "values" => [
                [ 'value' => $couponVal ]
            ]
        ],
        /* свойство с кастомными скидками */
        
        [
            'field_id' => 841545,
            "values" => [
                [ 'value' => json_encode($discountFieldData) ]
            ]
        ],
        
    ], 

];
// die(); // debug
$resCbAdd = $amoApi->createOrder([$newOrderData]);
// 
echo "<pre>Resnew\n". print_r($resCbAdd , 1 ) . "</pre>";
file_put_contents('neworderwh.log',  "\nOrderData\n" . var_export($newOrderData, 1), FILE_APPEND);
file_put_contents('neworderwh.log',  "\nResnew\n" . print_r($resCbAdd, 1), FILE_APPEND);



if ($createdOrderId = $resCbAdd['_embedded']['leads'][0]['id'])
{
    // теперь пытаемся прикрепить данные по товарам к сделке
    $resLink = $amoApi->linkEntityToOrder($createdOrderId, $itemData);

    // echo "<pre>Res link\n". print_r($resLink , 1 ) . "</pre>";

}

// теперь устанавливаем ответственного в контакт, того же, что и в заказе
if ($isNewCustomer)
{
    $amoApi->setRespToContact($contactIdinAmo, $respId);
}










// //////

/*
    Функция возвращает значение свойства по переданному коду свойства и массиву.
    Если не найдено, возвращает пустую строку
*/
function getPropsByName($propCode, $data)
{

    foreach ($data as $value)
    {
        if ($value['CODE'] == $propCode)
        {
            return $value['VALUE'];
        }
    }

    return '';

}


/*
    получение данных промокода на основании переданного ид заказа
 */

 function getCoupon($orderId)
 {

    // curl - ищем промокод
    $url = 'https://wineexpress.ru/wenewexchange/b24/getcoupon.php' . '?orderid=' . $orderId;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $resCurl = curl_exec($ch);
    curl_close($ch);

    $res = json_decode($resCurl);

    echo "<pre>CoupinVal\n". print_r($res->coupon, 1 ) . "</pre>";


    return $res->coupon;

 }