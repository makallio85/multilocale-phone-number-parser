<?php

namespace makallio85\MultilocalePhoneNumberParser\Test;

use PHPUnit\Framework\TestCase;
use makallio85\MultilocalePhoneNumberParser\Parser;

/**
 * Class ParserTest
 * @package makallio85\MultilocaleNumberParser\Test
 */
class ParserTest extends TestCase
{
    /**
     * @return array
     */
    public function isInternationalProvider()
    {
        return [
            ['040012345', false],
            ['40012345', false],
            ['358040012345', false],
            ['+358040012345', false],
            ['358400469969', true],
            ['+358400469969', true],
            ['+37256565656', true],
            ['37256565656', true],
            ['372 56565656', true],
            ['+372 56565656', true],
            ['56565656', false],
        ];
    }

    /**
     * Test that phone number is international mobile number
     *
     * @dataProvider isInternationalProvider
     *
     * @param $value
     * @param $assertion
     */
    public function testIsInternationalMobile($value, $assertion)
    {
        $this->assertEquals($assertion, Parser::isInternationalMobile($value));
    }

    /**
     * @return array
     */
    public function isMobileProvider()
    {
        return [
            ['014123456', false],
            ['358014123456', false],
            ['+358014123456', false],
            ['401234567', false],
            ['040123456', true],
            ['358040123456', false],
            ['35840123456', true],
            ['+35840123456', true],
            ['+358 40 123 456', true],
            ['+ 358 40 123 456', true],
            ['041123456', true],
            ['045123456', true],
            ['046123456', true],
            ['050123456', true],
            ['56565656', true],
            ['+372 56565656', true],
            ['+ 372 56 565 656', true],
            [' + 372 56 565 656', true],
            ['37256565656', true],
            ['37263565656', false],
        ];
    }

    /**
     * Test if phone number is mobile
     *
     * @dataProvider isMobileProvider
     *
     * @param $value
     * @param $assertion
     */
    public function testIsMobile($value, $assertion)
    {
        $this->assertEquals($assertion, Parser::isMobile($value));
    }

    /**
     * @return array
     */
    public function getMobileInternationalProvider()
    {
        return [
            ['040123456', 'fin', '35840123456'],
            ['014123456', 'fin', false],
            ['56565656', 'est', '37256565656'],
            ['63565656', 'est', false],
        ];
    }

    /**
     * Test that mobile is returned in its international form
     *
     * @dataProvider getMobileInternationalProvider
     *
     * @param $value
     * @param $country
     * @param $assertion
     */
    public function testGetMobileAsInternational($value, $country, $assertion)
    {
        $this->assertEquals($assertion, Parser::getMobileAsInternational($value, $country));
    }

    /**
     * Test that removing country code from number works
     *
     * @return array
     */
    public function removeCountryCodeProvider()
    {
        return [
            ['050-5555659', false],
            ['+358-050-5555659', '0505555659'],
            ['56565656', false],
            ['37256565656', '56565656'],
            ['+372 56 565 656', '56565656'],
        ];
    }

    /**
     * @dataProvider removeCountryCodeProvider
     *
     * @param $value
     * @param $assertion
     */
    public function testRemoveCountryCode($value, $assertion)
    {
        $this->assertEquals($assertion, Parser::removeCountryCode($value));
    }

    /**
     * @return array
     */
    public function getAsTrimmedProvider()
    {
        return [
            ['123 123', '123123'],
            [
                '123123
            123123',
                '123123123123',
            ],
            ["123\n123", '123123'],
            ['+358 0400-469 969', '3580400469969'],
            ['0400-469 969', '0400469969'],
        ];
    }

    /**
     * Test that trimming method works correctly
     *
     * @dataProvider getAsTrimmedProvider
     *
     * @param $value
     * @param $assertion
     */
    public function testGetAsTrimmed($value, $assertion)
    {
        $this->assertEquals($assertion, Parser::getTrimmed($value));
    }
}
