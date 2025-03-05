<?
namespace Aspro\Lite\Marketplace\Models\Ozon;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields,
    Bitrix\Main\ORM\Fields\Validators,
    Bitrix\Main\Web\Json,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class SectionsTable extends DataManager{
    public static function getTableName(){
        return 'b_'.(str_replace('.', '_', Solution::moduleID).'_ozon_sections');
    }

    public static function getCollectionClass()
    {
        return Sections::class;
    }

    public static function getMap(){
        return array(
            new Fields\IntegerField(
                'ID',
                array(
                    'primary' => true,
                    'autocomplete' => true
                )
            ),

            new Fields\IntegerField(
                'CLIENT_ID',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateNumber'),
                )
            ),

            new Fields\IntegerField(
                'OZON_ID',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateNumber'),
                )
            ),

            new Fields\IntegerField(
                'PARENT_ID',
                /* array(
                    'validation' => array(__CLASS__, 'validateNumber'),
                ) */
            ),

            new Fields\TextField(
                'TITLE',
                array(
                    'required' => true,
                )
            )
        );
    }

    public static function validateNumber(){
        return array(
            new Validators\RangeValidator(0, null, true),
        );
    }
}
?>