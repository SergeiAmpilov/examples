<?

namespace amowh;

class Amo
{
    private $accessToken;
    private $refreshToken;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $subdomain;
    private $allCatalog;
    private $userList;

    function __construct($clientId, $clientSecret, $redirectUri, $subdomain){
        $this->clientId         = $clientId;
        $this->clientSecret     = $clientSecret;
        $this->redirectUri      = $redirectUri;
        $this->subdomain        = $subdomain;        

        // tokens
        $tokens = $this->getAuthTokens();

        if (!empty($tokens['access_token']))
        {
            $this->accessToken = $tokens['access_token'];
        }

        if (!empty($tokens['refresh_token']))
        {
            $this->refreshToken = $tokens['refresh_token'];            
        }

        // check
        if( !$this->checkTokensIsCorrect() )
        {
            $newTokens = $this->getNewTokens();

            // rewrite
            if ($newTokens)
            {
                $this->setAuthTokens($newTokens);
            }
        }
		
    }

    /* AUTH +++ */

    // функция ищет сохраненные ранее значения токенов.
    // если нашла, возвращает их в виде массива
    // если не нашла - то false
    private function getAuthTokens()
    {
        $accessToken = file_get_contents('access.token');
        $refreshToken = file_get_contents('refresh.token');

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

    }

    // проверяет авторизационные токеты.
    // true - всё хорошо
    // false - авторизация не срабатывает
    public function checkTokensIsCorrect()
    {
        return $this->getAccount() !== false ;
    }


    // функция получения AcessToken по существующему Refresh Token
    private function getNewTokens()
    {
        //Формируем URL для запроса
        $link = 'https://' . $this->subdomain . '.amocrm.ru/oauth2/access_token'; 

        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $this->redirectUri,
        ];

        $res = $this->postCurl($link, $data, false);

