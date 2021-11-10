<?

// данный скрипт производит загрузку каталога товаров из
// базы данных сайта в Амо


/*

https://wineexpress.ru/wenewexchange/amo/test.php

*/


require_once 'config.php';
require_once 'amowh.php';

$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);

echo "<pre>" .  print_r(
    $amoApi->patchCurl(
        'https://' . 'wineexpress' . '.amocrm.ru/api/v4/leads/13340391',
        [
            "price" => (int) 111,
        ]
    ), 1) ."</pre>";


die();

$curPageNumber = 1;

$contactList = [];

do {
    $resPageGet = $amoApi->getContactsByPage($curPageNumber);
    // echo '<pre>' . print_r($resPageGet['_page'], 1) . '</pre>';
    // echo '<pre>' . print_r($resPageGet, 1) . '</pre>';


    // парсим контакты из выборки
    foreach ($resPageGet['_embedded']['contacts'] as $value)
    {
        //
        $curId = $value['id'];
        $curPhone = current(
            array_filter($value['custom_fields_values'], function($item){ return $item['field_code'] == 'PHONE';})
        )['values'][0]['value'];

        if (empty($curPhone))
        {
            continue;
        }

        if (array_key_exists($curPhone, $contactList))
        {
            $contactList[$curPhone][] = $curId;    
        } else
        {
            $contactList[$curPhone] = [$curId];
        }
    }


    $goNext = !empty($resPageGet);
    $curPageNumber += 1;

} while ($goNext);


// выводим контакты

// выведем только дубли
foreach ($contactList as $kTel => $val) {
    if (count($val) > 1)
    {
        echo "<pre>Для телефона номер {$kTel} обнаружены дубли (id контактов):<br>" . print_r($val, 1) . "</pre>";
    }
}
// echo '<pre>' . print_r($contactList, 1) . '</pre>';


   