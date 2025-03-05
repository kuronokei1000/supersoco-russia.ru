<?
namespace Aspro\Lite\Marketplace\Models\Ozon;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields,
    Bitrix\Main\ORM\Fields\Validators,
    Bitrix\Main\Web\Json,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class GoodsTable extends DataManager{
    public static function getTableName(){
        return 'b_'.(str_replace('.', '_', Solution::moduleID).'_ozon_goods');
    }

    public static function getCollectionClass()
    {
        return Goods::class;
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
                'STEP',
                array(
                    'required' => true,
                    'validation' => array(__CLASS__, 'validateNumber'),
                )
            ),

            // to remind
            new Fields\TextField(
                'VALUE',
                array(
                    'data_type' => 'longtext',
                    // 'serialized' => true
                    'save_data_modification' => array(__CLASS__, 'saveValues'),
                    'fetch_data_modification' => array(__CLASS__, 'fetchValues'),
                )
            )
        );
    }

    public static function validateNumber(){
        return array(
            new Validators\RangeValidator(0, null, true),
        );
    }

    public static function saveValues(){
        return array(
            function($value){
                if(
                    $value &&
                    is_array($value)
                ){
                    return serialize($value);
                }

                return '';
            }
        );
    }

    public static function fetchValues(){
        return array(
            function($value){
                // $result = Json::decode($value);
                // echo "<pre>";
                // print_r($value);
                // print_r($result);
                // echo "</pre>";
                // return $result;
                return trim($value);
            }
        );
    }
}
?>