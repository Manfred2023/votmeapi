<?php
/*
 *  BuildTrackApi
 *
 *  Created by Manfred MOUKATE on 1/7/25, 10:15 AM,
 *  Email moukatemanfred@gmail.com
 *  Copyright (c) 2025. All rights reserved.
 *  Last modified 1/7/25, 10:15 AM
 */

class QString
{

    const DIVIDER = '::';

    /**
     * @param string|null $string
     * @return string|null
     */
    static public function _clean(?string $string = null, bool $cleanUTF8 = false): ?string
    {
        if (!is_null($string)) {
            $pattern = [
                '/[áâãªä]/u' => 'a',
                '/[ÁÀÂÃÄ]/u' => 'A',
                '/[ÍÌÎÏ]/u' => 'I',
                '/[íì]/u' => 'i',
                '/[ÉÈÊË]/u' => 'E',
                '/[óòõ]/u' => 'o',
                '/[ÓÒÔÕÖ]/u' => 'O',
                '/[ÚÙÛÜ]/u' => 'U',
                '/Ç/' => 'C',
                '/ñ/' => 'n',
                '/Ñ/' => 'N',
                '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
                '/,/' => ',',
                '/[’‘‹›‚]/u' => ' ', // Literally a single quote
                '/[“”«»„]/u' => ' ', // Double quote
                '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
            ];
            $string = preg_replace(array_keys($pattern), array_values($pattern), $string);

            if (!$cleanUTF8) {
                $pattern = [
                    '/[à]/u' => 'a',
                    '/[îï]/u' => 'i',
                    '/[éèêë]/u' => 'e',
                    '/[ôºö]/u' => 'o',
                    '/[úùûü]/u' => 'u',
                    '/ç/' => 'c'
                ];
                $string = preg_replace(array_keys($pattern), array_values($pattern), $string);
            }

            return $string;
        }
        return null;
    }

    /**
     * @param string|null $string
     * @return string|null
     */
    static public function _get(?string $string = null): ?string
    {
        return strcmp(trim($string), '') !== 0 ? stripslashes(trim($string)) : null;
    }

    /**
     * @param string|null $string
     * @return string|null
     */
    static public function _set(?string $string = null): ?string
    {
        return (is_null($string) || strcmp(trim($string), '') === 0) ? null : addslashes(trim($string));
    }

    /**
     * @param string|null $string
     * @param bool $unspace
     * @return string|null
     */
    static public function _alphaNumOnly(?string $string = null, bool $unspace = false): ?string
    {
        if (!is_null($string)) {
            if ($unspace)
                $string = preg_replace('/\s+/', '', $string);

            return preg_replace('/[^A-Za-z0-9\-\s+]/', '', $string);
        }
        return null;
    }

    /**
     * @param string|null $string
     * @return array|string|string[]|null
     */
    static public function _latin(?string $string = null)
    {
        if (!is_null($string)) {
            $string = trim(preg_replace('/\s+/', ' ', $string));
            return preg_replace('/[^\p{Latin}\d.\' ]/u', '', $string);
        }
        return null;
    }

    /**
     * @param mixed $mixed
     * @return bool
     */
    static public function _isNull(mixed $mixed): bool
    {
        return strcmp(trim($mixed), '') === 0 || is_null(htmlspecialchars(stripslashes(trim($mixed))));
    }

}