<?php
/*
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/. ./..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('BX_NO_ACCELERATOR_RESET', true); 

// здесь никакой агент не выполнится. Потому что мы сделали COption::SetOptionString("main", "check_agents", "N");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

CAgent::CheckAgents(); // а вот тут все агенты выполнятся. И периодические, и непериодические
CEvent::CheckEvents(); // почтовые события мы оставили выполняться на хитах. Но и тут они могут выполняться. Почему нет?

define("BX_CRONTAB", true);

// модуль email-маркетинг
if (CModule::IncludeModule('sender')) {
    \Bitrix\Sender\MailingManager::checkPeriod(false);
    \Bitrix\Sender\MailingManager::checkSend();
}
*/
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('BX_NO_ACCELERATOR_RESET', true);
define('CHK_EVENT', true);
define('BX_WITH_ON_AFTER_EPILOG', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

CAgent::CheckAgents();
define("BX_CRONTAB_SUPPORT", true);
define("BX_CRONTAB", true);

if(CModule::IncludeModule('sender'))
{
    \Bitrix\Sender\MailingManager::checkPeriod(false);
    \Bitrix\Sender\MailingManager::checkSend();
}

require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/tools/backup.php");
CMain::FinalActions();
?>