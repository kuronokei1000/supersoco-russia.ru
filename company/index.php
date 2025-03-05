<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("О компании Super Soco");

$APPLICATION->SetPageProperty(
    "title",
    "О компании Super Soco - официальный представитель в РФ +7 499 704-42-08"
);

$APPLICATION->SetPageProperty(
    "description",
    "Super Soco – это инновационная технологическая компания, которая следует передовым технологиям и новейшим трендам в промышленном дизайне"
);

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss('/bitrix/templates/aspro-lite/css/jquery.fancybox.min.css');
Asset::getInstance()->addJs('/bitrix/templates/aspro-lite/js/jquery.fancybox.min.js');

?><div class="company">
	<p class="company__tagline">
		 Мы являемся официальным представителем компании Super Soco на территории России и стран СНГ!
	</p>
	<h2>О компании</h2>
	<div class="company__wrapper company__wrapper-prolog">
		<div class="company__description">
			<p>
 <strong>Super Soco</strong> – это инновационная технологическая компания, которая следует передовым технологиям и новейшим трендам в промышленном дизайне. Она фокусируется на предоставлении новых энергоэффективных интеллектуальных транспортных решений. Уже сейчас вы можете приобрести наши высокотехнологичные продукты и наслаждаться удовольствием от путешествий.
			</p>
			<p>
				 На данный момент Super Soco подал заявки и получил более <strong>205 патентов</strong>, в том числе мы получаем различные международные награды в области дизайна, включая:
			</p>
			<ul class="list-svg">
				<li>«China Good Design»</li>
				<li>японский конкурс «G-MARK Excellent Design»</li>
				<li>знаменитый немецкий «iF Product Design Award»</li>
			</ul>
			<p>
				 Super Soco уже экспортируется в более чем 74 зарубежных страны и региона, которые охватывают Европу, Северную Америку, Латинскую Америку, Азию, Ближний Восток и т.д.
			</p>
			<p>
				 Компания получила крупные инвестиции от хорошо известных промышленных гигантов, таких как: Xiaomi, Ducati, Shunwei, Jiufu, Weigao.
			</p>
		</div>
		<div class="company__image company__image-prolog">
 <a href="/upload/medialibrary/dd9/5vofop0s6yn1jqt9znwx4tuxh1o11fe2.jpg" data-fancybox="gallery"> <img alt="Официальный представитель компании Super Soco на территории РФ" src="/upload/medialibrary/dd9/5vofop0s6yn1jqt9znwx4tuxh1o11fe2.jpg" loading="lazy"> </a>
		</div>
	</div>
	<h2 class="mt-40" id="license">Наши лицензии</h2>
	<p>
		 Лицензии официального дистрибьютора Super Soco в России и на территории СНГ.
	</p>
	<div class="company__wrapper company__wrapper-license">
		 <?php
            $arrLicenses = [
                1 => '/upload/medialibrary/a71/sftdhrjobkpl8i6d5w0vhhgc282nvwff.png',
                2 => '/upload/medialibrary/b98/90rzqju4kw0i48prokvj1gneiidns7kn.png',
            ];

            foreach ($arrLicenses as $key => $value) {
                $imgAlt = "Лицензия официального дистрибьютора Super Soco в России и на территории СНГ, фото $key";

                echo <<<EOF
                    <div class="company__image company__image-license">
                        <a href="$value" data-fancybox="gallery">
                            <img title="$imgAlt" alt="$imgAlt" src="$value" loading="lazy">
                        </a>
                    </div>
                EOF;
            }
            ?>
	</div>
	<h2 class="mt-40" id="factory">Производство</h2>
	<p>
		 Производство укомплектовано современным производственным комплексом площадью <strong>30 000 кв.м</strong>
		и расположено в Нанкине, Китай. Производственные мощности, <strong>300 000 единиц в год</strong>, сосредоточены на производстве высококачественных электрических двухколесных транспортных средств, с лицензией на производство мотоциклов, утвержденной правительством.
	</p>
	<p>
		 Производственный комплекс расположен в зоне экономического развития Лишуй, в 42 км от центра Нанкина, в 12 км от Международного аэропорта Нанкин Лукоу и в 60 км от порта Нанкина, что удобно для дорожного движения и судоходства.
	</p>
	<div class="company__wrapper company__wrapper-factory">
		 <?php
            $arrFactories = [
                13 => '/upload/medialibrary/fdf/iuxyjr9895p01ucpmx4uo3mzffoktei7.jpg',
                5  => '/upload/medialibrary/987/kqp37lkelgu0dufanhtkxbx234vd7axk.jpg',
                6  => '/upload/medialibrary/206/u915rdd31rx9q26eimlf74y1va4ruc7z.jpg',
                12 => '/upload/medialibrary/011/vmn09hnvf0to5qpapcleyz44b0ssxsko.jpg',
                2  => '/upload/medialibrary/688/j1rzmc9xl5zdzr88ehzq11jbx2w5q2oj.jpg',
                1  => '/upload/medialibrary/cb1/rvs3tuzmj524brhse3lm8s45xds6f0g8.jpg',
                3  => '/upload/medialibrary/e64/e0bl01afjwn9kqs35492avs0tdqar7lt.jpg',
                4  => '/upload/medialibrary/a6b/3fj7gdk3ufzkmv1gzbrxhxtkv49hvq3e.jpg',
                7  => '/upload/medialibrary/f09/xtt4w3q0n0iai9yydwy3bloqbpn2ztcj.jpg',
                8  => '/upload/medialibrary/f1e/e897ztdn676fktdimpt92ht5q1b9wrnm.jpg',
                10 => '/upload/medialibrary/c16/743g09e2xd4cbhleldix89z384n27fdd.jpg',
                11 => '/upload/medialibrary/578/jryxv4gwi3hqob09mbcs8w3kmg5eb16x.jpg',
                9  => '/upload/medialibrary/2a6/p1elioqzfi8o614of14iau1hvsfnl22z.jpg',
            ];

            foreach ($arrFactories as $key => $value) {
                $imgAlt = "Наше производство, фото $key";

                echo <<<EOF
                    <div class="company__image company__image-factory">
                        <a href="$value" data-fancybox="gallery">
                            <img title="$imgAlt" alt="$imgAlt" loading="lazy" src="$value">
                        </a>
                    </div>
                EOF;
            }
            ?>
	</div>
	<h2 class="mt-40">Наши партнеры</h2>
	<div class="company__wrapper company__wrapper-partners">
		 <?php
            $arrPartners = [
                'White Siberia' => [
                    'url'     => 'https://white-siberia.ru',
                    'img_src' => '/upload/medialibrary/25a/36yty7pu1zfw6srvlcxxsrpo8drm4xuk.png',
                ],
                'Surron'        => [
                    'url'     => 'https://surron-official.ru',
                    'img_src' => 'https://supersoco-russia.ru/upload/medialibrary/001/g3ep9869dktdx3b1rb2pleo4pymm1waw.png',
                ],
            ];

            foreach ($arrPartners as $name => $value) {
                $imgAlt = "Партнер $name";

                echo <<<EOF
                    <div class="company__image company__image-partners">
                        <a href="{$value['url']}">
                            <img title="$imgAlt" alt="$imgAlt" loading="lazy" src="{$value['img_src']}">
                        </a>
                    </div>
                EOF;
            }
            ?>
	</div>
</div>
 <br><?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>