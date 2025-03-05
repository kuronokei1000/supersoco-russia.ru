<?
use Bitrix\Main\UserPhoneAuthTable;

include_once('const.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
global $DB;

$phoneNumber = UserPhoneAuthTable::normalizePhoneNumber($_POST['USER_PHONE_NUMBER'] ?? '');
$smsCode = $_POST['SMS_CODE'] ?? '';
$userId = trim($_POST['USER_ID'] ?? false);
$userLogin = trim($_POST['USER_LOGIN'] ?? '');
$bAuth = isset($_POST['AUTH']) && $_POST['AUTH'] === 'Y';

if (
    strlen($phoneNumber) &&
    strlen($smsCode) &&
    (
        $bAuth ||
        $userId > 0 ||
        strlen($userLogin)
    )
) {
    $row = UserPhoneAuthTable::getList(['filter' => ['=PHONE_NUMBER' => $phoneNumber]])->fetch();
    if (
        $row &&
        $row['OTP_SECRET'] <> ''
    ) {
        $attempts = $row['ATTEMPTS'];

        if ($attempts < 3) {
            if ($userIdVerified = \CUser::VerifyPhoneCode($phoneNumber, $smsCode)) {
                if ($bAuth) {
                    if ($row['CONFIRMED'] === 'Y') {
                        echo 'true';
                        exit;
                    }
                } elseif ($userId > 0) {
                    if ($userId == $userIdVerified) {
                        echo 'true';
                        exit;
                    }
                } elseif (strlen($userLogin)) {
                    if ($arUser = \CUser::getById($userIdVerified)->Fetch()) {
                        if ($arUser['LOGIN'] == $userLogin) {
                            echo 'true';
                            exit;
                        }
                    }
                }
            }

            UserPhoneAuthTable::update($row['USER_ID'], [
                'ATTEMPTS' => $attempts,
            ]);
        }
    }
}

echo 'false';
