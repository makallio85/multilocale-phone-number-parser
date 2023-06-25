<?php

namespace makallio85\MultilocalePhoneNumberParser;

/**
 * Class Parser
 *
 * Provides tools to validate and manipulate phone numbers within multiple locales
 *
 * @package makallio85\MultilocalePhoneNumberParser
 */
class Parser
{
    /**
     * Map holds data for countries
     *
     * @var array
     */
    private static $map = [
        'fin' => [
            'countryCode'  => 358,
            'mobileBegins' => ['040', '041', '044', '045', '046', '050'],
        ],
        'est' => [
            'countryCode'  => 372,
            'mobileBegins' => ['50', '51', '52', '53', '54', '55', '56'],
        ],
    ];

    /**
     * Iterate over map and check if number is mobile. Number should also have country code in order to pass
     * If country is given, check only against it
     *
     * @param      $number
     * @param null $country
     *
     * @return bool
     */
    public static function isInternationalMobile($number, $country = null)
    {
        $number = self::getTrimmed($number);
        $number = ltrim($number, '+');

        $map = empty($country) ? self::$map : [$country => self::$map[$country]];

        foreach ($map as $country => $data) {
            if (preg_match('/^' . $data['countryCode'] . '/', $number)) {
                $localPart = ltrim($number, $data['countryCode']);
                if (preg_match('/^0/', $localPart)) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Check against map if number is mobile number
     *
     * @param $number
     *
     * @return bool
     */
    public static function isMobile($number)
    {
        $number = self::getTrimmed($number);

        if (self::isInternationalMobile($number)) {
            $number = self::removeCountryCode($number);
        }

        foreach (self::$map as $country => $data) {
            foreach ($data['mobileBegins'] as $mobileBegin) {
                if (preg_match('/^' . $mobileBegin . '/', $number)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Removes country code from number, if present
     *
     * @param $number
     *
     * @return bool|null|string|string[]
     */
    public static function removeCountryCode($number)
    {
        $number = self::getTrimmed($number);
        $number = ltrim($number, '+');

        $countryCode = substr($number, 0, 3);

        foreach (self::$map as $country => $data) {
            if ((int)$countryCode === $data['countryCode'] && (int)$countryCode === 358) {
                $number = substr($number, 3);
                if ($number[0] !== '0') {
                    $number = '0' . $number;
                }

                return $number;
            }

            if ((int)$countryCode === $data['countryCode'] && (int)$countryCode === 372) {
                return substr($number, 3);
            }
        }

        return false;
    }

    /**
     * Get mobile number in its international form
     *
     * @param $number
     * @param $country
     *
     * @return bool|null|string|string[]
     */
    public static function getMobileAsInternational($number, $country)
    {
        $number = self::getTrimmed($number);

        if (! self::isMobile($number)) {
            return false;
        }

        if (self::isInternationalMobile($number)) {
            return $number;
        }

        $number = ltrim($number, '0');

        return self::$map[$country]['countryCode'] . $number;
    }

    /**
     * Basically just remove all whitespace characters
     *
     * @param $number
     *
     * @return null|string|string[]
     */
    public static function getTrimmed($number)
    {
        $onlyNumbers = preg_replace('/[^0-9]/', '', $number);

        return preg_replace('/\s+/', '', $onlyNumbers);
    }
}
