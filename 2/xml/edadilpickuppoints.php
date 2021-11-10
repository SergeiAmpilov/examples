<?php

// данный скрипт выгружает актуальный список точек выдачи для системы Едадил
// https://wineexpress.ru/wenewexchange/xml/edadilpickuppoints.php


require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");


$IBLOCK_ID = 3; // Инфоблок с акциями

 
// echo "<pre>" . "https://" . print_r($_SERVER["SERVER_NAME"],1) . "</pre>";



$ar_catalogs = [];
$ar_offers = [];

$ar_items = [];

$res = CIBlockElement::GetList([], [
    "ACTIVE" => "Y",
    "IBLOCK_ID" => $IBLOCK_ID,
    ">DATE_ACTIVE_TO"=>ConvertTimeStamp(time(),"FULL")
]);

while($ob = $res->GetNextElement())
{ 
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();

    $ar_items = array_merge($ar_items, $arProps["TOV"]["VALUE"]);

    // echo "<pre>";
    // print_r($arFields["ID"]); echo "<br>";
    // print_r($arFields);
    // echo "</pre>";

    // echo "<pre>";
    // print_r($arProps);
    // echo "</pre>";

    $ar_catalogs[] = [
        "id" => $arFields["ID"],
        "conditions" => "Предложение действительно во всех винотеках Wine Express в Москве и Санкт-Петербурге при наличии товара в магазине. Данное предложение не суммируется со скидкой по карте лояльности Wine Express и другими промо-акциями. Цены указаны в рублях за единицу товара. Wine Express гарантирует подлинность продукции: все товары обладают необходимыми сертификатами и внесены в единую информационную систему. Внешний вид товаров может незначительно отличаться от представленных изображений. Подробную информацию о товаре спрашивайте у консультантов винотек или ищите на сайте wineexpress.ru. Количество товара ограничено. Информация, размещенная в каталоге Едадил, не является публичной офертой.",
        "date_start" => date(DATE_ATOM, strtotime($arFields["DATE_CREATE"])),
        "date_end" => date(DATE_ATOM, strtotime($arFields["DATE_ACTIVE_TO"])),
        "is_main" => true,
        "image" => "https://" . print_r($_SERVER["SERVER_NAME"],1) . CFile::GetPath($arProps["EDADIL_IMG"]["VALUE"]),
        "target_regions" => ["Россия, Москва", "Россия, Санкт-Петербург"],
        "offers" => $arProps["TOV"]["VALUE"],
    ];

    

}

// формируем массив с товарами
$ar_items = array_unique($ar_items);
// echo "<pre>" . print_r($ar_items, 1) . "</pre>";


foreach ($ar_items as $it)
{
    // echo "<pre>" . print_r($it, 1) . "</pre>";  
    
    
    /*
    if ($it == "46781" || $it == "45588" || $it == "45587")
    {
        continue ;
    }
    */
    

    $it_db = CIBlockElement::GetByID($it);

    if($it_data = $it_db->GetNext())
    {
        // echo "<pre>" . print_r($it_data, 1) . "</pre>";



        /* PROPERTIES */
        $db_props = CIBlockElement::GetProperty(26, $it);


        $acloType = "";
        $alcoBrand = "";
        while ($ar_props_el = $db_props->Fetch())
        {

            // echo "<pre>" . print_r($ar_props_el, 1) . "</pre>";

            if ($ar_props_el["CODE"] == "TYPE")
            {
                $acloType = $ar_props_el["VALUE_ENUM"];
            }


            if ($ar_props_el["CODE"] == "MANUFACTURER")
            {
                $alcoBrand = $ar_props_el["VALUE"];
            }

            
        }


        /* PRICES */
        $basePrice = CPrice::GetBasePrice($it)["PRICE"];
        $discountPrice = $basePrice;


        $dbPrice = CPrice::GetList(
            array("QUANTITY_FROM" => "ASC", "QUANTITY_TO" => "ASC", 
                  "SORT" => "ASC"),
            array("PRODUCT_ID" => $it, "CATALOG_GROUP_ID" => "1"),
            false,
            false,
            array("ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY", 
                  "QUANTITY_FROM", "QUANTITY_TO")
        );

        while ($arPrice = $dbPrice->Fetch())
        {
            $arDiscounts = CCatalogDiscount::GetDiscountByPrice(
                $arPrice["ID"],
                $USER->GetUserGroupArray(),
                "N",
                SITE_ID
            );

            $discountPrice = CCatalogProduct::CountPriceWithDiscount(
                    $arPrice["PRICE"],
                    $arPrice["CURRENCY"],
                    $arDiscounts
                );
            continue ;
        }
        
        // echo "<pre> prc " . print_r($basePrice, 1) . "</pre>";
        // echo "<pre> disc " . print_r($discountPrice, 1) . "</pre>";



        


        $cur_item_data = [
            "id" => $it_data["ID"],
            "description" => $acloType . " \ "  . $alcoBrand . " \ " . $it_data["NAME"],
            "image" => "https://" . print_r($_SERVER["SERVER_NAME"],1) . CFile::GetPath($it_data["DETAIL_PICTURE"]),
            "brand" => $alcoBrand,
            "price_old" => $basePrice,
            "price_new" => $discountPrice,
        ];

        // добавляем в результат
        $ar_offers[] = $cur_item_data;
    }
}

echo "<pre>" . print_r($ar_catalogs, 1) . "</pre>";
echo "<pre>" . print_r($ar_offers, 1) . "</pre>";


file_put_contents('edadilpickuppoints.json',  json_encode([
    "catalogs" => $ar_catalogs,
    "offers" => $ar_offers,
    "version" => 2
], JSON_UNESCAPED_UNICODE ));



function getPropsValueByCode($code, $ar_data)
{

    $res_filt = array_filter($ar_data, function($item) use ($code) {
        return $item["CODE"] == $code;
    });

    return current($res_filt)["VALUE_ENUM"];

}

die(); // ======================== //

// ID инфоблока
$IBLOCK_ID = 32;

$resTotal = [];


foreach ($id_list as $k => $v)
{
    // echo "Hello Edadil<br>";

    // CIBlockElement::GetPropertyValues

    $res = CIBlockElement::GetByID($v);

    if ($ar_res = $res->GetNext())
    {

        // значения свойств




        // echo "<pre>" . print_r($ar_res, 1) . "</pre>";

        
    }
        

    



}

$iterator = CIBlockElement::GetPropertyValues($IBLOCK_ID, [
    'ACTIVE' => 'Y',
    [
        "LOGIC" => "OR",
            ["ID" => 44498],
            ["ID" => 13955],
            ["ID" => 44345],
            ["ID" => 47176],
    ]
], false);

while ($row = $iterator->Fetch())
{
    // echo "<pre>"; print_r($row); echo "</pre>";

    $res = CIBlockElement::GetByID($row["IBLOCK_ELEMENT_ID"]);

    if ($ar_res = $res->GetNext())
    {

        $resTotal[] = [
            "id" => $row["IBLOCK_ELEMENT_ID"],
            "store_cluster" => $ar_res["NAME"],
            "brand" => "Wineexpress",
            "phones" => [ $row["254"] ],
            "address" => $row[251],
        ];

    }
  
}

file_put_contents('edadilpickuppoints.json',  json_encode($resTotal, JSON_UNESCAPED_UNICODE ));

// echo "<pre>"; print_r(json_encode($resTotal, JSON_UNESCAPED_UNICODE )); echo "</pre>";
