<?

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ($_REQUEST["ORDER_ID"] && $_REQUEST["COUPONVAL"]) {
    $GLOBALS["USER_FIELD_MANAGER"]->Update("ORDER", $_REQUEST["ORDER_ID"], Array("UF_CRM_1593181161"=>$_REQUEST["COUPONVAL"])); 
}

?>
