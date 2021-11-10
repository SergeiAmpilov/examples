<?php
namespace Upside\Exchange\Deliveries;

// use Exception;
use Bitrix\Sale;
use CSaleLocation;
use SoapClient;
use SoapFault;
use Bitrix\Main\Config\Option;
use Upside\Exchange\ExchangeHelper;

// require_once($_SERVER["DOCUMENT_ROOT"] ."/bitrix/modules/main/include/prolog_before.php");
// require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sale/mysql/location.php");

class DeliveriesDpd  extends ExchangeHelper {

  const apiUrls = array(
    'dev'  => 'http://wstest.dpd.ru/services/', // Тестовый
    'prod' => 'http://ws.dpd.ru/services/' // Боевой
  );


  /*
    Отправка заказ в ТК
  */
  public static function sendToDPD($order, $delivery_id) {
    $order_id = $order->getId();

    //Тип доставки
    $serviceVariant = '';
    if ($delivery_id == 6) {
      $serviceVariant = 'ДТ';
    }
    if ($delivery_id == 7) {
      $serviceVariant = 'ДД';
    }

    // Свойства
    $propertyArray = $order->getPropertyCollection()->getArray();
    $serviceCode = false;
    $receiverAddressterminalCode = false;
    $receiverAddress = array(
      'name' => '',
      'phone' => '',
      'countryName' => '',
      'city' => '',
      'street' => '',
      'streetAbbr' => '',
      'house' => '',
      'houseKorpus' => '',
      'flat' => ''
    );
    $PARSE_ADDRESS = false;
    foreach ($propertyArray['properties'] as $propertyArrayItem) {
      if ($propertyArrayItem['CODE'] == 'DPD_SERVICE_CODE') {
        $serviceCode = $propertyArrayItem['VALUE'][0];
        if (strlen($serviceCode) < 2) {
          $serviceCode = Option::get('ipol.dpd', 'DEFAULT_TARIFF_CODE');
        }
      }
      if ($propertyArrayItem['CODE'] == 'DPD_TERMINAL_CODE') {
        $receiverAddressterminalCode = $propertyArrayItem['VALUE'][0];
      }
      if ($propertyArrayItem['CODE'] == 'FIO') {
        $receiverAddress['name'] = $propertyArrayItem['VALUE'][0];
      }
      if ($propertyArrayItem['CODE'] == 'PHONE') {
        $receiverAddress['phone'] = $propertyArrayItem['VALUE'][0];
      }
      if ($propertyArrayItem['CODE'] == 'LOCATION') {
        $arBxLocation = CSaleLocation::GetByID($propertyArrayItem['VALUE'][0], 'ru');
        $receiverAddress['countryName'] = $arBxLocation['COUNTRY_NAME'];
      }
      if ($propertyArrayItem['CODE'] == 'CITY') {
        $receiverAddress['city'] = $propertyArrayItem['VALUE'][0];
      }
      if ($propertyArrayItem['CODE'] == 'PARSE_ADDRESS') {
        $PARSE_ADDRESS = json_decode(urldecode($propertyArrayItem['VALUE'][0]));
      }
    }    
    if ($serviceCode == false) {
      ExchangeHelper::staticLogFuckUp('Попытка отправки заказа с ID: '.$order_id.' неуспешна. Отсутсвует DPD_SERVICE_CODE');
      return false;
    }
    


    if ($PARSE_ADDRESS) {
      $receiverAddress['countryName'] = $PARSE_ADDRESS->country;
      $receiverAddress['city'] = $PARSE_ADDRESS->city;
      $receiverAddress['street'] = $PARSE_ADDRESS->street;
      $receiverAddress['streetAbbr'] = $PARSE_ADDRESS->street_type;
      $receiverAddress['house'] = $PARSE_ADDRESS->house;
      $receiverAddress['houseKorpus'] = $PARSE_ADDRESS->block;
      $receiverAddress['flat'] = $PARSE_ADDRESS->flat;
    }

    // Опции
    $senderAddressName = Option::get('ipol.dpd', 'SENDER_NAME');
    $senderAddressContactFio = Option::get('ipol.dpd', 'SENDER_FIO');
    $senderAddressContactPhone = Option::get('ipol.dpd', 'SENDER_PHONE');
    $clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
    $clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');
    $isTest = Option::get('ipol.dpd', 'IS_TEST');
    $sender = Option::get('ipol.dpd', 'SENDERS', 'a:0:{}');
    $sender  = unserialize($sender) ?: [];

    $activeSender = array(
      'countryName' => '',
      'city' => '',
      'terminalCode' => '',
      'street' => '',
      'house' => '',
      'houseKorpus' => '',
    );
    foreach($sender as $sid => $s) {
      // Отправитель по умолчанию
      if ($s['DEFAULT'] == 'Y') {
        $arBxLocation = CSaleLocation::GetByID((int) $s['LOCATION'], "ru");
        $activeSender['countryName'] = $arBxLocation['COUNTRY_NAME'];
        $activeSender['city'] = $arBxLocation['CITY_NAME'];
        $activeSender['terminalCode'] = $s['TERMINAL_CODE'];
        $activeSender['street'] = $s['STREET'];
        $activeSender['house'] = $s['HOUSE'];
        $activeSender['houseKorpus'] = $s['KORPUS'];
      }
    }

    $cargoWeight = 0;
    foreach ($order->getBasket() as $basketItem) {
      $cargoWeight += $basketItem->getField('QUANTITY') * 0.350;
    }
    
    $uri = self::apiUrls['prod'];
    if ($isTest) {
      $uri = self::apiUrls['dev'];
    }
    
    $client = new SoapClient($uri."order2?wsdl");

    $arData = array();

    // Данные авторизации
    $arData['auth'] = array(
      'clientNumber' => $clientNumber,
      'clientKey' => $clientKey
    );

    // Отправитель
    $arData['header'] = array(
      'datePickup' => date('Y-m-d',strtotime(date("Y-m-d", mktime()) . " + 1 day")), // !!!!!!!! Дата приёма груза
      'senderAddress' => array(
        'name' => $senderAddressName, // Название отправителя. В случае, когда адрес приёма/доставки – это магазин, филиал компании, дилерский центр и т.п., в эту строку пишется его название. Если доставка осуществляется физическому лицу, то пишется Ф.И.О получателя.
        'terminalCode' => $activeSender['terminalCode'], // Данное поле является обязательным для вариантов перевозки «ДТ», «ТД» или «ТТ» в методе createOrder. Если указывается данное поле, то адрес указывать не требуется.                                                                               
        'countryName' => $activeSender['countryName'],
        'city' => $activeSender['city'],
        'street' => $activeSender['street'], // Улица (формат ФИАС)
        'house' => $activeSender['houseKorpus'], // Дом
        'contactFio' =>  $senderAddressContactFio, // Контактное лицо
        'contactPhone' => $senderAddressContactPhone, // Контактный телефон
        ),
      'pickupTimePeriod' => '9-18' // Интервал времени приёма груза.
    );
    
    // Товары
    $arData['order'] = array(
      'orderNumberInternal' => $order_id,
      'serviceCode' => $serviceCode, // Код услуги DPD.
      'serviceVariant' => $serviceVariant, // !!!!! Вариант доставки. Доступно 4 варианта: ДД, ДТ, ТД и ТТ.
      'cargoNumPack' => 1, // !!!!! Количество грузомест (посылок) в отправке
      'cargoWeight' => $cargoWeight, // Вес отправки, кг
      'cargoRegistered' => false, // Ценный груз.
      'cargoCategory' => 'Обувь', // Содержимое отправ ки
      'receiverAddress' => array(
        'name' => $receiverAddress['name'], // Название получателя. В случае, когда адрес приёма/доставки – это магазин, филиал компании, дилерский центр и т.п., в эту строку пишется его название. Если доставка осуществляется физическому лицу, то пишется Ф.И.О получателя.
        'terminalCode' => $receiverAddressterminalCode, // Данное поле является обязательным для вариантов перевозки «ДТ», «ТД» или «ТТ» в методе createOrder. Если указывается данное поле, то адрес указывать не требуется.                                                                               
        'countryName' => $receiverAddress['countryName'],
        'city' => $receiverAddress['city'],
        'street' => $receiverAddress['street'], // Улица (формат ФИАС)
        'streetAbbr' => $receiverAddress['streetAbbr'], // Сокращения типа улицы (ул, пр-т, б-р и т.д.)
        'house' => $receiverAddress['house'], // Дом
        'houseKorpus' => $receiverAddress['houseKorpus'], // Корпус
        'flat' => $receiverAddress['flat'], // Квартира
        'contactFio' => $receiverAddress['name'], // Контактное лицо
        'contactPhone' => $receiverAddress['phone'], // Контактный телефон
      ), // Информация о получателе
    );


    $arRequest['orders'] = $arData;
    $req = $client->createOrder($arRequest);
    $res = self::stdToArray($req);
    switch ($res['return']['status'][0]) {
      case 'OK': // Заказ создан
        self::setTrack($order_id, $res['return']['orderNum'][0]);
        return true;
        break;
      case 'OrderPending': // Заказ отменен
        // сделать запрос на удаление !!!!!!!!!!!
        break;
      case 'OrderPending': // Заказ дорабатывается вручную менеджером
        return true;
        break;
      case 'OrderError': // Неизведанная ошибка
        ExchangeHelper::staticLogFuckUp('Попытка отправки заказа с ID: '.$order_id.' неуспешна. Ответ от DPD: '.print_r ($res,true));
        return false;
        break;
      case 'OrderDuplicate': // Заказ уже создан
        self::checkTrack($order_id);
        return true;
        break;
      
      default:
        ExchangeHelper::staticLogFuckUp('Попытка отправки заказа с ID: '.$order_id.' неуспешна. Ответ от DPD: '.print_r ($res,true));
        return false;
        break;
    }
    
  }

