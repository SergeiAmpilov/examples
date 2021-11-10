<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Loader;
use Bitrix\Main\Request;
use Upside\SearchComponent\SearchComponent;
use Upside\Usearch;

class SearchAjaxController  extends Controller {

    /**
     * @var Usearch
     */
    private $search;
    private $q;

    public function __construct(Request $request = null) {
        parent::__construct($request);

        $this->search  = new Usearch;
    }

    public function configureActions()
    {
        return [
            'search' => [
                'prefilters' => []
            ],
        ];
    }


    public function searchAction($q, $template) {

        $this->q = $q;
        $buffer = '';



        $result =  $this->search->byQuery($q);

        if(!is_array($result))
        {
            return ['error' => true, 'message' => $result];
        }



        switch ($template) {
            default:
                $buffer .= $this->defaultTemplate($result);
                break;
            case "page-search":
                $buffer .= $this->pageSearchTemplate($result);
                break;
        }

        if(empty($buffer)) {
            return ['error' => true, 'message' => 'empty buffer'];
        }

        return ['error' => false, 'content' => $buffer];
    }


    protected function defaultTemplate($result) {


        $buffer = '<ul class="done-item">';

        $buffer .= '<li class="done-item_title">Товары</li>';

        foreach($result as $cat)
        {
            if(!$cat['NAME'])
            {
                continue;
            }

            foreach($cat['SEARCH_RESULT'] as $element)
            {
                $buffer .= '<li class="done-item_result"><a href="' . $element['DETAIL_PAGE_URL']. '">' . mb_eregi_replace( $this->q, '<b>' . $this->q . '</b>', $element['NAME']) . '</a></li>';
            }
        }

        $buffer .= '<li class="done-item_title">Категории</li>';

        foreach($result as $cat)
        {

            if(!$cat['NAME'])
            {
                continue;
            }

            if( $cat['NAME'] == 'Без категории')
            {
                continue;
            }

            $buffer .= '<li class="done-item_result"><a href="' . $cat['SECTION_PAGE_URL']. '">' .
                mb_eregi_replace( $this->q, '<b>' . $this->q . '</b>', $cat['NAME']) . '(' . count($cat['SEARCH_RESULT']) . ')' . '</a></li>';

        }

        $buffer .= '</ul>';

        return $buffer;

    }



    protected function pageSearchTemplate($result) {

        $buffer = '<div class="select-item__title">Товары</div>';

        foreach($result as $cat)
        {
            if(!$cat['NAME'])
            {
                continue;
            }

            foreach($cat['SEARCH_RESULT'] as $element)
            {
                $buffer .= '<a class="select-item__content" href="' . $element['DETAIL_PAGE_URL']. '">' . mb_eregi_replace( $this->q, '<b>' . $this->q . '</b>', $element['NAME']) . '</a>';
            }
        }
        $buffer .= '</div>';


        $buffer  .= '<div class="form-field__select-item select-item">';

        $buffer .= '<div class="select-item__title">Категории</div>';

        foreach($result as $cat)
        {
            if(!$cat['NAME'])
            {
                continue;
            }

            if( $cat['NAME'] == 'Без категории')
            {
                continue;
            }

            $buffer .= '<a class="select-item__content" href="' . $cat['SECTION_PAGE_URL']. '">' .
                mb_eregi_replace( $this->q, '<b>' . $this->q . '</b>', $cat['NAME']) . '(' . count($cat['SEARCH_RESULT']) . ')' .
                '</a>';
        }

        $buffer.= '</div>';

        return $buffer;
    }


}