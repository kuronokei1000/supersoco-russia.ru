<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?use \Aspro\Lite\Mobile\General as MSolution?>
        <?if(!$isIndex):?>
            <?TSolution::checkRestartBuffer();?>
            <?TSolution::get_banners_position('CONTENT_BOTTOM');?>
            <?if($APPLICATION->GetProperty("FULLWIDTH")!=='Y'):?>
                </div>
            <?endif;?>
        <?else:?>
            <?TSolution::ShowPageType('indexblocks');?>
        <?endif;?>
    </main>
</layout>
<?TSolution::showPageType('footer');?>
<?MSolution::showPageTypeFromSolution('footer');?>
	<?@include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/bottom_footer.php'));?>
</body>
</html>