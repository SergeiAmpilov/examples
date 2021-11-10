<?

// setresptoorder.php
// webhook обработки алгоритма установки ответственного в заказ

file_put_contents('setresptoorder.log',  "\nStart set Responce to Order\t" . print_r(date('l jS \of F Y h:i:s A'), 1), FILE_APPEND);

if ( !$orderId = $_REQUEST['id'] )
{
    file_put_contents('setresptoorder.log',  "\nDie! Empty order id. \t", FILE_APPEND);
    die('empty order id');
}


require_once 'config.php';
require_once 'amowh.php';


$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);

if ( !$orderData = $amoApi->getOrder($orderId))
{
    file_put_contents('setresptoorder.log',  "\nDie! Cant get order data by order id {$orderId}. \t", FILE_APPEND);
    die("Cant get order data by order id {$orderId}");
}


// 1 - классификация по номеру телефона

// пытаемся получить ИД контакта
$contactId = current($orderData['contacts']['id']);

// echo "<pre>" . print_r($contactId, 1) . "</pre>";

// теперь получаем номер телефона из контакта
if ( !$resContactPhone = $amoApi->getPhoneFromContact($contactId))
{
    file_put_contents('setresptoorder.log',  "\nDie! Cant get phone number for contact {$contactId}. \t", FILE_APPEND);
    die("Cant get phone number for contact {$contactId}");
}

// echo "<pre>" . print_r($resContactPhone, 1) . "</pre>";

// теперь ищем первое вхождение контакта с таким номером телефона в базу

$resContList = $amoApi->getContacts($resContactPhone);
// echo "<pre>" . print_r($resContList, 1) . "</pre>";

if (!$cList = $resContList['_embedded']['contacts'])
{
    file_put_contents('setresptoorder.log',  "\nDie! Cant get contact List by phone. \t", FILE_APPEND);
    die("Cant get contact List by phone");
}

$respId = $cList[0]['responsible_user_id'];

if (empty($respId) )
{
    // значит анализируем Источники и промокоды.
} else
{

    $amoApi->setRespToOrder($respId);

}
