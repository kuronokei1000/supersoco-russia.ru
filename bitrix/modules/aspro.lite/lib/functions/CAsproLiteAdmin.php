<?
namespace Aspro\Functions;

use Bitrix\Main\Application,
	Bitrix\Main\Web\DOM\Document,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Web\DOM\CssParser,
	Bitrix\Main\Text\HtmlFilter,
	Bitrix\Main\IO\File,
	Bitrix\Main\IO\Directory,
	Bitrix\Main\Config\Option,
	CLite as Solution;
	
Loc::loadMessages(__FILE__);

if(!class_exists("CAsproLiteAdmin"))
{
	class CAsproLiteAdmin{
		const MODULE_ID = \CLite::moduleID;

		private static $moduleClass = 'CLite';

		public $request;
		public $siteID;
		public $tabOptions;

		function __construct($request, $optionsSiteID, $tabOptions) {
			$this->request = $request;
			$this->siteID = $optionsSiteID;
			$this->tabOptions = $tabOptions;
		}

		public function checkList($arOption) {
			if($arOption['TYPE'] == 'selectbox' && $arOption['LIST']){
				foreach($arOption['LIST'] as $listItemKey => $listItem){
					if(is_array($listItem)){
						$this->checkAll($listItem, $listItemKey);
					}
				}
			}
		}

		public function checkAll($arOption, $optionListKey = '') {	
			foreach(
				array(
					'DEPENDENT_PARAMS',
					'ADDITIONAL_OPTIONS',
					'TOGGLE_OPTIONS:OPTIONS',
				) as $param
			){
				$this->checkParams($arOption, $param, $optionListKey);
			}
		}

		public function checkParams($arOption, $needKey, $optionListKey){
			$arCheckedParams = $this->getCurrentKey($arOption, $needKey);
			if($arCheckedParams){
				foreach($arCheckedParams as $paramCode => $arParam){
					$paramCode = $paramCode.($optionListKey ? '_'.$optionListKey : '');
					$newVal = $this->request[$paramCode.'_'.$this->siteID];
					$this->setOption($paramCode, $arParam, $optionListKey, $newVal);
				}
			}
		}

		public function setOption($optionCode, $arOption, $optionListKey, &$newVal){
			$this->setThemeColorsOptions($optionCode);

			if($optionCode == 'CUSTOM_FONT') {
				$newVal = $this->getCustomFontVal($optionCode, $newVal);
			}
		
			if($arOption["TYPE"] == "checkbox"){
				$newVal = $this->getCheckBoxVal($newVal);
			}
			elseif($arOption["TYPE"] == "file"){
				$arValueDefault = serialize(array());
				$newVal = Solution::unserialize(Option::get(self::MODULE_ID, $optionCode, $arValueDefault, $this->siteID), ['allowed_classes' => false]);
				if(
					isset($_REQUEST[$optionCode."_".$this->siteID.'_del']) ||
					(
						isset($_FILES[$optionCode."_".$this->siteID]) && 
						strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['0'])
					)
				){
					$arValues = $newVal;
					$arValues = (array)$arValues;
					foreach($arValues as $fileID){
						\CFile::Delete($fileID);
					}
		
					$newVal = serialize(array());
				}
		
				if(
					isset($_FILES[$optionCode."_".$this->siteID]) &&
					(
						strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['n0']) || 
						strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['0'])
					)
				){
					$arValues = array();
					$absFilePath = (strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['n0']) ? $_FILES[$optionCode."_".$this->siteID]['tmp_name']['n0'] : $_FILES[$optionCode."_".$this->siteID]['tmp_name']['0']);
					$arOriginalName = (strlen($_FILES[$optionCode."_".$this->siteID]['name']['n0']) ? $_FILES[$optionCode."_".$this->siteID]['name']['n0'] : $_FILES[$optionCode."_".$this->siteID]['name']['0']);
					if(file_exists($absFilePath)){
						$arFile = \CFile::MakeFileArray($absFilePath);
						$arFile['name'] = $arOriginalName; // for original file extension
		
						if($bIsIco = strpos($arOriginalName, '.ico') !== false){
							$script_files = Option::get("fileman", "~script_files", "php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht,py,psp,var");
							$arScriptFiles = explode(',', $script_files);
							if(($p = array_search('ico', $arScriptFiles)) !== false){
								unset($arScriptFiles[$p]);
							}
		
							$tmp = implode(',', $arScriptFiles);
							Option::set("fileman", "~script_files", $tmp);
						}
		
						if($fileID = \CFile::SaveFile($arFile, self::$moduleClass)){
							$arValues[] = $fileID;
						}
		
						if($bIsIco){
							Option::set("fileman", "~script_files", $script_files);
						}
					}
		
					$newVal = serialize($arValues);
				}
		
				if(!isset($_FILES[$optionCode."_".$this->siteID]) || (!strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['n0']) && !strlen($_FILES[$optionCode."_".$this->siteID]['tmp_name']['0']) && !isset($_REQUEST[$optionCode."_".$this->siteID.'_del']))){
					//return;
				}
		
				if($optionCode === 'FAVICON_IMAGE'){
					//copy favicon for search bots
					self::$moduleClass::CopyFaviconToSiteDir($newVal, $this->siteID);
				}
		
				if(is_array($newVal)){
					$newVal = serialize($newVal);
				}
				
				Option::set(self::MODULE_ID, $optionCode, $newVal, $this->siteID);
				unset($this->tabOptions[$optionCode]);
			}
			elseif($arOption["TYPE"] == "selectbox"){
				$this->checkList($arOption);

						
				if(isset($arOption["SUB_PARAMS"]) && $arOption["SUB_PARAMS"])
				{
					if (!$newVal) {
						$newVal = Option::get(self::MODULE_ID, $optionCode, $arOption['DEFAULT'], $this->siteID);
					}
				
					if(isset($arOption["LIST"]) && $arOption["LIST"])
					{
						$arSubValues = array();
						foreach($arOption["LIST"] as $key2 => $value)
						{
							if($arOption["SUB_PARAMS"][$key2] && $key2 == $newVal)
							{
								/* get custom blocks */
								$arNewOptions = \Aspro\Functions\CAsproLite::getCustomBlocks($this->siteID);
								if ($arNewOptions) {
									$arOption["SUB_PARAMS"][$key2] += $arNewOptions;
								}
								/* */

								foreach($arOption["SUB_PARAMS"][$key2] as $key3 => $arSubValue)
								{
									if($_REQUEST[$key2."_".$key3."_".$this->siteID])
									{
										$arSubValues[$key3] = $_REQUEST[$key2."_".$key3."_".$this->siteID];
										unset($this->tabOptions[$key2."_".$key3]);
									}
									// elseif($this->tabOptions[$key2."_".$key3])
									else
									{
										if($arSubValue["TYPE"] == "checkbox" && $key2 == $newVal && !isset($arSubValue["VISIBLE"])){
											$arSubValues[$key3] = "N";
										}
										if($this->tabOptions[$key2."_".$key3]) {
											unset($this->tabOptions[$key2."_".$key3]);
										}
									}
		
									//set special options for index components
									if(isset($arSubValue['INDEX_BLOCK_OPTIONS']))
									{
										if(isset($arSubValue['INDEX_BLOCK_OPTIONS']['TOP']) && $arSubValue['INDEX_BLOCK_OPTIONS']['TOP']) {
											foreach($arSubValue['INDEX_BLOCK_OPTIONS']['TOP'] as $topOptionKey => $topOption) {
												$code_tmp = $topOptionKey.'_'.$key3.'_'.$key2;
												if($_REQUEST[$code_tmp.'_'.$this->siteID])
													Option::set(self::MODULE_ID, $code_tmp, $_REQUEST[$code_tmp.'_'.$this->siteID], $this->siteID);
												else
													Option::set(self::MODULE_ID, $code_tmp, 'N', $this->siteID);
											}
										}
										if(isset($arSubValue['INDEX_BLOCK_OPTIONS']['BOTTOM']) && $arSubValue['INDEX_BLOCK_OPTIONS']['BOTTOM']) {
											foreach($arSubValue['INDEX_BLOCK_OPTIONS']['BOTTOM'] as $bottomOptionKey => $bottomOption) {
												$code_tmp = $bottomOptionKey.'_'.$key3.'_'.$key2;
												if($_REQUEST[$code_tmp.'_'.$this->siteID])
													Option::set(self::MODULE_ID, $code_tmp, $_REQUEST[$code_tmp.'_'.$this->siteID], $this->siteID);
												else
													Option::set(self::MODULE_ID, $code_tmp, 'N', $this->siteID);
											}
										}
									}
		
									//set default template index components
									if(isset($arSubValue['TEMPLATE']) && $arSubValue['TEMPLATE'])
									{
										$code_tmp = $key2.'_'.$key3.'_TEMPLATE';
										if($_REQUEST[$code_tmp.'_'.$this->siteID])
											Option::set(self::MODULE_ID, $code_tmp, $_REQUEST[$code_tmp.'_'.$this->siteID], $this->siteID);
		
										if($arSubValue['TEMPLATE']['LIST'])
										{
											$arTmpDopConditions = array();
											foreach($arSubValue['TEMPLATE']['LIST'] as $skey => $arSValue)
											{
												if($arSValue['ADDITIONAL_OPTIONS'])
												{
													foreach($arSValue['ADDITIONAL_OPTIONS'] as $additionalOptionKey => $additionalOption) {
														$strCodeTmp = $key2.'_'.$key3.'_'.$additionalOptionKey.'_'.$skey;
														if($_REQUEST[$strCodeTmp.'_'.$this->siteID])
															$additionalOptionVal = $_REQUEST[$strCodeTmp.'_'.$this->siteID];
														else {
															if($additionalOption['TYPE'] === 'checkbox'){
																$additionalOptionVal = 'N';
															}
															else {
																$additionalOptionVal = $additionalOption['DEFAULT'];
															}
														}
		
														\Bitrix\Main\Config\Option::set(self::MODULE_ID, $strCodeTmp, $additionalOptionVal, $this->siteID);
													}
												}
											}																
										}
									}
								}
		
								//sort order prop for main page
								$param = 'SORT_ORDER_'.$optionCode.'_'.$key2;
								if(isset($_REQUEST[$param.'_'.$this->siteID]))
								{
									Option::set(self::MODULE_ID, $param, $_REQUEST[$param.'_'.$this->siteID], $this->siteID);
								}
							}
						}

						if($arSubValues){
							Option::set(self::MODULE_ID, "NESTED_OPTIONS_".$optionCode."_".$newVal, serialize($arSubValues), $this->siteID);
						}
					}
				}
			}
			elseif($arOption["TYPE"] == "multiselectbox"){
				$newVal = @implode(",", (array)$newVal);
			}

			if(
				$optionCode == "YA_COUNTER_ID" &&
				strlen($newVal)
			){
				$newVal = str_replace('yaCounter', '', $newVal);
			}

			if (is_array($newVal)) {
				$newVal = (string)$newVal;
			}
		
			Option::set(self::MODULE_ID, $optionCode, $newVal, $this->siteID);

			if($arOption['TYPE'] != 'file'){
				$this->tabOptions[$optionCode] = $newVal;
			}
		
			if($optionCode == "CUSTOM_FONT"){
				$path = \Bitrix\Main\Application::getDocumentRoot().'/bitrix/components/aspro/theme.'.self::$moduleClass::solutionName.'/css/user_font_'.$this->siteID.'.css';
				$content = '';
				if($newVal){
					$string = str_replace(array('link href=', '&display=swap'), '', $newVal);
					$stringLength = strlen($string);
					$startLetter = strpos($string, '=');
					$string = substr($string, $startLetter + 1, $stringLength);
					$endLetter = strpos($string, ':');
					$string = ($endLetter ? substr($string, 0, $endLetter) : $string);
					$string = str_replace('" rel="stylesheet"', '', $string);
					$endLetter = strpos($string, '&amp');
					$string = ($endLetter ? substr($string, 0, $endLetter) : $string);
					$string = trim($string, '"');
					$content = "body,h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .popup-window,body div.bx-yandex-map,.fancybox-title{font-family: '".str_replace('+', ' ', $string)."', sans-serif;}";
				}
				\Bitrix\Main\IO\File::putFileContents($path, $content);
			}
		
			$this->checkList($arOption);
			$this->checkAll($arOption, $optionListKey);
		}

		public function setThemeColorsOptions($optionCode) {
			if($optionCode == "BASE_COLOR_CUSTOM" || $optionCode == 'CUSTOM_BGCOLOR_THEME'){
				self::$moduleClass::CheckColor($this->request[$optionCode."_".$this->siteID]);
			}

			if($optionCode == "BASE_COLOR" && $this->request[$optionCode."_".$this->siteID] === 'CUSTOM'){
				Option::set(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', $this->siteID);
			}
			elseif($optionCode == "BGCOLOR_THEME" && $this->request[$optionCode."_".$this->siteID] === 'CUSTOM'){
				Option::set(self::MODULE_ID, "NeedGenerateCustomThemeBG", 'Y', $this->siteID);
			}
		}

		public function getCustomFontVal($optionCode, $newVal) {
			$newVal = trim(str_replace(array('>', '<', 'rel="stylesheet"'), '', $newVal));

			return $newVal;
		}

		public function getCheckBoxVal($newVal) {
			if(!strlen($newVal) || $newVal != "Y")
				$newVal = "N";

			return $newVal;
		}

		private function getCurrentKey($arOption, $needKey) {
			$result = false;

			if(strpos($needKey, ':') !== false){
				$needKey = explode(':', $needKey);
				if(
					array_key_exists($needKey[0], $arOption) &&
					is_array($arOption[$needKey[0]]) &&
					array_key_exists($needKey[1], $arOption[$needKey[0]]) &&
					$arOption[$needKey[0]][$needKey[1]]
				){
					$result = $arOption[$needKey[0]][$needKey[1]];
				}
			}
			else{
				if(
					array_key_exists($needKey, $arOption) &&
					$arOption[$needKey]
				){
					$result = $arOption[$needKey];
				}
			}

			return is_array($result) ? $result : false;
		}

		public static function getBackParams(&$arValues, &$arDefaultValues, $arOption, $siteId) {
			self::getBackParamsAdditional($arValues, $arDefaultValues, $arOption, '', $siteId);
			self::getBackParamsToggle($arValues, $arDefaultValues, $arOption, '', $siteId);
			self::getBackParamsDepend($arValues, $arDefaultValues, $arOption, '', $siteId);
			self::getBackParamsList($arValues, $arDefaultValues, $arOption, $siteId);
		}

		public static function getBackParamsList(&$arValues, &$arDefaultValues, $arOption, $siteId) {
			if($arOption['LIST']) {
				foreach($arOption['LIST'] as $key => $arListOption){
					if(is_array($arListOption)){
						self::getBackParamsAdditional($arValues, $arDefaultValues, $arListOption, $key, $siteId);
						self::getBackParamsToggle($arValues, $arDefaultValues, $arListOption, $key, $siteId);
						self::getBackParamsDepend($arValues, $arDefaultValues, $arListOption, $key, $siteId);
					}
				}
			}
		}

		public static function getBackParamsDepend(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId){
			if(
				array_key_exists('DEPENDENT_PARAMS', $arOption) &&
				is_array($arOption['DEPENDENT_PARAMS']) &&
				$arOption['DEPENDENT_PARAMS']
			){
				foreach($arOption['DEPENDENT_PARAMS'] as $childKey => $childOption){
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);

					self::getBackParamsAdditional($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsToggle($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsDepend($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}

		public static function getBackParamsAdditional(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId) {
			if(
				array_key_exists('ADDITIONAL_OPTIONS', $arOption) &&
				is_array($arOption['ADDITIONAL_OPTIONS']) &&
				$arOption['ADDITIONAL_OPTIONS']
			){
				foreach($arOption['ADDITIONAL_OPTIONS'] as $childKey => $childOption){
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);

					self::getBackParamsAdditional($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsToggle($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsDepend($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}

		public static function getBackParamsToggle(&$arValues, &$arDefaultValues, $arOption, $parentKey, $siteId) {
			if(
				array_key_exists('TOGGLE_OPTIONS', $arOption) &&
				is_array($arOption['TOGGLE_OPTIONS']) &&
				array_key_exists('OPTIONS', $arOption['TOGGLE_OPTIONS']) &&
				$arOption['TOGGLE_OPTIONS']['OPTIONS']
			){
				foreach($arOption['TOGGLE_OPTIONS']['OPTIONS'] as $childKey => $childOption){
					$optionCurrentKey = $childKey.'_'.$parentKey;
					$arDefaultValues[$optionCurrentKey] = $childOption['DEFAULT'];
					$arValues[$optionCurrentKey] = Option::get(self::MODULE_ID, $optionCurrentKey, $childOption['DEFAULT'], $siteId);

					self::getBackParamsAdditional($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsToggle($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsDepend($arValues, $arDefaultValues, $childOption, $parentKey, $siteId);
					self::getBackParamsList($arValues, $arDefaultValues, $childOption, $siteId);
				}
			}
		}
	}
}
?>