        return $res;

    }

    private function setAuthTokens($tokens)
    {
        $this->accessToken = $tokens['access_token'];
        $this->refreshToken = $tokens['refresh_token'];

        file_put_contents('access.token', $this->accessToken);
        file_put_contents('refresh.token', $this->refreshToken);
    }
    /* AUTH --- */

    
    /* CATALOG +++ */


    // получает список товаров
    public function getCatalogList($filtData)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/catalog_elements?' . http_build_query($filtData);

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);
        
        return $res;
    }

    // добавляет список товаров в базу данных
    public function addCatalogElement($data)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/catalog_elements';

        $res = $this->postCurl($link, [
            'add' => $data,
        ]);

        return $res;

    }

    // функция обновляет данные элемента каталога товаров
    public function updateCatalogElement($data)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/catalog_elements';

        $res = $this->postCurl($link, [
            'update' => $data,
        ]);

        return $res;

    }

    // функция возвращает весь каталог товаров
    public function getWholeCatalog()
    {

        if (empty($this->allCatalog))
        {
            $resTotal = [];

            $pageNumber = 1;

            while ($pageNumber < 1000) {
                $res = $this->getCatalogList([
                    'catalog_id' => '4111',
                    'page' => $pageNumber,
                ]);

                if ($res['_embedded']['items'])
                {
                    foreach ($res['_embedded']['items'] as $value)
                    {
                        $resTotal[ $value['id'] ] = $value;
                    }

                    $pageNumber += 1 ;
                    continue ;
                } else
                {
                    break ;
                }
            }            

            $this->allCatalog = $resTotal;

        }

        return $this->allCatalog;

    }

    // осуществляет поиск товара по переданному артикулу
    // если товар есть, возвращает его ИД
    // если товара нет, возвращает false
    public function findCatalogElementByArtnumber($artnumber)
    {
        $wCatalog = $this->getWholeCatalog();

        // поиск

        $resFilt = array_filter($wCatalog, function ($item) use ($artnumber) {
            if (empty($item['custom_fields']))
            {
                return false;
            }

            $res = false;
            foreach ($item['custom_fields'] as $v)
            {
                if ($v['code'] !== 'SKU')
                {
                    continue ;
                }

                return $v['values'][0]['value'] == $artnumber;
            }

            return $res;
        });

        if (empty($resFilt))
        {
            return false ;
        } else
        {
            return current($resFilt)['id'];
        }

    }

    // осуществляет поиск товара по переданному артикулу
    // если товар есть, возвращает его ИД
    // если товара нет, возвращает false
    public function findCatalogElementByArtnumberFast($artnumber)
    {

        $resArti = $this->getCatalogList([
            'catalog_id' => 4111,
            'term' => $artnumber,
        ]);

        if ( $resList = empty($resArti['_embedded']['items']) 
                        ? false
                        : $resArti['_embedded']['items'] )
        {
            foreach ($resList as $item)
            {
                foreach ($item['custom_fields'] as $val)
                {
                    if ($val['code'] == 'SKU' && current($val['values'])['value'] == $artnumber )
                    {
                        return $item['id'];
                    }
                }
            }

            return false;
            
        } else
        {
            return false;
        }

        

    }

    /* CATALOG -- */


    /* CONTACTS +++ */

    public function formatPhone($phone)
    {

        // return $phone;

        $phone = preg_replace('/[^0-9]/', '', $phone); // вернет 79851111111

        // добавляем лидирующую 7 или 8
        if (($phone[0] != '7' || $phone[0] != '8') && strlen($phone) < 11) {
            $phone = '7' . $phone;
        }
    
    
    
        if (strlen($phone) != 11 && ($phone[0] != '7' || $phone[0] != '8')) {
            return $phone;
        }
        $phone_number['dialcode'] = substr($phone, 0, 1);
        $phone_number['code']  = substr($phone, 1, 3);
        $phone_number['phone'] = substr($phone, -7);
        $phone_number['phone_arr1'] = substr($phone_number['phone'], 0, 3);
        $phone_number['phone_arr2'] = substr($phone_number['phone'], 3, 4);
    //      $phone_number['phone_arr3'] = substr($phone_number['phone'], 5, 2);
        
        $format_phone = '+7' . $phone_number['code'] . $phone_number['phone_arr1'] . $phone_number['phone_arr2'];
        
        return $format_phone;

    }

    // метод возвращает список всех контактов, что есть в базе
    public function getContacts($query = false)
    {
        // /api/v4/contacts
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/contacts';



        if ($query)
        {
            $link .= "?" . http_build_query([
                'query' => $query,
            ]);
        }

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);
        
        return $res;

    }

    public function getContactsByPage($pageNumber = 0)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/contacts';

        if ($pageNumber)
        {
            $link .= "?" . http_build_query([
                'page' => (int) $pageNumber,
            ]);
        }

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);
        
        return $res;



    }

    // функция получает (и зовращает) номер телефона на основании ИД контакта
    public function getPhoneFromContact($contactId)
    {

        if ( !$resContact = $this->getContacts($contactId) )
        {
            return false;
        }
        
        return current($resContact['_embedded']['contacts'])['custom_fields_values'][0]['values'][0]['value'];

    }

    // возвращает id контакта по переданному номеру телефона
    // или false, если контакта с таким номером телеофна в системе не существует
    public function getContactByPhone($phone)
    {
        // return false ; // пока не работает фильтрация вынужден отключить

        $phoneFormated = $this->formatPhone($phone);

        if ( $resGet = $this->getContacts($phoneFormated) )
        {
            return empty( current($resGet['_embedded']['contacts'])['id'] )
                    ? false
                    : current($resGet['_embedded']['contacts'])['id'] ;   
        }
        
        return false;

    }

    // по переданному ID контакта, функция возвращает ИД ответственного менеджера
    public function getRespId($contactId)
    {

        $link = 'https://' . $this->subdomain . ".amocrm.ru/api/v4/contacts/$contactId";

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        if ($res = $this->getCurl($link, $headers))
        {
            return $res['responsible_user_id'];            
        }
    }

    // данный метод создает контакты в системе пакетно
    public function createContact($data)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/contacts';

        $res = $this->postCurl($link, $data);

        return $res;

    }

    // функция создает контакт на основании переданных аргументов
    public function createContactSm($name = '', $phone = '', $email = '', $respId = 0)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/contacts';
        
        $customArr = [];

        if ($phone)
        {
            $customArr[] = [
                "field_id" => 241167,
                "values" => [
                    [ 'value' => $this->formatPhone($phone) ]
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

        $data = [
            [
                'name' => trim( $name ),
                'responsible_user_id' => (int) $respId,
                'custom_fields_values' => $customArr,
            ],
        ];

        $res = $this->postCurl($link, $data);

        return $res;

    }

    public function setRespToContact($contactId, $respId)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/contacts/' . $contactId;

        return $this->patchCurl($link,
            ['responsible_user_id' => $respId]
        );

    }

    /* CONTACTS --- */

    /* ORDER +++ */

    // метод получает (и возвращает) данные по заказу на основании переданного ИД
    public function getOrder($orderId)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/leads?' . 
                    http_build_query([
                        'id' => $orderId,
                    ]);

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);
        
        return current($res['_embedded']['items']);

    }

    // функция устанавливает ответственного в заказ
    public function setRespToOrder($respId)
    {
        return false;
    }

    public function createOrder($data)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/leads';

        $res = $this->postCurl($link, $data);

        return $res;

    }

    public function linkEntityToOrder($entityId, $data)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/leads/' . $entityId . '/link';

        $res = $this->postCurl($link, $data);

        return $res;

    }
    /* ORDER --- */


    /* pipelines & Call-Back ++ */

    public function createCallBack($data)
    {
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/leads';

        $res = $this->postCurl($link, $data);

        return $res;


    }

    // функция возвращает id всех статусов воронки на основании переданного ИД воронки
    public function getPipelines($pipelineId = 0)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v2/pipelines?id=' . $pipelineId;

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);
        
        return $res;

    }

    public function addUnsortedDeal($data)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/leads/unsorted/forms';

        $res = $this->postCurl($link, $data);

        return $res;

    }

    /* pipelines & Call-Back -- */

    /* распределение заказов по ответственным +++ */

    // по переданному ИД Битрикс функция возвращает ид Заказа
    // или false - если такого ИД не найдено
    public function getPreviousOrderById($orderId)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/leads?' . 
                    http_build_query([ 'query' => $orderId]);
        
        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        if ($res = $this->getCurl($link, $headers))
        {
            return current($res['_embedded']['leads'])['id'];
        } else
        {
            return false;
        }

    }

    // функция получает данные списка по переданному id- списка и запросу
    // и возвращает результат в виде емайл менеджера
    public function getItemList($listId, $data = false)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/catalogs/' . $listId . '/elements';

        if ($data)
        {
            $link .= '?' . http_build_query($data);
        }

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        if ($res = $this->getCurl($link, $headers))
        {
            return current($res['_embedded']['elements'])['custom_fields_values'][0]['values'][0]['value'];

        } else
        {
            return false;
        }

    }

    public function getMngIdByField($fieldValue, $fieldCode = 'email')
    {
        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];


        if (empty($this->userList))
        {
            // заполним список пользователей
            $link1 = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/users';

            if ( $res1 = $this->getCurl($link1, $headers) )
            {
                $this->userList = $res1['_embedded']['users'];
            } else
            {
                return false;
            }
        }

        //

        return current(array_filter($this->userList, 
            function ($item) use ($fieldValue, $fieldCode) {
                return $fieldValue == $item[$fieldCode];
            }))['id'];

    }



    /* распределение заказов по ответственным --- */


    // получение данных аккаунта
    public function getAccount()
    {

        //Формируем URL для запроса
        $link = 'https://' . $this->subdomain . '.amocrm.ru/api/v4/leads';
        

        $headers = [
            'Authorization: Bearer ' . $this->accessToken
        ];

        $res = $this->getCurl($link, $headers);

        return $res;

    }


    // функция произовдит первичную аутентификацию в аккаунте по упрощенному
    // методу, на основе переданного кода авторизации
    public function getFirstAuth($authCode)
    {

        $link = 'https://' . $this->subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $authCode,
            'redirect_uri' => $this->redirectUri,
        ];

        $res = $this->postCurl($link, $data, false);

        return $res;

    }

    // get curl запрос
    private function getCurl($link, $headers)
    {
        //Сохраняем дескриптор сеанса cURL
        $curl = curl_init(); 

        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        //Инициируем запрос к API и сохраняем ответ в переменную
        $out = curl_exec($curl); 
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];

        try
        {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new \Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        }
        catch(\Exception $e)
        {
            echo "<pre>" . print_r(
                'Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode()
                , 1) . "</pre>";
            // die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
            
            return false;
        }

        $response = json_decode($out, true);

        return $response;

    }
    
    // post curl запрос
    private function postCurl($link, $data, $needAuth = true )
    {

        //Сохраняем дескриптор сеанса cURL
        $curl = curl_init();

        

        if ($needAuth)
        {
            $header = [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type:application/json'
            ];
            
        } else 
        {
            $header = ['Content-Type:application/json'];
            
        }


        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_HEADER, false);       
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);


        //Инициируем запрос и сохраняем ответ в переменную
        $out = curl_exec($curl); 

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
        $code = (int)$code;

        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];

        try
        {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new \Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        }
        catch(\Exception $e)
        {
            

            
            echo "<pre>" . print_r(
                'Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode()
                , 1) . "</pre>";
            

            // die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());

            return json_decode($out, true);
            return false; // пока полчкаливо возвращаем false
            
        }

        /**
         * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
         * нам придётся перевести ответ в формат, понятный PHP
         */
        $response = json_decode($out, true);

        return $response;

    }

    // post curl запрос
    public function patchCurl($link, $data, $needAuth = true )
    {

            //Сохраняем дескриптор сеанса cURL
            $curl = curl_init();

    

            if ($needAuth)
            {
                $header = [
                    'Authorization: Bearer ' . $this->accessToken,
                    'Content-Type:application/json'
                ];
                
            } else 
            {
                $header = ['Content-Type:application/json'];
                
            }
    
    
            /** Устанавливаем необходимые опции для сеанса cURL  */
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
            curl_setopt($curl, CURLOPT_URL, $link);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_HEADER, false);       
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    
    
            //Инициируем запрос и сохраняем ответ в переменную
            $out = curl_exec($curl); 
    
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
    
            /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
            $code = (int)$code;
    
            $errors = [
                400 => 'Bad request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not found',
                500 => 'Internal server error',
                502 => 'Bad gateway',
                503 => 'Service unavailable',
            ];
    
            try
            {
                /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
                if ($code < 200 || $code > 204) {
                    throw new \Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
                }
            }
            catch(\Exception $e)
            {
                
    
                
                echo "<pre>" . print_r(
                    'Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode()
                    , 1) . "</pre>";
                
    
                // die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    
                return json_decode($out, true);
                return false; // пока полчкаливо возвращаем false
                
            }
    
            /**
             * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
             * нам придётся перевести ответ в формат, понятный PHP
             */
            $response = json_decode($out, true);
    
            return $response;

    }

    
}