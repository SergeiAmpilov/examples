<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

// getDiscountStatus.php
// получает статус скидки по переданному внешнему коду
// -1 = ошибка
// 0  = такой скидки нет
// 1  = такая скидка уже есть

// https://wineexpress.ru/wenewexchange/b24/getDiscountStatus.php?CODE=6630fbc8-c804-11ea-94b9-00155d014912

if (empty($_REQUEST["CODE"])) {
    echo -1;
    die();
}

// CCatalogDiscount


// CModule::IncludeModule("catalog");

$dbProductDiscounts = CCatalogDiscount::GetList(
    array("SORT" => "ASC"),
    ["COUPON" => ""]
);


echo "<pre>" . print_r($dbProductDiscounts, 1) . "</pre>";

while ( $arProductDiscounts = $dbProductDiscounts->Fetch() )
{
    echo "<pre>" . print_r($arProductDiscounts, 1) . "</pre>";
    
}




?>