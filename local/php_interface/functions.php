<?php

function debug($var): void
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

/**
 * Канонические ссылки для всех страниц
 *
 * @param string $url
 *
 * @return string
 */
function getCanonicalUrl(string $url): string
{
    // Разбор URL на составляющие
    $parsedUrl = parse_url($url);
    $path = $parsedUrl['path'] ?? '';

    // Проверяем наличие /filter/ и удаляем его вместе с последующей частью URL
    $filterPos = strpos($path, '/filter/');
    if ($filterPos !== false) {
        $path = substr($path, 0, $filterPos);
    }

    // Убираем избыточные слеши в конце пути перед добавлением финального слеша и возвращаем каноническую ссылку
    return rtrim($path, '/') . '/';
}
