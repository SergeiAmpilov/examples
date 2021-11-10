<?php

/*AddEventHandler("search", "BeforeIndex", array("SearchIndexing", "rules"));

class SearchIndexing {
	**
	 * Параметры индексирования
	 *
	 * @param $arFields
	 * @return mixed
	 *
	function rules($arFields) {

		CModule::IncludeModule("iblock");

		if($arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 2) { // ID инфоблока

			$arFields["BODY"] = "";
			foreach(['BARCODE'] as $CODE) {
				$db_props = CIBlockElement::GetProperty(                // Запросим свойства индексируемого элемента
					$arFields["PARAM2"],                                // BLOCK_ID индексируемого свойства
					$arFields["ITEM_ID"],                                // ID индексируемого свойства
					["sort" => "asc"],                                // Сортировка (можно упустить)
					["CODE" => $CODE]                            // CODE свойства (в данном случае артикул)
				);

				if ($ar_props = $db_props->Fetch()) {
					$arFields["TITLE"] .= " " . $ar_props["VALUE"];    // Добавим свойство в конец заголовка индексируемого элемента
					$arFields["BODY"] .=  " " . $ar_props["VALUE"];
				}
			}

			file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/searchindex.txt", $arFields["TITLE"] . "\r\n", FILE_APPEND);
		}

		//file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/searchindex_test.txt", print_r($arFields, 1));

		return $arFields;
	}
}*/



$eventManager = \Bitrix\Main\EventManager::getInstance();

// $eventManager->addEventHandlerCompatible('search', 'BeforeIndex',    ['\\CatalogProductIndexer','handleBeforeIndex']);
$eventManager->addEventHandlerCompatible('search', 'BeforeIndex',    ['CatalogProductIndexer','handleBeforeIndex']);


class CatalogProductIndexer
{
    /**
     * @var int Идентификатор инфоблока каталога
     */
    const IBLOCK_ID = '2';

    /**
     * Дополняет индексируемый массив нужными значениями
     * подписан на событие BeforeIndex модуля search
     * @param array $arFields
     * @return array
     */
    public static function handleBeforeIndex( $arFields = [] )
    {
        if ( !static::isInetesting( $arFields ) )
        {
            return $arFields;
        }

        /**
         * @var array Массив полей элемента, которые нас интересуют
         */
        $arSelect = [
            'ID',
            'IBLOCK_ID',
            'PROPERTY_BARCODE',
            'PROPERTY_ARTICLE',
            'PROPERTY_TRADE_MARK',
            'IBLOCK_SECTION_ID',
        ];

        /**
         * @var CIblockResult Массив описывающий индексируемый элемент
         */
        $resElements = \CIBlockElement::getList(
            [],
            [
                'IBLOCK_ID' => $arFields['PARAM2'],
                'ID'        => $arFields['ITEM_ID']
            ],
            false,
            [
                'nTopCount'=>1
            ],
            $arSelect
        );

        /**
         * В случае, если элемент найден мы добавляем нужные поля
         * в соответсвующие столбцы поиска
         */
        if ( $arElement = $resElements->fetch() )
        {
            $arFields['TITLE'] .= ' '.$arElement['PROPERTY_BARCODE_VALUE'];
            $arFields['BODY'] .= ' '.$arElement['PROPERTY_ARTICLE_VALUE'] . ' '.$arElement['PROPERTY_TRADE_MARK_VALUE'];


            /*
            * индексируем категорию
            * - пока отключено


            $resSection = \CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);

            if($rSection = $resSection->GetNext())
            {

                $date = new \Bitrix\Main\Type\DateTime;

                $resCategotyIndex = \CSearch::Index(
                    'iblock',
                    $arElement['IBLOCK_SECTION_ID'],
                    [
                        "DATE_CHANGE" => $date,
                        "TITLE" => $rSection['NAME'],
                        "SITE_ID" => $arFields['SITE_ID'],
                        "PARAM1" => $arFields['PARAM1'],
                        "PARAM2" => "category_" . $arFields['PARAM2'],
                        "URL" => $rSection["SECTION_PAGE_URL"]
                    ],
                    true
                );

                // file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/searchindex.txt", print_r($resCategotyIndex, 1) . "\r\n", FILE_APPEND);
            }
            */

            //file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/searchindex.txt", $arFields["TITLE"] . "\r\n", FILE_APPEND);
        } else {
            //file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/searchindex_bad.txt", $arFields["TITLE"] . "\r\n", FILE_APPEND);
        }


        return $arFields;
    }

    /**
     * Возвращает true, если это интересующий нас элемент
     * @param array $fields
     * @return boolean
     */
    public static function isInetesting( $fields = [] )
    {
        return ( $fields["MODULE_ID"] == "iblock" && $fields['PARAM2'] == static::IBLOCK_ID );
    }

}