  /*
    Удаление заказа из ТК
  */
  public static function removeFromDPD($order) {
    $order_id = $order->getId();

    // Свойства
    $propertyArray = $order->getPropertyCollection()->getArray();
    $orderNum = false;
    foreach ($propertyArray['properties'] as $propertyArrayItem) {
      if ($propertyArrayItem['CODE'] == 'TRACK_NUMBER') {
        $orderNum = $propertyArrayItem['VALUE'][0];
      }
    }    

    // Опции
    $isTest = Option::get('ipol.dpd', 'IS_TEST');
    $clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
    $clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');

    $uri = self::apiUrls['prod'];
    if ($isTest) {
      $uri = self::apiUrls['dev'];
    }

    $client = new SoapClient($uri."order2?wsdl"); 
    
    $arData = array();

    // Данные авторизации
    $arData['auth'] = array(
      'clientNumber' => $clientNumber,
      'clientKey' => $clientKey
    );
    
    $arData['cancel'] = array(
      'orderNumberInternal' => $order_id, // Номер заказа в информационной системе клиента
      'orderNum' => $orderNum // Номер заказа DPD
    );

    $arRequest['orders'] = $arData;
    $req = $client->cancelOrder($arRequest); 
    $res = self::stdToArray($req);
    
    // ExchangeHelper::staticLogFuckUp(print_r($arRequest, true));
    // ExchangeHelper::staticLogFuckUp(print_r($res, true));

    switch ($res['return']['status'][0]) {
      case 'Canceled': // Операция выполнена успешно
        self::setTrack($order_id, '');
        return true;
        break;
      case 'NotFound': //	Данные не найдены
        return true;
        break;
      case 'CallDPD': // Состояние заказа не позволяет отменить заказ самостоятельно, для отмены заказа необходим звонок в Конткат-Центр.
        ExchangeHelper::staticLogFuckUp('Попытка удаления заказа с ID: '.$order_id.' неуспешна. Ответ от DPD: '.print_r ($res,true));
        return false;
        break;
      case 'CanceledPreviously': //	Отменено ранее
        self::setTrack($order_id, '');
        return true;
        break;
      case 'Error': // Текст сообщения об ошибке
        ExchangeHelper::staticLogFuckUp('Попытка удаления заказа с ID: '.$order_id.' неуспешна. Ответ от DPD: '.print_r ($res,true));
        return false;
        break;
    }

  }
  
