<?php

use Bitrix\Main\Page\Asset;

Asset::getInstance()->addCss('/bitrix/templates/aspro-lite/css/jquery.fancybox.min.css');
Asset::getInstance()->addJs('/bitrix/templates/aspro-lite/js/jquery.fancybox.min.js');

?>

<div class="maxwidth-theme index-gallery">
    <h2>Фотогалерея с нашего завода Super Soco</h2>

    <div class="index-gallery__wrapper">
        <?php
        $arrFactories = [
            29 => "/upload/medialibrary/81c/fwfu0ir7gpv5h4x8uvfijpkwyens7hj3.jpg",
            30 => "/upload/medialibrary/547/p1ffmdp624vnil42jnchrej42i747dpd.jpg",
            31 => "/upload/medialibrary/68b/8ih1ol2wd8ymegu4e2rsloxn5ba4sres.jpg",
            32 => "/upload/medialibrary/908/yjw452s8q3pir73ngthhbv0nph4v1t61.jpg",
            15 => "/upload/medialibrary/851/gzdftzvd2atge5r0p7ph129twdlu3g9j.jpg",
            16 => "/upload/medialibrary/fbc/5m4zqhx8t9p6uculamq252a4d2eo8s19.jpg",
            17 => "/upload/medialibrary/d3f/045qyxy3f8stv9bivx8l9krunlj0nvs5.jpg",
            18 => "/upload/medialibrary/b84/99mcpgul1205f6h2n04x7salalnll3nd.jpg",
            20 => "/upload/medialibrary/bb5/rzc25tmon6nt5t4y0b5otc90enhqr0mt.jpg",
            1  => "/upload/medialibrary/fd0/omu3mkezhrmkc1trbgszc94l5fwviqwd.jpg",
            2  => "/upload/medialibrary/567/33nle2qyjkhbi7zba3tbvl9k7x6l7p4s.jpg",
            3  => "/upload/medialibrary/34b/h184cdcp47xmw2jr327ilvc225773fn0.jpg",
            4  => "/upload/medialibrary/f69/37gdyq41n07y2viujyu8o9mxmfw8ctar.jpg",
            5  => "/upload/medialibrary/6f3/li6xs8b5wan8nx9xyo7gp0g20fbmux3l.jpg",
            6  => "/upload/medialibrary/55a/nc0byl7u05shelw2d4fmduv484t0lxxp.jpg",
            7  => "/upload/medialibrary/724/1h7pos4rsvmf9rzwojg8bn1emeql370f.jpg",
            8  => "/upload/medialibrary/a8e/w3a0f59udfs2xees80qte1pkhh2lgyrr.jpg",
            9  => "/upload/medialibrary/dc1/j1wpdmk3ix9cxur9g67a6x81re4r8szf.jpg",
            10 => "/upload/medialibrary/736/1gqwgllbyshl72lutri3uzful666a39m.jpg",
            19 => "/upload/medialibrary/84e/7v2z55ysa2xw5pxvorp26gp8zuulsqs7.jpg",
            11 => "/upload/medialibrary/a87/61pij48r7c44i771v8mpw9jbb5bicimf.jpg",
            22 => "/upload/medialibrary/edc/qhajbejwqyem04bjhodtpw3okapcxquu.jpg",
            13 => "/upload/medialibrary/caa/wjwxemwz4o6fflk8u5gfk89alwbz0qpj.jpg",
            24 => "/upload/medialibrary/e55/lphgyea4lvw3lj4rqjptg1b13scewz1s.jpg",
            21 => "/upload/medialibrary/4cc/vw3gr54lauwmlce65n04ppqsamezm1w7.jpg",
            12 => "/upload/medialibrary/c86/kag73pqm2qd9jswtxl4nwol3jzzafhlt.jpg",
            14 => "/upload/medialibrary/7c8/f0xff9eztuoqb41t3gek1wazqi25yb5v.jpg",
            23 => "/upload/medialibrary/987/fjfk684ppow8ueg19gyor8gr8u69h2sn.jpg",
            26 => "/upload/medialibrary/d15/oqwnuh2mszo8pjtnziz7qupt9zzfpvax.jpg",
            27 => "/upload/medialibrary/02e/0fvrdvp962fcrwf7qls6z59fwn6k2k4c.jpg",
            28 => "/upload/medialibrary/9a1/ibf57qqqmz4owvd4mj2fwwhturvgmnss.jpg",
        ];

        foreach ($arrFactories as $key => $value) {
            $imgAlt = "Завод Super Soco, фото $key";

            echo <<<EOF
                <div class="index-gallery__image">
                    <a href="$value" data-fancybox="gallery">
                        <img title="$imgAlt" alt="$imgAlt" loading="lazy" src="$value">
                    </a>
                </div>
            EOF;
        }
        ?>
    </div>
</div>