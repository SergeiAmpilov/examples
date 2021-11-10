<?php


namespace Upside;

use CIBlockElement;
use CIBlockSection;
use CModule;
use CSearch;
use CSearchStatistic;

class Usearch
{
    function __construct()
    {
        CModule::IncludeModule('search');
    }

    protected function getCatByID($id)
    {
        $res = CIBlockSection::GetByID($id);
        return $res->GetNext();
    }

    function byQuery($query)
    {
        $query = trim($query);

        $obSearch = new CSearch;

        $obSearch->Search(array(
            "QUERY" => $query,
            "SITE_ID" => LANG,
            "MODULE_ID" => "iblock",
            "PARAM2" => 2
        ));

        // если прямой поиск ничего не дал, то делаем такой же поиск но с отключенной морфологией
        if (!$obSearch->selectedRowsCount())
        {
            $obSearch->Search(array(
                "QUERY" => $query,
                "SITE_ID" => LANG,
                "MODULE_ID" => "iblock",
                "PARAM2" => 2
            ), [], ['STEMMING' => false]);
        }

        $result = [];
        if ($obSearch->errorno != 0) {
            return 'ErrorDB';
        } else {
            while ($arResult = $obSearch->GetNext()) {
                $result[] = $arResult;
            }
        }

        $statistic = new CSearchStatistic($query, "");
        $statistic->PhraseStat(count($result), 1);

        $arCategories = [];

        if (count($result)) {
            $maxCount = 30;
            foreach ($result as $item) {
                if(!$maxCount--) {
                    break;
                }
                $id = $item['ITEM_ID'];
                $element = CIBlockElement::GetByID($id)->GetNext();
                $cat_id = $element['IBLOCK_SECTION_ID'];
                $cat = $this->getCatByID($element['IBLOCK_SECTION_ID']);

                if(!$cat) {
                    $cat = [
                        'NAME' => 'Без категории',
                        'SECTION_PAGE_URL' => '/'
                    ];
                    $cat_id = 0;
                }

                if(!$arCategories[$cat_id]) {
                    $arCategories[$cat_id] = $cat;
                    $arCategories[$cat_id]['SEARCH_RESULT'] = [];
                }

                if(!$arCategories[$cat_id]['SEARCH_RESULT'][$id]) {
                    $arCategories[$cat_id]['SEARCH_RESULT'][$id] = $element;
                }
            }
        } else {
            return 'Empty result';
        }

        if(!count($arCategories)) {
            return false;
        }

        return $arCategories;
    }


    /* функция осуществляет поиск категорий товаров по переданному запросу */
    /* пока нигде не используется */
    public function byQueryCategories($query)
    {

        $query = trim($query);

        $obSearch = new CSearch;

        $obSearch->Search(array(
            "QUERY" => $query,
            "SITE_ID" => LANG,
            "MODULE_ID" => "iblock",
            "PARAM2" => 'category_2', /* признак того, что ищем по категориям инфоблока с ID = 2 */
        ));


        $result = [];
        if ($obSearch->errorno != 0)
        {
            return false;
        } else
        {
            while ($arResult = $obSearch->GetNext()) {
                $result[] = $arResult;
            }
        }

        return $result;
    }

}