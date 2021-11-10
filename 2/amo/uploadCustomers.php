<?

// uploadCustomers.php
// скрипт выгрузки клиентской базы в Амо. Базу контактов берем из Б24



require_once 'config.php';
require_once 'amowh.php';

require_once('../b24/config.php');
require_once('../b24/b24wh.php');

$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);
$b24 = new \b24\webHook(B24DOMAIN, B24KEY, B24CODE);

// $nextPage = 5600; // внимание, для полной выгрузки контактов тут должен стоять 0!
$nextPage = 0; // внимание, для полной выгрузки контактов тут должен стоять 0!

$debugCounter = 2; // DEBUG

do {

    $cList = $b24->GetList('contact', [
        'filter' => [
            'HAS_PHONE' => 'Y',
        ]
    ], $nextPage);

    foreach ($cList->result as $item)
    {

        // $debugCounter -= 1; if ($debugCounter < 0) { break; } // DEBUG

        $resItem = $b24->Get('contact', $item->ID);

        // echo "<pre>" . print_r($resItem->result->ID, 1) . "</pre>";
        // echo "<pre>" . print_r($resItem->result, 1) . "</pre>";

        $contIdinAmo = $amoApi->getContactByPhone($resItem->result->PHONE[0]->VALUE);

        if ($contIdinAmo) 
        {
            echo "<pre>" . print_r("allready exist in AMO with ID = $contIdinAmo", 1) . "</pre>"; 
        } else
        {
            echo "<pre>" . print_r("Need to create new contact with phone number = " . 
                $resItem->result->PHONE[0]->VALUE, 1) . "</pre>";
            
            $arName = [];
            $arName[] = $resItem->result->NAME;
            $arName[] = $resItem->result->SECOND_NAME;
            $arName[] = $resItem->result->LAST_NAME;


            $creationResult = $amoApi->createContactSm(
                trim( implode ( ' ' , $arName ) ),
                empty($resItem->result->PHONE[0]->VALUE) ? '' : $resItem->result->PHONE[0]->VALUE,
                empty($resItem->result->EMAIL[0]->VALUE) ? '' : $resItem->result->EMAIL[0]->VALUE,
                (int) getAmoId($resItem->result->ASSIGNED_BY_ID)
            );

            echo "<pre> === creation result ===</pre>";
            echo "<pre>" . print_r($creationResult, 1) . "</pre>";
        }

    }

} while ( $nextPage = empty($cList->next) ? false : $cList->next );


// функция возвращает ИД менеджера в АМО на основании ИД менеджера из Битрикс
function getAmoId($bitrixId)
{

    global $MNG_LIST;

    if (empty($bitrixId))
    {
        return false;
    }

    $res = array_filter($MNG_LIST, function ($item) use ($bitrixId) {
        return $item['bitrix_id'] == $bitrixId;
        // return 1;
    });

    // echo print_r($res, 1);

    if (empty($res)) {
        return false;
    }

    return current($res)['amo_id'];

}


// echo "<pre>" . print_r($cList, 1) . "</pre>";
// die();
