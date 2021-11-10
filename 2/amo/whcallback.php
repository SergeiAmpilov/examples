<?

// whcallback.php


require_once 'config.php';
require_once 'amowh.php';

// логи
file_put_contents('whcallback.log',  "\n" . print_r(date('l jS \of F Y h:i:s A'), 1), FILE_APPEND);
file_put_contents('whcallback.log',  "\n" . print_r($_POST, 1), FILE_APPEND);

$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);

$name   = $_REQUEST['NAME'];
$email  = $_REQUEST['EMAIL'];
$phone  = $_REQUEST['PHONE'];

$contactIdinAmo = $amoApi->getContactByPhone($phone);
file_put_contents('whcallback.log',  "\n1CIIA =\n" . print_r($contactIdinAmo, 1), FILE_APPEND);

$pipelineId = 3794653; /* Хантер */

if ($contactIdinAmo)
{

    $pipeStatusId = 36621253; /* Новая заявка */
    $respId = $amoApi->getRespId($contactIdinAmo); // 

} else
{

    // предварительно создадим контакт в Амо
    $creationResult = $amoApi->createContactSm(
        $name,
        $phone,
        $email
    );

    $contactIdinAmo = $creationResult['_embedded']['contacts'][0]['id'];

    $pipeStatusId = 37304599; /* Новая заявка (неразобрано) */

    $respId = 6518407; // Веремеев Никита    
    $amoApi->setRespToContact($contactIdinAmo, $respId);
    
}



// теперь добавляем сделку
// https://www.amocrm.ru/developers/content/api/leads

// $resCbAdd = $amoApi->createCallBack([
//     'add' => [
//         /* данные конкретной сделки */
//         [
//             'name' => 'Заказ обратного звонка на сайте',
//             'created_at' => time(),
//             'status_id' => 36622012, /* ВЗЯТЬ В РАБОТУ */
//             'pipeline_id' => 3794764, /* ОБЩАЯ */
//             'contacts_id' => [ $contactIdinAmo ],

//         ],
//     ]
// ]);


$resCbAdd = $amoApi->createOrder([
    
    /* данные конкретной сделки */
    [
        'name' => 'Заказ обратного звонка на сайте wineexpress.ru',
        'created_at' => time(),
        'status_id' => $pipeStatusId, 
        'pipeline_id' => $pipelineId, 
        // 'contacts_id' => [ $contactIdinAmo ],
        'responsible_user_id' => $respId,
        '_embedded' => [
            'contacts' => [
                [ 'id' => $contactIdinAmo ],
            ]
        ],
    ],
    
]);

// ставит ответственным менеджером Веремеева Никикту
