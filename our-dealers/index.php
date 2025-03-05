<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Официальные дилеры Super Soco в РФ");

$APPLICATION->SetPageProperty(
    "title",
    "Список официальных дилеров Super Soco в РФ +7 499 704-42-08"
);

$APPLICATION->SetPageProperty(
    "description",
    "Представлены наши официальные дилеры Super Soco на территории РФ."
);

$dealers = include "dealers.php";
?>

    <div class="our-dealers">

        <div class="mb-20">
            Для начала сотрудничества с нами отправьте письмо на электронную почту
            <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#112;&#97;&#114;&#116;&#110;&#101;&#114;&#115;&#64;&#115;&#117;&#112;&#101;&#114;&#115;&#111;&#99;&#111;&#45;&#114;&#117;&#115;&#115;&#105;&#97;&#46;&#114;&#117;">&#112;&#97;&#114;&#116;&#110;&#101;&#114;&#115;&#64;&#115;&#117;&#112;&#101;&#114;&#115;&#111;&#99;&#111;&#45;&#114;&#117;&#115;&#115;&#105;&#97;&#46;&#114;&#117;</a>.
            <br>
            <strong>Наш лучший менеджер</strong> свяжется с вами в кратчайшие сроки!
        </div>

        <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A7d9e1b3c203835e4972add5f6c08c648c2c8238fed574080d977ee6b9ec288b2&amp;source=constructor"
                width="100%" height="500" frameborder="0"></iframe>

        <div class="our-dealers__wrapper mt-60">

            <div class="our-dealers__description">
                <?php foreach ($dealers as $city => $dealersInCity) : ?>
                    <div class="our-dealers__dealer">

                        <div class="our-dealers__dealer-city">
                            <div class="svg-address our-dealers-svg"></div>
                            <div>
                                <h2><?=$city?></h2>
                            </div>
                        </div>

                        <?php foreach ($dealersInCity as $dealerName => $dealerDetails) : ?>

                            <div class="our-dealers__dealer-wrapper">

                                <div class="our-dealers__dealer-wrapper__image">
                                    <a rel="nofollow" href="<?=$dealerDetails['url']?>">
                                        <img alt="Дилер Super Soco в городе <?=$city?> - <?=$dealerName?>"
                                             loading="lazy"
                                             src="<?=$dealerDetails['image']['src']?>">
                                    </a>
                                </div>

                                <div class="our-dealers__dealer-wrapper_text">
                                    <h3>
                                        <a rel="nofollow" href="<?=$dealerDetails['url']?>">
                                            <?=$dealerName?>
                                        </a>
                                    </h3>
                                    <p class="mt-0">
                                        <strong>Сайт:</strong>
                                        <a rel="nofollow" href="<?=$dealerDetails['url']?>">
                                            <?=$dealerDetails['url']?>
                                        </a>
                                        <br>

                                        <?php
                                        foreach ($dealerDetails['branches'] as $dealerBranches) {
                                            if (!empty($dealerBranches['work_time'])) {
                                                $workTimeBlock = <<<EOF
                                                    <br>
                                                    <strong>Часы работы:</strong>
                                                    {$dealerBranches['work_time']}
                                                EOF;
                                            } else {
                                                $workTimeBlock = '';
                                            }

                                            echo <<<EOF
                                                <p class="mb-5">
                                                    <strong>Адрес:</strong>
                                                    {$dealerBranches['address']}
                                                    {$dealerBranches['address_addon']}
                                                    <br>
                                                    <strong>Телефон:</strong>
                                                    <a href="tel:{$dealerBranches['phone']['link']}">
                                                        {$dealerBranches['phone']['full']}
                                                    </a>
                                                    $workTimeBlock
                                                </p>
                                            EOF;
                                        }
                                        ?>

                                    </p>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>

    </div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>