  static function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key=>$item){
      $rc[$key]= (array)$item;
      foreach($rc[$key] as $keys=>$items){
        $rc[$key][$keys]= (array)$items;
      }
    }
    return $rc;
  }

  /*
    Поиск трекномера в ТК и установка его ордеру
  */
  public static function checkTrack($order_id) {

    // Опции
    $clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
    $clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');
    $isTest = Option::get('ipol.dpd', 'IS_TEST');

    $uri = self::apiUrls['prod'];
    if ($isTest) {
      $uri = self::apiUrls['dev'];
    }
    
    $client = new SoapClient($uri."order2?wsdl");

    $arData = array();

    // Данные авторизации
    $arData['auth'] = array(
      'clientNumber' => $clientNumber,
      'clientKey' => $clientKey
    );

    // Данные заказа
    $arData['order'] = array(
      'orderNumberInternal' => $order_id
    );

    $arRequest['orderStatus'] = $arData;

    $req = $client->getOrderStatus($arRequest); 
    $res = self::stdToArray($req);

    if ($res['return']['errorMessage'][0] == '' && $res['return']['orderNum'][0] != '') {
      self::setTrack($order_id, $res['return']['orderNum'][0]);
    } else {
      ExchangeHelper::staticLogFuckUp('Запрос трек номера заказа с ID: '.$order_id.' неуспешна. Ответ от DPD: '.print_r ($res,true));
    }

  }

  /*
    Установка трекномера ордеру
  */
  private static function setTrack($order_id, $track_number) {
    $order = Sale\Order::load($order_id);
    $collection = $order->getPropertyCollection();
    foreach ($collection as $item) {
      $prop = $item->getProperty();
      if($prop['ID'] == 12) {
        $item->setValue($track_number);
        $item->save();
      }
    }
    $order->save();
  }


  public static function getStatus($track_number) {
	  $clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
	  $clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');
	  $isTest = Option::get('ipol.dpd', 'IS_TEST');

	  $uri = self::apiUrls['prod'];
	  if ($isTest) {
		  $uri = self::apiUrls['dev'];
	  }
	  
	  /* 


	  $client = new SoapClient($uri."tracing1-1?wsdl",array(
       'exceptions' => true,
    ));

	  $arData['auth'] = array(
		  'clientNumber' => $clientNumber,
		  'clientKey' => $clientKey
	  );
	  $arData['clientOrderNr'] = $track_number;
	  $request['request'] =  $arData;
	  
	  //var_dump($request);
	  //var_dump($uri);
	  
		try {
		  $response = $client->getStatesByClientOrder($request);
		  $res = self::stdToArray($response);
		  var_dump($res);
		  
		  if(isset($res['return']['states']['newState'])) {
			 return $res['return']['states']['newState'];
		  }
		  
		} catch(SoapFault $ex){
			//var_dump($ex->faultcode, $ex->faultstring, $ex->faultactor, $ex->detail, $ex->_name, $ex->headerfault);
		} */
	
	  /*
		NewOrderByClient – оформлен новый заказ по инициативе клиента
		NotDone – заказ отменен
		OnTerminalPickup – посылка находится на терминале приема отправления
		OnRoad – посылка находится в пути (внутренняя перевозка DPD)
		OnTerminal – посылка находится на транзитном терминале
		OnTerminalDelivery – посылка находится на терминале доставки
		Delivering – посылка выведена на доставку
		Delivered – посылка доставлена получателю
		Lost – посылка утеряна
		Problem – с посылкой возникла проблемная ситуация
		ReturnedFromDelivery – посылка возвращена с доставки
		NewOrderByDPD – оформлен новый заказ по инициативе DPD
	   */
	   
		$client = new SoapClient($uri."event-tracking",array(
			'exceptions' => true,
			'trace' => true
		));
		
	    $arData['auth'] = array(
		  'clientNumber' => $clientNumber,
		  'clientKey' => $clientKey
	  );
	  $arData['maxRowCount'] = 99999;
	  $request['request'] =  $arData;
	   

  } 
  
	public static function getStatuses() {
		self::log('Получение статустов от DPD', 'DPD_GET_STATUS.txt');
		
	  $clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
	  $clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');
	  $isTest = Option::get('ipol.dpd', 'IS_TEST');

	  $uri = self::apiUrls['prod'];
	  if ($isTest) {
		  $uri = self::apiUrls['dev'];
	  }
	  
	  $client = new SoapClient($uri."event-tracking?wsdl",array(
			'exceptions' => true,
			'trace' => true
		));
		
	    $arData['auth'] = array(
		  'clientNumber' => $clientNumber,
		  'clientKey' => $clientKey
	  );
	  $arData['maxRowCount'] = 99999;
	  $request['request'] =  $arData;
	  
	  try {
		  $response = $client->getEvents($request);  
		 // self::log($request, 'DPD_GET_STATUS_soap.txt');
		 // self::log($client->__getLastRequest(), 'DPD_GET_STATUS_soap.txt');
		 // self::log($client->__getLastResponse(), 'DPD_GET_STATUS_soap.txt');
		  
		  $res = self::stdToArray($response);
		  //var_dump($res['return']['event']);
		  
		  self::log('Получение успешно !', 'DPD_GET_STATUS.txt');
		
		  
		  if(!isset($res['return']['event'])) {
			  return [];
		  }
		  
		  $events_result = [];
		  
		  foreach($res['return']['event'] as $event) {
			  //echo $event->clientOrderNr . " - " . strtotime($event->eventDate) . PHP_EOL;
			  //var_dump($event);
			  $goods = false;
			  if(+$event->eventNumber === 3304 || +$event->eventNumber === 3305) {
				  foreach($event->parameter as $param) {
					if($param->paramName === 'OrderWorkCompleted') {
						$goods = $param->value;
					}
				  }
			  }
			  
			  $events_result[$event->clientOrderNr][strtotime($event->eventDate)] = [
				"eventNumber" => $event->eventNumber,
				"eventCode" => $event->eventCode,
				"eventName" => $event->eventName,
				"eventGoods" => $goods
			  ];
		  }
		  
		  foreach($events_result as $key => $order) {
			  krsort($order);
			  $events_result[$key] = $order;
		  }
		  
		  
		  foreach($events_result as $key => $orders) {
				self::log("Заказ с номером " . $key, 'DPD_GET_STATUS.txt');
				foreach($orders as $order) {
				   self::log( $order['eventName'] . "[".$order['eventNumber']."]", 'DPD_GET_STATUS.txt');
				}
		  }
		 
		  
		  return [$res['return']['docId'], $events_result];
		  
		} catch(SoapFault $ex){
			var_dump($ex->faultcode, $ex->faultstring, $ex->faultactor, $ex->detail, $ex->_name, $ex->headerfault);
			self::log("Провал: ", 'DPD_GET_STATUS.txt');
			self::log([$ex->faultcode, $ex->faultstring, $ex->faultactor, $ex->detail, $ex->_name, $ex->headerfault], 'DPD_GET_STATUS.txt');
		}
	}
	
	public static function getStatusesOK($docId) {
		self::log("Закрытие событий документа: " . $docId , 'DPD_GET_STATUS.txt');
		
		$clientNumber = Option::get('ipol.dpd', 'KLIENT_NUMBER');
		$clientKey = Option::get('ipol.dpd', 'KLIENT_KEY');
		$isTest = Option::get('ipol.dpd', 'IS_TEST');

		$uri = self::apiUrls['prod'];
		if ($isTest) {
			$uri = self::apiUrls['dev'];
		}

		$client = new SoapClient($uri."event-tracking?wsdl",array(
			'exceptions' => true,
		));

		$arData['auth'] = array(
		  'clientNumber' => $clientNumber,
		  'clientKey' => $clientKey
		);
		$arData['docId'] = $docId;
		$request['request'] =  $arData;
	  
	   try {
		  $response = $client->getEvents($request);
		  self::log("Документ " . $docId . " закрыт." , 'DPD_GET_STATUS.txt');
	   } catch(SoapFault $ex){
			var_dump($ex->faultcode, $ex->faultstring, $ex->faultactor, $ex->detail, $ex->_name, $ex->headerfault);
			self::log("Провал закрытия: ", 'DPD_GET_STATUS.txt');
			self::log([$ex->faultcode, $ex->faultstring, $ex->faultactor, $ex->detail, $ex->_name, $ex->headerfault], 'DPD_GET_STATUS.txt');
		}
	}
}