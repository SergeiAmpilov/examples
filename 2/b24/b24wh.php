<?php

namespace b24;

class webHook{
	private $bitrix_domain;
	private $bitrixKey;
	private $bitrixid;

	private function postCurl($url, $postData, $header = false){
		$ch = curl_init();
     	curl_setopt($ch, CURLOPT_URL, $url);
     	if ($header == true){
     		curl_setopt($ch, CURLOPT_HEADER, TRUE);
     	}
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_POST, true);
    	curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7");
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
//		curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/cookie.log");
//		curl_setopt($ch, CURLOPT_COOKIEFILE,  __DIR__ . "/cookie.log");
    	$res = curl_exec($ch);
    	curl_close($ch);
    	return $res;
	}

	private function getCurl($url, $header = false){//print_r($url); die();
		$ch = curl_init();
     	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     	if ($header == true){
     		curl_setopt($ch, CURLOPT_HEADER, TRUE);
     	}
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//		curl_setopt($ch, CURLOPT_COOKIEJAR,  __DIR__ . "/cookie.log");
//		curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/cookie.log");
		$res = curl_exec($ch);
    	curl_close($ch);
    	return $res;
	}
	
	function __construct($bitrix_domain, $bitrixKey, $bitrixid = 1){
		$this->bitrix_domain = $bitrix_domain;
		$this->bitrixKey = $bitrixKey;
		$this->bitrixid = $bitrixid;
	}
	
	function add($name, $fields){
        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/crm." . $name . ".add/?" .  http_build_query($fields));
        $jsonanswer = json_decode($result);
        return $jsonanswer;
	}

    function Get($name, $id, $section = 'crm'){
        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $section . "." . $name . ".get/?id=" . $id);
        $jsonanswer = json_decode($result);
        return $jsonanswer;
    }

	function fields($name, $section = 'crm'){
        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $section . "." . $name . ".fields/");
        $jsonanswer = json_decode($result);
        return $jsonanswer;
	}

	function GetList($bitrixName, $params = array(), $start = 0, $section = 'crm') {
   		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $section . "." . $bitrixName . ".list/?start=$start" . "&" . http_build_query($params));
