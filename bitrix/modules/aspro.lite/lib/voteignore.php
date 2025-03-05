<?
namespace Aspro\Lite;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields,
    Bitrix\Main\ORM\Fields\Validators,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class VoteIgnoreTable extends DataManager {
    const CODE_LENGTH = 10;

    public static function getTableName() {
        return 'b_'.(str_replace('.', '_', Solution::moduleID).'_voteignore');
    }

    public static function getMap() {
        return array(
            new Fields\IntegerField(
                'ID',
                array(
                    'primary' => true,
                    'autocomplete' => true
                )
            ),

            new Fields\IntegerField(
                'USER_ID',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateUserId'),
                )
            ),

            new Fields\StringField(
                'SITE_ID',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateSiteId'),
                )
            ),

            new Fields\IntegerField(
                'PRODUCT_ID',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateProductId'),
                )
            ),

            new Fields\DatetimeField(
                'DATE_CREATE',
                array(
                    'default_value' => new \Bitrix\Main\Type\DateTime,
                )
            ),
        );
    }

    public static function validateUserId() {
        return array(
            new Validators\RangeValidator(0, null, true), // value > 0
        );
    }

    public static function validateProductId() {
        return array(
            new Validators\RangeValidator(0, null, true), // value > 0
        );
    }

    public static function validateSiteId() {
        return array(
            new Validators\LengthValidator(2, 2), // value length == 2
        );
    }
}
