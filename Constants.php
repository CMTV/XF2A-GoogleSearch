<?php
/**
 * Google Search xF2 addon by CMTV
 * You can do whatever you want with this code
 * Enjoy!
 */

namespace CMTV\GoogleSearch;

class Constants
{
    const ADDON_ID = 'CMTV\GoogleSearch';
    const ADDON_ID_ESC = 'CMTV_GoogleSearch';

    //

    public static function mvc(string $body): string
    {
        return self::ADDON_ID . ':' . $body;
    }

    public static function option(string $id): string
    {
        return self::ADDON_ID_ESC . '_' . $id;
    }

    public static function phrase(string $phraseName): string
    {
        return self::ADDON_ID_ESC . '_' . $phraseName;
    }

    public static function template(string $templateName): string
    {
        return self::ADDON_ID_ESC . '_' . $templateName;
    }
}