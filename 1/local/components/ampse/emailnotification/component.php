<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


$arResult['DELIVERY_ADRESS'] = '';
$arResult['PAY_METHOD'] = '';
$arResult['DELIVERY_COST'] = '';
$arResult['BASKET_ITEMS'] = [];





$order = \Bitrix\Sale\Order::load($arParams["ORDER_REAL_ID"]);
$paymentCollection = $order->getPaymentCollection();

foreach ($paymentCollection as $payment)
{
    
    if ($pSysName = $payment->getPaymentSystemName())
    {
        $arResult['PAY_METHOD'] = $pSysName;
        break ;
        
    }
}

$arResult['DELIVERY_COST'] = $order->getDeliveryPrice();



$db_props = CSaleOrderPropsValue::GetOrderProps($arParams["ORDER_REAL_ID"]);

while ($arProps = $db_props->Fetch())
{


    /* адрес доставки */
    if ($arProps["CODE"] == "ADDRESS")
    {
        $arResult['DELIVERY_ADRESS'] = $arProps["VALUE"];
        
    }
}

if (!$arResult['DELIVERY_COST'])
{
    $arResult['DELIVERY_COST'] = 'Бесплатно';
}


/* список товаров */
$basket = $order->getBasket();

// echo "<pre>" . print_r($basket, 1) . "</pre>";

foreach ($basket->getBasketItems() as $bItem)
{

    $newItemRes = [];      

    /* данные о товаре по ИД торгового предложения */
    $catalog_item_id = CCatalogSku::GetProductInfo($bItem->getField('PRODUCT_ID'));

        
    if ($prodId = $catalog_item_id["ID"])
    {
        $res = CIBlockElement::GetByID($prodId);

        if($ar_res = $res->GetNext())
        {
            $newItemRes["NAME"] = $ar_res["NAME"];

        }

        /* свойства товара цвет */
        $item_db_props_color = CIBlockElement::GetProperty(
            $catalog_item_id["IBLOCK_ID"],
            $catalog_item_id["ID"],
            ["sort" => "asc"],
            [ "CODE"=>"COLOR" ]
        );
        if ($item_ar_props_color = $item_db_props_color->Fetch())
        {
            $newItemRes["COLOR"] = $item_ar_props_color["VALUE"];
        }

        /* свойства товара артикул */
        $item_db_props_artumber = CIBlockElement::GetProperty(
            $catalog_item_id["IBLOCK_ID"],
            $catalog_item_id["ID"],
            ["sort" => "asc"],
            [ "CODE" => "ARTICLE" ]
        );
        if ($item_ar_props_artnumber = $item_db_props_artumber->Fetch())
        {
            $newItemRes["ARTICLE"] = $item_ar_props_artnumber["VALUE"];
        }
    }
    
    if ($item_offer_name = $bItem->getField('NAME'))
    {
        $newItemRes["NAME"] .= " ( " . $item_offer_name . " )";

    }
    

    $newItemRes["QUANTITY"] = $bItem->getQuantity();
    $newItemRes["FINAL_PRICE"]= $bItem->getFinalPrice() . " руб.";

    $newItemRes["SIZE"] = $bItem->getField('NAME');
 
    
    $arResult['BASKET_ITEMS'][] = $newItemRes;
}

$this->IncludeComponentTemplate();
?>