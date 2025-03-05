<?php

namespace Aspro\Lite\Video;
use \Bitrix\Main\Localization\Loc,
    \Aspro\Functions\CAsproLite as SolutionFunctions;

class Iframe {
    public static function getVideoBlock($arVideo): array {
        $arVideo = is_array($arVideo)
            ? self::getVideoArray($arVideo)
            : self::getVideoItem($arVideo);

        return (array)$arVideo;
    }

    public static function getVideoItem(string $video): string {
        if (strpos($video, 'iframe') === false) {
            if (strpos($video, 'youtube') !== false) {
               $video = self::checkYoutube($video);
            } elseif (strpos($video, 'rutube') !== false) {
               $video = self::checkRutube($video);
            } else {
               $video = self::getError($video);
            }
        }

        return $video;
    }

    public static function getVideoArray(array $arVideo): array {
        foreach ($arVideo as $key => $video) {
            $arVideo[$key] = self::getVideoItem($video);
        }

        return $arVideo;
    }

    public static function checkRutube(string $video): string {
        $matches = [];
        $videoID = '';
        preg_match('/rutube.ru\/(play\/embed|video)\/(.*)/s', $video, $matches);

        if (isset($matches[2])) {
            $videoID = str_replace('/', '', $matches[2]);
        }

        return $videoID
            ? '<iframe frameborder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen width="660" height="457" src="https://rutube.ru/play/embed/'.$videoID.'"></iframe>'
            : self::getError($video);

    }

    public static function checkYoutube(string $video): string {
        $videoID = '';
        if (strpos($video, 'v=') !== false ) {
            $params = explode('?', $video)[1];
            $query = [];
            parse_str($params, $query);
            
            $videoID = $query['v'];
            unset($query['v']);

            if (!empty($query)) {
                $query = array_map(function($key, $value) {
                    return $key.'='.$value;
                }, array_keys($query), array_values($query));

                $videoID .= '?v='.implode('&', $query);
            }
        } else {
            $parse_string = explode('/', $video);
            $videoID = array_pop($parse_string);
        }

        return $videoID
            ? '<iframe frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen width="660" height="457" src="https://www.youtube.com/embed/'.$videoID.'"></iframe>'
            : self::getError($video);
    }

    public static function getError(string $link): string {
        ob_start();
        SolutionFunctions::showBlockHtml([
            'FILE' => 'video/error_frame.php',
            'PARAMS' => [
                'MESSAGE' => Loc::getMessage('IFRAME_CODE_ERROR', [
                    '#LINK#' => $link,
                ]),
            ]
        ]);
        $html = trim(ob_get_clean());

        return $html;
    }
}