<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Гарантия на товар");

$APPLICATION->SetPageProperty(
    "title",
    "Гарантийное обслуживание Super Soco от официального представителя в РФ +7 499 704-42-08"
);

$APPLICATION->SetPageProperty(
    "description",
    "Одной из важных составляющих работы нашего интернет-магазина является то, что товар имеет сертификацию и фирменную гарантию производителя"
);
?>

    <div class="warranty">

        <div class="important-text mt-0">
            <div class="important-text__header">
                <div class="important-text__header-caption">
                    Наш товар имеет сертификацию и фирменную гарантию производителя!
                </div>
            </div>
        </div>

        <div class="warranty__wrapper">

            <div class="warranty__description">
                <h2>Гарантийное обслуживание</h2>
                <p>
                    Для осуществления гарантийного обслуживания необходим правильно заполненный, без помарок и
                    исправлений, гарантийный талон, в котором указаны:
                </p>

                <ul class="list-svg mt-10">
                    <li>Модель, серийный номер изделия, дата продажи и печать торгующей организации</li>
                    <li>Документ, подтверждающий покупку (товарная накладная, чек)</li>
                    <li>Полная комплектация товара</li>
                </ul>

                <div class="important-text">
                    <div class="important-text__header">
                        <div class="important-text__header-svg">
                            <div class="svg-triangle-exclamation"></div>
                        </div>
                        <div class="important-text__header-caption">
                            Обращаем ваше внимание
                        </div>
                    </div>
                    <div class="important-text__description">
                        <p>
                            При получении и оплате заказа, покупатель, в присутствии курьера, обязан проверить
                            комплектацию и внешний вид изделия на предмет наличия физических дефектов (царапин, трещин,
                            сколов и т.п.), а также комплектацию техники. После отъезда курьера, претензии по этим
                            вопросам не принимаются.
                        </p>
                    </div>
                </div>

                <div class="font-24 mb-10 mt-40">Гарантийное обслуживание не производится если:</div>
                <ul>
                    <li>Утерян или не заполнен гарантийный талон</li>
                    <li>Оборудование было поставлено на территорию РФ неофициально</li>
                    <li>Изделие имеет следы механического повреждения или вскрытия</li>
                    <li>Нарушены заводские пломбы</li>
                    <li>Были нарушены условия эксплуатации, транспортировки или хранения</li>
                    <li>Проводился ремонт лицами, не являющимися сотрудниками авторизованного сервисного центра</li>
                    <li>Использовались неоригинальные комплектующие</li>
                </ul>
            </div>
            <div class="warranty__image">
                <img alt="Гарантийное обслуживание Super Soco" loading="lazy"
                     src="/upload/medialibrary/7f4/mva1ao2veddf9u5pjaezysu87ubn234y.jpg">
            </div>
        </div>

    </div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");