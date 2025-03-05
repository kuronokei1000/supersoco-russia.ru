<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
global $DB;

$captcha_word = strtoupper($_REQUEST['captcha_word'] ?? '');
$captcha_sid = $_REQUEST['captcha_sid'] ?? '';

if(
    strlen($captcha_word) &&
    strlen($captcha_sid) 
) {
    $res = $DB->Query("SELECT CODE FROM b_captcha WHERE ID = '".$DB->ForSQL($captcha_sid, 32 )."' ");

    if (
        ($ar = $res->Fetch()) &&
        $ar["CODE"] == $captcha_word
    ) {
        echo 'true';
        exit; 
    }
}

echo 'false';
