<?php
namespace Upside\SearchComponent;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Result;
use CBitrixComponent;
use Upside\Usearch;

class SearchComponent extends CBitrixComponent {
    
    public function __construct(CBitrixComponent $component = null) {
        parent::__construct($component);
		$this->getPopularPhrase();
    }


    public function getPopularPhrase($count = 10, $return_array = false) {
    	global $DB;
        $populars = array();
		$res = $DB->query('SELECT `PHRASE`, COUNT(*) as `count` FROM `b_search_phrase` GROUP BY `PHRASE` ORDER BY `count` DESC LIMIT '. + $count);

		while ($item = $res->Fetch()) {
			$populars[] = $item;
        }

        if ($return_array) {
            return $populars;
        } else {
            $this->arResult['POPULAR'] = $populars;
        }

    }

    public function executeComponent() {
        $this->includeComponentTemplate();
    }
}