//   		$result = $this->postCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/crm.".$bitrixName.".list", http_build_query($params));
      	$jsonanswer = json_decode($result);
       	return $jsonanswer;
	}
	
	function setgoods($id, $params = array()) {
   		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/crm.deal.productrows.set/?id=$id" . "&" . http_build_query($params));
      	$jsonanswer = json_decode($result);
       	return $jsonanswer;
	}

	function lineStart($params = array()) {
   		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/voximplant.callback.start/?" . http_build_query($params));
      	$jsonanswer = json_decode($result);
       	return $jsonanswer;
	}
	
	function getById($name, $id, $section = 'crm'){
   		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $section . "." . $name . ".get/?id=" . $id);
      	$jsonanswer = json_decode($result);
       	return $jsonanswer;
	}

    function lineGet(){
        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/voximplant.line.get");
//        print_r($result);
        $jsonanswer = json_decode($result);
        return $jsonanswer;
    }
	
	function getTask($name, $id){
   		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $name . "/?id=" . $id);
   		$jsonanswer = json_decode($result);
       	return $jsonanswer;
	}

	function getOrderFields() {

        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . "sale" . "." . "order" . ".getFields/");
   		$jsonanswer = json_decode($result);
       	return $jsonanswer;


	}

    function sendNot($structid, $t){
	    $to = [2428, 42, 2592, 60, 26];
	    $to = [2572];
	    if($t == 1)
	        $text = 'Новое сообщение от клиента.#BR#https://standart001.bitrix24.ru/crm/contact/details/'.$structid.'/';
	    else if($t == 2)
            $text = 'Новое сообщение от клиента.#BR#https://standart001.bitrix24.ru/crm/lead/details/'.$structid.'/';

	    foreach ($to as $id) {
            $arr = [
                'to' => $id,
                'message' => $text,
                'type' => 'SYSTEM'
            ];
            $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/im.notify/?" . http_build_query($arr));
        }
        //$jsonanswer = json_decode($result);
        return "OK";
    }
	
	function updateFields($name, $id, $fields = array(), $section = 'crm'){
        $result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/" . $section . "." . $name . ".update/?id=" . $id . "&" .  http_build_query($fields));
        $jsonanswer = json_decode($result);
        return $jsonanswer;
	}

	// ampse 2020

	// sale - интернет магазин
	function saleAdd($name, $fields) {
		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/sale." . $name . ".add/?" .  http_build_query($fields));
        $jsonanswer = json_decode($result);
        return $jsonanswer;

	}

	function salePropertyesModify($fields) {
		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/sale." . "propertyvalue" . ".modify/?" . http_build_query($fields));
		$jsonanswer = json_decode($result);
        return $jsonanswer;
	}

	function addBasketItem($fields) {

		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/sale." . "basketitem" . ".add/?" . http_build_query($fields));
		$jsonanswer = json_decode($result);
		return $jsonanswer;
		
	}

	function getUserById($fields) {
		$result = $this->getCurl("https://" . $this->bitrix_domain . "/rest/" . $this->bitrixid . "/" . $this->bitrixKey . "/user." . "get" . ".json/?" . http_build_query($fields));
		$jsonanswer = json_decode($result);
		return $jsonanswer;
	}

	// functions

	// возвращает false - если контакт не найден и
	// возвращает ид контакта, если он уже есть
	function findContactByPhone($phoneFormated) {

		$listRes = $this->GetList('contact', [
			"order" => ["DATE_CREATE" => "ASC"],
			"filter" => ["PHONE" =>  $phoneFormated ],
			"select" => ["ID", "NAME", "SECOND_NAME", "PHONE", "EMAIL", "ASSIGNED_BY_ID"]
			
		]);

		if ($listRes->total === 0) {
			return false;
		} else {
			return current($listRes->result);
		}
	}

	// возвращает false - если контакт не удалось создать
	// возвращает ИД контакта, если всё получилось
	function createNewContact($name, $phone, $email, $sourceId = "", $assignetId="",
		$utm_source = "", $utm_medium = "", $utm_campaign = "", $utm_content = "") {
		
		$resClientAdd = $this->add('contact', ["fields" => [    
			"NAME" => $name,
			"PHONE" => [[ "VALUE" => $phone, "VALUE_TYPE" => "WORK" ]],
			"EMAIL" => [[ "VALUE" => $email, "VALUE_TYPE" => "WORK" ]],
			"SOURCE_ID" => $sourceId,
			"UTM_SOURCE" => $utm_source,
			"UTM_MEDIUM" => $utm_medium,
			"UTM_CAMPAIGN" => $utm_campaign,
			"UTM_CONTENT" => $utm_content,
			"ASSIGNED_BY_ID" => $assignetId,
		]]);

		if ( !empty($resClientAdd->result) ) {
			return $resClientAdd->result;			
		} else {
			return false;			
		}
	}

	// возвращает false - если ничего не найдено,
	// в противном случае возвращает массив с основными параметрами
	function getProductIdByArti($article) {

		$r1 = $this->GetList('product', [
			"filter" => [
				"CATALOG_ID" => "26",
				"PROPERTY_153" => $article,
			],
			"select" => [ "ID", "NAME", "PRICE" ],
		]);

		return $r1->total 
				? (array) current($r1->result)
				: false ;
	}

	// функция определяет цену ЧКК для товара по переданному ИД.
	// если получить цену не удалось, то возвращает false,
	// в противном случае возвращает значение цены
	function getPCCPrice($productId) {

		$r = $this->GetList('price', [ "filter" => [
			"productId" => $productId,
			"catalogGroupId" => 4, /* тип цены = ЧКК */
		]], 0, 'catalog');

		if ($r->total) {
			return (float) current($r->result->prices)->price;
		} else {
			return false;
		}		
	}

	// функция устанавливает ответственного за заказ
	function setResponsiveToOrder($orderId, $customerId) {

		$resUpd = $this->updateFields('order', $orderId, [
			"id" => $orderId,
			"fields" => [
				"responsibleId" => $customerId
			],
		], 'sale');

		return $resUpd;

	}

	function setResponsiveToCustomer($customerId, $respId) {

		$resUpd = $this->updateFields('contact', $customerId, [
			"id" => $customerId,
			"fields" => [
				"ASSIGNED_BY_ID" => $respId,
			],
		], 'crm');

		return $resUpd;

	}
}