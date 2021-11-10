<?

// данный скрипт производит загрузку каталога товаров из
// базы данных сайта в Амо

// log
file_put_contents('uploadCatalog.log',  "\nStart upload catalog\t" . print_r(date('l jS \of F Y h:i:s A'), 1), FILE_APPEND);


require_once 'config.php';
require_once 'amowh.php';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


$amoApi = new \amowh\Amo(CLIENT_ID, CLIENT_SECRET, CLIENT_REDIRECT_URI, SUBDOMAIN);

// 1 - получаем список товаров из Битрикса

$res = CIBlockElement::GetList([],
[
    'ACTIVE' => 'Y',
    'IBLOCK_ID' => 26,
], 
false,
false 
// ['nTopCount' => 10]
);

while($ob = $res->GetNextElement())
{ 
    $arFields = $ob->GetFields();  
    $arProps = $ob->GetProperties();

    $item = array_merge($arFields, $arProps);

    // echo "<pre>" . print_r($item, 1) . "</pre>";

    $catEl = CCatalogProduct::GetByID($item["ID"]);
	// $catEl["QUANTITY"]; // это остаток по товару

	$elPrice = CPrice::GetBasePrice($item["ID"]);
	// $elPrice["PRICE"]; // это цена товара

    // теперь надо попытаться найти товар в амо по указанному артикулу
    $artNumber = $item['ARTNUMBER']['VALUE'];

    if ($elementId = $amoApi->findCatalogElementByArtnumberFast($artNumber))
    {
        // такой товар уже есть. пока ничего не делаем
        // echo "<pre> ELEMENT ID = " . print_r($elementId, 1) . "</pre>";
        

        // если такой товар уже есть, обновляем его остатки и цену
        $resUpd = $amoApi->updateCatalogElement([
            [
              'catalog_id' => CATALOG_ID ,
              'id' => $elementId,
              'name' => $item['NAME'],
              'custom_fields' => [
                    /* Цена */
                    [
                        'id' => '318469',
                        'values' => [ ['value' => (float)$elPrice["PRICE"] ] ],
                    ],
                    /* Остатки */
                    [
                        'id' => '350895',
                        'values' => [ ['value' => (int)$catEl["QUANTITY"] ] ],
                    ],
                ],
             ],
        ]);

        echo "<pre>" . print_r($resUpd, 1) . "</pre>";



    } else
    {

        // создаем новый товар
        $resAdd = $amoApi->addCatalogElement([
            [
                'catalog_id' => CATALOG_ID,
                'name' => $item['NAME'],
                'custom_fields' => [
                    /* артикул */
                    [
                        'id' => '318465',
                        'values' => [ ['value' => $artNumber] ],
                    ],
                    /* Регион */
                    [
                        'id' => '325781',
                        'values' => [ ['value' => $item['REGION']['VALUE']] ],
                    ],
                    /* Страна */
                    [
                        'id' => '325783',
                        'values' => [ ['value' => $item['COUNTRY']['VALUE']] ],
                    ],
                    /* Бренд */
                    [
                        'id' => '325785',
                        'values' => [ ['value' => $item['MANUFACTURER']['VALUE']] ],
                    ],
                    /* Описание */
                    [
                        'id' => '318467',
                        'values' => [ ['value' => $item['DETAIL_TEXT']] ],
                    ],
                    /* Цвет */
                    [
                        'id' => '325803',
                        'values' => [ ['value' => $item['COLOR']['VALUE']] ],
                    ],
                    /* Сахар */
                    [
                        'id' => '325789',
                        'values' => [ ['value' => $item['SUGAR']['VALUE']] ],
                    ],
                    /* Вкус */
                    [
                        'id' => '325805',
                        'values' => [ ['value' => $item['TASTE_TEXT']['VALUE']['TEXT']] ],
                    ],
                    /* Сорта винограда */
                    [
                        'id' => '325831',
                        'values' => [ ['value' => $item['SORT_TEXT']['VALUE']] ],
                    ],
                    /* Цена */
                    [
                        'id' => '318469',
                        'values' => [ ['value' => (float)$elPrice["PRICE"] ] ],
                    ],
                    /* Остатки */
                    [
                        'id' => '350895',
                        'values' => [ ['value' => (int)$catEl["QUANTITY"] ] ],
                    ],
                    
                    
                ],
            ]
        ]);

        echo "<pre>" . print_r($resAdd, 1) . "</pre>";
    }


}
