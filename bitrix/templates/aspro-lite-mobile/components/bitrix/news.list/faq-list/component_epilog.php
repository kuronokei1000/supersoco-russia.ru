<?
if (!$templateData['ITEMS']) {
	$GLOBALS['APPLICATION']->SetPageProperty('BLOCK_FAQS', 'hidden');
} else {
	\TSolution\Extensions::init('bootstrap.lite');
	TSolution\ExtensionsMobile::init(['accordion']);
}
?>