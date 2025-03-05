<?
namespace Aspro\Lite\Sender\Preset;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader,
    Bitrix\Main\IO\File,
    Bitrix\Main\Application,
    Bitrix\Main\Config\Option,
    CLite as Solution;

Loc::loadMessages(__FILE__);

class Template {
    public static function OnPresetTemplateList($templateType = null, $templateId = null) {
        if (
            $templateType &&
            $templateType !== 'ADDITIONAL'
        ) {
            return [];
        }

        return [
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_common',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/sender/preset/template/1column1.png',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('common'),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_forgotten_cart',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/cart.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('forgotten_cart'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_forgotten_cart_coupon',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/cart_coupon.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('forgotten_cart_coupon'),
                'DESC' => Loc::getMessage('DESC_'.$id),
                'HOT' => 'Y',
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_sale_products',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/sale.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('sale_products'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_sale_coupon',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/sale.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('sale_coupon'),
                'DESC' => Loc::getMessage('DESC_'.$id),
                'HOT' => 'Y',
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_rate_quality',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/rate.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('rate_quality'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_rate_quality_order',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/rate.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('rate_quality_order'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_product_available',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/products.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('product_available'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
            [
                'ID' => $id = 'aspro_'.Solution::solutionName.'_new_order',
                'NAME' => Loc::getMessage('TEMPLATE_'.$id),
                'ICON' => '/bitrix/images/'.Solution::moduleID.'/preset/order.jpg',
                'TYPE' => 'ADDITIONAL',
                'HTML' => static::getContent('new_order'),
                'DESC' => Loc::getMessage('DESC_'.$id),
            ],
        ];
    }

    public static function getFile(string $templateCode) :string {
        $path = 'modules/'.Solution::moduleID.'/preset/template/'.bx_basename($templateCode).'.php';
        $file = Loader::getLocal($path);
        if ($file) {
            return $file;
        }

        $root = Loader::getDocumentRoot();
        return $root.'/bitrix/'.$path;
    }

    public static function getContent(string $templateCode) :string {
        $content = $templateContent = $themeContent = '';

        Loader::includeModule('fileman');

        extract(self::getVars());

        $file = self::getFile($templateCode);
        if (
            $file && 
            file_exists($file)
        ) {
            ob_start();
            @include $file;
            $templateContent = trim(ob_get_contents());
            ob_end_clean();

            if (
                strlen($templateContent) &&
                \Bitrix\Fileman\Block\Editor::isContentSupported($templateContent)
            ) {
                $themeCode = self::getTheme($templateCode);
                $themeFile = self::getFile($themeCode);
                if (
                    $themeFile && 
                    file_exists($themeFile)
                ) {
                    ob_start();
                    @include $themeFile;
                    $themeContent = trim(ob_get_contents());
                    ob_end_clean();
                }

                if (
                    strlen($themeContent) &&
                    \Bitrix\Fileman\Block\Editor::isContentSupported($themeContent)
                ) {
                    $content = str_replace(
                        [
                            '%TEMPLATE_CONTENT%',
                            '%COPYRIGHT%',
                            '%EMAIL%',
                            '%PHONE%',
            
                            '%SITE_ID%',
                            '%SITE_ADDRESS%',
                            '%SITE_EMAIL%',
                            '%BASE_COLOR%',
                            '%LOGO_SRC%',
                            '%LOGO_BG_COLOR%',
                            '%OUTER_BORDER_RADIUS%',
                            '%CATALOG_PAGE_URL%',
                            '%BASKET_PAGE_URL%',
                            '%ORDER_PAGE_URL%',
                            '%PERSONAL_PAGE_URL%',
                        ],
                        [
                            $templateContent,
                            $copyrightHtml,
                            $emailHtml,
                            $phoneHtml,
            
                            $siteId,
                            $siteAddressFull,
                            $siteEmail,
                            $baseColor,
                            $logoSrc,
                            $logoBgColor,
                            $outerBorderRadius,
                            $catalogPageUrl,
                            $basketPageUrl,
                            $orderPageUrl,
                            $personalPageUrl,
                        ],
                        $themeContent
                    );
                }
            }
        }

        return $content;
    }

    public static function getVars($siteId = '') :array {
        static $arVars;

        if (!isset($arVars)) {
            $arVars = [];

            // include CMainPage
            require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/mainpage.php');
    
            \Bitrix\Main\Loader::includeModule('sale');
            \Bitrix\Main\Loader::includeModule('catalog');
        }

        if (!$siteId) {
            // get site_id by host
            $CMainPage = new \CMainPage();
            $siteId = $CMainPage->GetSiteByHost();
            if (!$siteId) {
                $siteId = 's1';
            }
        }
    
        if (!isset($arVars[$siteId])) {
            // get site info
            $arSite = \CSite::GetByID($siteId)->Fetch();
            $arSite['DIR'] = str_replace('//', '/', '/'.$arSite['DIR']);
            if (!strlen($arSite['DOC_ROOT'])) {
                $arSite['DOC_ROOT'] = Application::getDocumentRoot();
            }
            $arSite['DOC_ROOT'] = str_replace('//', '/', $arSite['DOC_ROOT'].'/');
            $siteDir = str_replace('//', '/', $arSite['DOC_ROOT'].$arSite['DIR']);
            $siteProtocol = Option::get('sender', 'link_protocol', '');
            $siteProtocol = ($siteProtocol ? $siteProtocol : 'http');
            $siteAddress = $arSite['SERVER_NAME'];
            $siteAddressFull = $siteProtocol.'://'.$arSite['SERVER_NAME'];
    
            // site email
            $saleOrderEmail = Option::get('sale', 'order_email', 'sale@'.$siteAddress);
            $siteEmail = ($arSite['EMAIL'] ? $arSite['EMAIL'] : $saleOrderEmail);

            // imgPath
            $imgPath = '/bitrix/images/'.Solution::moduleID.'/preset';
    
            // get vars
            // $arModuleOptions = Solution::GetBackParametrsValues($siteId, $arSite['DIR']);

            // use regionality
            $useRegionality = Option::get(Solution::moduleID, 'USE_REGIONALITY', 'N');
    
            // base color
            $baseColor = Option::get(Solution::moduleID, 'BASE_COLOR', '13', $siteId);
            $baseColorCustom = Option::get(Solution::moduleID, 'BASE_COLOR_CUSTOM', '1976d2', $siteId);
            if ($baseColor === 'CUSTOM') {
                $baseColor = '#'.$baseColorCustom;
            }
            else {
                $baseColor = Solution::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['LIST'][$baseColor]['COLOR'];
            }
    
            // outer border radius
            $outerBorderRadius = Option::get(Solution::moduleID, 'OUTER_BORDER_RADIUS', '12', $siteId);
            $outerBorderRadius = ((intval($outerBorderRadius) <= 0) ? 12 : intval($outerBorderRadius)).'px';
            
            // logo
            $coloredLogo = Option::get(Solution::moduleID, 'COLORED_LOGO', 'N', $siteId);
            $logoSrc = '/include/logo.png';
            $arLogo = Option::get(Solution::moduleID, 'LOGO_IMAGE_EMAIL', serialize(array()), $siteId);
            if (
                $arLogo &&
                ($arLogoValue = Solution::unserialize($arLogo)) &&
                is_array($arLogoValue)
            ) {
                $logoSrc = \CFIle::GetPath(current($arLogoValue));
            }
            else {
                $arLogo = Option::get(Solution::moduleID, 'LOGO_IMAGE', serialize(array()), $siteId);
                if (
                    $arLogo &&
                    ($arLogoValue = Solution::unserialize($arLogo)) &&
                    is_array($arLogoValue)
                ) {
                    $logoSrc = \CFIle::GetPath(current($arLogoValue));
                }
            }
    
            // logo bg color
            $logoBgColor = 'none';
            if ($coloredLogo === 'Y') {
                $logoBgColor = $baseColor;
            }

            // catalog page url
            $catalogPageUrl = Option::get(Solution::moduleID, 'CATALOG_PAGE_URL', '', $siteId) ?: '#SITE_DIR#catalog/';
            $catalogPageUrl = str_replace('#'.'SITE_DIR#', $arSite['DIR'], $catalogPageUrl);

            // basket page url
            $basketPageUrl = Option::get(Solution::moduleID, 'BASKET_PAGE_URL', '', $siteId) ?: '#SITE_DIR#basket/';
            $basketPageUrl = str_replace('#'.'SITE_DIR#', $arSite['DIR'], $basketPageUrl);

            // order page url
            $orderPageUrl = Option::get(Solution::moduleID, 'ORDER_PAGE_URL', '', $siteId) ?: '#SITE_DIR#order/';
            $orderPageUrl = str_replace('#'.'SITE_DIR#', $arSite['DIR'], $orderPageUrl);

            // personal page url
            $personalPageUrl = Option::get(Solution::moduleID, 'PERSONAL_PAGE_URL', '', $siteId) ?: '#SITE_DIR#personal/';
            $personalPageUrl = str_replace('#'.'SITE_DIR#', $arSite['DIR'], $personalPageUrl);
    
            // socials
            $socialVk = Option::get(Solution::moduleID, 'SOCIAL_VK', '', $siteId);
            $socialFacebook = Option::get(Solution::moduleID, 'SOCIAL_FACEBOOK', '', $siteId);
            $socialTwitter = Option::get(Solution::moduleID, 'SOCIAL_TWITTER', '', $siteId);
            $socialInstagram = Option::get(Solution::moduleID, 'SOCIAL_INSTAGRAM', '', $siteId);
            $socialTelegram = Option::get(Solution::moduleID, 'SOCIAL_TELEGRAM', '', $siteId);
            $socialYoutube = Option::get(Solution::moduleID, 'SOCIAL_YOUTUBE', '', $siteId);
            $socialOdnoklassniki = Option::get(Solution::moduleID, 'SOCIAL_ODNOKLASSNIKI', '', $siteId);
            $socialMail = Option::get(Solution::moduleID, 'SOCIAL_MAIL', '', $siteId);
            $socialTikTok = Option::get(Solution::moduleID, 'SOCIAL_TIKTOK', '', $siteId);
            $socialZen = Option::get(Solution::moduleID, 'SOCIAL_ZEN', '', $siteId);
            $socialPinterest = Option::get(Solution::moduleID, 'SOCIAL_PINTEREST', '', $siteId);
            $socialSnapchat = Option::get(Solution::moduleID, 'SOCIAL_SNAPCHAT', '', $siteId);
            $socialLinkedin = Option::get(Solution::moduleID, 'SOCIAL_LINKEDIN', '', $siteId);
            $socialAsproLink = Option::get(Solution::moduleID, 'SOCIAL_ASPRO_LINK', '', $siteId);
    
            // copyright
            $copyrightHtml = '&copy; '.$arSite['NAME'];
            $copyrightPath = $siteDir.'/include/footer/copy.php';
            if (File::isFileExists($copyrightPath)) {
                $copyrightHtml = File::getFileContents($copyrightPath);
    
                // cut php
                if (preg_match('/<\?(.*)\?>/is', $copyrightHtml, $matches)) {
                    if ($matches[1]) {
                        $copyrightHtml = str_replace(array($matches[1], '<?', '?>'), '', $copyrightHtml);
                    }
                }
            }
            $copyrightHtml = trim($copyrightHtml);

            // email
            $emailHtml = $siteEmail;
            $emailPath = $siteDir.'/include/contacts-site-email.php';
            if (File::isFileExists($emailPath)) {
                $emailHtml = File::getFileContents($emailPath);
                $emailHtml = strip_tags($emailHtml, '');
            }
            $emailHtml = trim($emailHtml);

            // phone
            $phoneHtml = '';
            $iCountPhones = Option::get(Solution::moduleID, 'HEADER_PHONES', 0, $siteId);
            if ($iCountPhones) {
                $phoneHtml = Option::get(Solution::moduleID, 'HEADER_PHONES_array_PHONE_VALUE_0', '', $siteId);
                $phoneHtml = strip_tags($phoneHtml, '');
            }
            $phoneHtml = trim($phoneHtml);

            $arVars[$siteId] = [
                'arSite' => $arSite,
                'siteId' => $siteId,
                'siteDir' => $siteDir,
                'siteProtocol' => $siteProtocol,
                'siteAddress' => $siteAddress,
                'siteAddressFull' => $siteAddressFull,
                'saleOrderEmail' => $saleOrderEmail,
                'siteEmail' => $siteEmail,
                'imgPath' => $imgPath,
                'useRegionality' => $useRegionality,
                'baseColor' => $baseColor,
                'baseColorCustom' => $baseColorCustom,
                'outerBorderRadius' => $outerBorderRadius,
                'logoSrc' => $logoSrc,
                'coloredLogo' => $coloredLogo,
                'logoBgColor' => $logoBgColor,
                'catalogPageUrl' => $catalogPageUrl,
                'basketPageUrl' => $basketPageUrl,
                'orderPageUrl' => $orderPageUrl,
                'personalPageUrl' => $personalPageUrl,
                'socialVk' => $socialVk,
                'socialFacebook' => $socialFacebook,
                'socialTwitter' => $socialTwitter,
                'socialInstagram' => $socialInstagram,
                'socialTelegram' => $socialTelegram,
                'socialYoutube' => $socialYoutube,
                'socialOdnoklassniki' => $socialOdnoklassniki,
                'socialMail' => $socialMail,
                'socialTikTok' => $socialTikTok,
                'socialZen' => $socialZen,
                'socialPinterest' => $socialPinterest,
                'socialSnapchat' => $socialSnapchat,
                'socialLinkedin' => $socialLinkedin,
                'socialAsproLink' => $socialAsproLink,
                'copyrightHtml' => $copyrightHtml,
                'emailHtml' => $emailHtml,
                'phoneHtml' => $phoneHtml,
            ];
        }

        return $arVars[$siteId];
    }

    public static function getVar($varName, $siteId = '') {
        return self::getVars($siteId)[$varName];
    }

    protected static function getTheme(string $templateCode) :string {
        if ($templateCode === 'new_order') {
            return 'theme_main';
        }

        return 'theme_marketing';
    }

    public static function hex2rgb($color = '', $opacity = 1.0) :string {
        $hex = str_replace('#', '', $color);
        if (strlen($hex) == 3) {
            $hex .= $hex;
        }

        $result = '#'.$hex;

        if (function_exists('hexdec')) {
            $split_hex = str_split($hex, 2);
            $result = 'rgba('.hexdec($split_hex[0]).','.hexdec($split_hex[1]).','.hexdec($split_hex[2]).','.strval($opacity).')';
        }

        return $result;
    }
}
