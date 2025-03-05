<?
include_once('const.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
global $USER;

$bSuccessConfigSave = false;

if($USER->IsAdmin() && (isset($_POST['SAVE_OPTIONS']) && $_POST['SAVE_OPTIONS'] == 'Y'))
{
	if(isset($_SESSION['THEME']) && $_SESSION['THEME'])
	{
		if($_SESSION['THEME'][SITE_ID])
		{
			\Bitrix\Main\Loader::includeModule('aspro.lite');

			// get options
			foreach(CLite::$arParametrsList as $blockCode => $arBlock)
			{
				if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS']))
				{
					foreach($arBlock['OPTIONS'] as $optionCode => $arOption)
					{
						if($arOption['TYPE'] !== 'note' && $arOption['TYPE'] !== 'includefile' && $arOption['TYPE'] !== 'file')
							$arBackParametrs[$optionCode] = $arOption;
					}
				}
			}
			$bSuccessConfigSave = true;
			$arDependentParams = array();
			foreach($_SESSION['THEME'][SITE_ID] as $optionCode => $optionValue)
			{
				if($arBackParametrs[$optionCode]) //save exists option
				{
					\Bitrix\Main\Config\Option::set('aspro.lite', $optionCode, $optionValue, SITE_ID);
				}
				else //get dependent option
				{
					if(strpos($optionCode, 'index') !== false)
					{
						$arTmpOption = explode('_', $optionCode, 2);
						$index_code = reset($arTmpOption);
						$index_subvalue = end($arTmpOption);

						$arDependentParams[$index_code][$index_subvalue] = $optionValue;
					}
					else
						$arDependentParams[$optionCode] = $optionValue;
				}
			}
			if($arDependentParams) // save dependent options
			{
				foreach($arBackParametrs as $optionCode => $arOption)
				{
					$bAdditionalOptions = isset($arOption['ADDITIONAL_OPTIONS']) && $arOption['ADDITIONAL_OPTIONS'];
					$bToggleOptions = isset($arOption['TOGGLE_OPTIONS']) && $arOption['TOGGLE_OPTIONS'];

					if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS'])
					{
						foreach($arOption['DEPENDENT_PARAMS'] as $dependOptionCode => $arValue)
						{
							if($arDependentParams[$dependOptionCode])
							{
								if($arValue['LIST'][$arDependentParams[$dependOptionCode]])
									\Bitrix\Main\Config\Option::set('aspro.lite', $dependOptionCode, $arDependentParams[$dependOptionCode], SITE_ID);
							}
						}
					}
					elseif(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS'])
					{
						$arOptionKeys = array_keys($arDependentParams);
						$arCustomOptions = ['SORT', 'TOP', 'BOTTOM', 'DELIMITER', 'FON', 'SHOW', 'TITLE', 'TABS'];

						foreach ($arDependentParams as $key => $arSubParams) {
							if ($arOption['LIST'][$key] && $arOption['SUB_PARAMS'][$key]) {
								$arTemplateIndex = array();
								foreach ($arSubParams as $key2 => $value) {
									if (strpos($key2, 'TEMPLATE') !== false) {
										$arTemplateIndex[$key2] = $value;
										unset($arSubParams[$key2]);
									}
								}
								if ($arSubParams) {
									$arNestedParams = CLite::unserialize(\Bitrix\Main\Config\Option::get('aspro.lite', "NESTED_OPTIONS_".$optionCode."_".$key, serialize([]), SITE_ID));
									
									\Bitrix\Main\Config\Option::set('aspro.lite', "NESTED_OPTIONS_".$optionCode."_".$key, serialize(array_merge($arNestedParams,$arSubParams)), SITE_ID);
								}

								//save teplate index
								if ($arTemplateIndex) {
									foreach ($arTemplateIndex as $key2 => $value) {
										\Bitrix\Main\Config\Option::set('aspro.lite', $key."_".$key2, $value, SITE_ID);
									}
								}
								
								//sort order prop for main page
								$param = 'SORT_ORDER_'.$optionCode.'_'.$key;
								$optionValue = \Bitrix\Main\Config\Option::get('aspro.lite', $param, '', SITE_ID);
								$sessionValue = $_SESSION['THEME'][SITE_ID][$param];

								\Bitrix\Main\Config\Option::set('aspro.lite', $param, $sessionValue ? $sessionValue : $optionValue, SITE_ID);
							} elseif (in_array($key, $arCustomOptions)) {
								foreach ($arSubParams as $key2 => $value) {
									$param = $key.'_'.$key2;

									\Bitrix\Main\Config\Option::set('aspro.lite', $param, $_SESSION['THEME'][SITE_ID][$param], SITE_ID);
								}
							}
						}
					}
					elseif($bAdditionalOptions || $bToggleOptions) // additional & toogle options
					{
						foreach($arOption['LIST'] as $key => $arOptionsList)
						{
							if($arOptionsList['ADDITIONAL_OPTIONS'])
							{
								foreach($arOptionsList['ADDITIONAL_OPTIONS'] as $key2 => $arListOption2)
								{
									if($arDependentParams[$key2."_".$key]){
										\Bitrix\Main\Config\Option::set('aspro.lite', $key2."_".$key, $arDependentParams[$key2."_".$key], SITE_ID);
									}
								}
							}

							if($arOptionsList['TOGGLE_OPTIONS'])
							{
								foreach($arOptionsList['TOGGLE_OPTIONS']['OPTIONS'] as $key2 => $arListOption2)
								{
									if($arDependentParams[$key2."_".$key]){
										\Bitrix\Main\Config\Option::set('aspro.lite', $key2."_".$key, $arDependentParams[$key2."_".$key], SITE_ID);
									}

									if($arListOption2['ADDITIONAL_OPTIONS'])
									{
										foreach($arListOption2['ADDITIONAL_OPTIONS'] as $key3 => $arListOption3)
										{
											if($arDependentParams[$key3."_".$key]){
												\Bitrix\Main\Config\Option::set('aspro.lite', $key3."_".$key, $arDependentParams[$key3."_".$key], SITE_ID);
											}
										}
									}
								}
							}
						}
					}
				}
			}
			unset($_SESSION['THEME'][SITE_ID]);
		}
	}
}

if($bSuccessConfigSave)
	$addResult = array('STATUS' => 'OK', 'MESSAGE' => 'CONFIG_SAVE_SUCCESS');
else
	$addResult = array('STATUS' => 'ERROR', 'MESSAGE' => 'CONFIG_SAVE_FAIL');

echo json_encode($addResult);
die();
?>