<?php

namespace App\Tests\Service;

use App\Entity\Price;
use App\Entity\ConfigRateHours;
use App\Entity\ConfigWeekDays;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class ConfigServiceTest extends TestCase
{

    private array $overlapping = array(
        [
            'new' => array('start' => '11:00:00', 'end' => '21:00:00'),
            'existing' => array('start' => '16:00:00', 'end' => '21:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '16:00:00', 'end' => '21:00:00'),
            'existing' => array('start' => '11:00:00', 'end' => '21:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '10:00:00', 'end' => '20:00:00'),
            'existing' => array('start' => '05:00:00', 'end' => '15:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '20:00:00', 'end' => '21:00:00'),
            'existing' => array('start' => '10:00:00', 'end' => '20:30:00', 'weekDay' => 2)
        ],
    );

    private array $overlappingDay = array(
        [
            'new' => array('start' => '05:00:00', 'end' => '16:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 1)
        ],
    );

    private array $nonOverlappingDay = array(
        [
            'new' => array('start' => '05:00:00', 'end' => '16:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
    );

    private array $nonOverlapping = array(
        [
            'new' => array('start' => '05:00:00', 'end' => '10:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '20:00:00', 'end' => '21:00:00'),
            'existing' => array('start' => '10:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ]
    );

    private array $invalid = array(
        [
            'new' => array('start' => '10:00:00', 'end' => '10:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '10:00:00', 'end' => '09:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '10:00:00', 'end' => '31:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '31:00:00', 'end' => '10:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
    );

    private array $invalidSame = array(
        [
            'new' => array('start' => '17:00:00', 'end' => '18:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 1)
        ],
    );

    private array $invalidHours = array(
        [
            'new' => array('start' => '32:00:00', 'end' => '11:00:00'),
            'existing' => array('start' => '22:00:00', 'end' => '23:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '11:00:00', 'end' => '32:00:00'),
            'existing' => array('start' => '22:00:00', 'end' => '23:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '31:00:00', 'end' => '32:00:00'),
            'existing' => array('start' => '22:00:00', 'end' => '23:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => 'xxx', 'end' => '12:00:00'),
            'existing' => array('start' => '22:00:00', 'end' => '23:00:00', 'weekDay' => 2)
        ],
    );

    private array $invalidEnd = array(
        [
            'new' => array('start' => '10:00:00', 'end' => '10:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '10:00:00', 'end' => '09:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '17:00:00', 'end' => '10:00:00'),
            'existing' => array('start' => '15:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
        [
            'new' => array('start' => '20:00:00', 'end' => '21:00:00'),
            'existing' => array('start' => '10:00:00', 'end' => '20:00:00', 'weekDay' => 2)
        ],
    );

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidOverlapCreationOfRateHourConfigEntity()
    {
        /**
         * Invalid by Overlap
         */
        foreach ($this->invalidSame as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);
            $this->expectException(InvalidTimeConfigException::class);
            $this->expectExceptionMessage('CONFIG_OVERLAP_TIMES');
            $cfr = ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidTimesCreationOfRateHourConfigEntity()
    {
        /**
         * Invalid by End Time
         */
        foreach ($this->invalidEnd as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIG_START_SMALLER_END');
            ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidTimerangeCreationOfRateHourConfigEntity()
    {
        /**
         * Invalid by End Time
         */
        foreach ($this->invalidHours as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIG_INVALID_TIMES');
            ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidOverlapsDayEntity()
    {
        /**
         * Valid
         */
        foreach ($this->nonOverlappingDay as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);

            $entry = ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );

            $this->assertInstanceOf(Price::class, $entry);
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testValidOverlapsDayEntity()
    {
        /**
         * Valid
         */
        foreach ($this->overlappingDay as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIG_OVERLAP_TIMES');
            ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testValidCreationOfRateHourConfigEntity()
    {
        /**
         * Valid
         */
        foreach ($this->nonOverlapping as $item) {

            $existing = self::_createConfigPriceEntity($item['existing']['start'], $item['existing']['end'], $item['existing']['weekDay']);

            $entry = ConfigService::createNewPrice(
                $item['new']['start'],
                $item['new']['end'],
                140,
                'P1',
                'Regular Day',
                1,
                array($existing)
            );

            $this->assertInstanceOf(Price::class, $entry);
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testCheckOverlappingTimes()
    {

        /**
         * Overlapping Cases
         */
        foreach ($this->overlapping as $overlap) {
            $new = [
                self::_createDTFromHourString($overlap['new']['start']),
                self::_createDTFromHourString($overlap['new']['end']),
            ];
            $existing = [
                self::_createDTFromHourString($overlap['existing']['start']),
                self::_createDTFromHourString($overlap['existing']['end']),
            ];
            $this->assertTrue(ConfigService::checkOverlappingTimes($new, $existing));
        }

        /**
         * Non-Overlapping Cases
         */
        foreach ($this->nonOverlapping as $nonOverlap) {
            $new = [
                self::_createDTFromHourString($nonOverlap['new']['start']),
                self::_createDTFromHourString($nonOverlap['new']['end']),
            ];
            $existing = [
                self::_createDTFromHourString($nonOverlap['existing']['start']),
                self::_createDTFromHourString($nonOverlap['existing']['end']),
            ];
            $this->assertFalse(ConfigService::checkOverlappingTimes($new, $existing));
        }

        /**
         * Invalid Cases
         */
        foreach ($this->invalid as $invalid) {
            $new = [
                self::_createDTFromHourString($invalid['new']['start']),
                self::_createDTFromHourString($invalid['new']['end']),
            ];
            $existing = [
                self::_createDTFromHourString($invalid['existing']['start']),
                self::_createDTFromHourString($invalid['existing']['end']),
            ];

            $this->expectException(Exception::class);
            ConfigService::checkOverlappingTimes($new, $existing);
        }
    }

    private function _createDTFromHourString(string $hour): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hour);
    }

    private function _createConfigPriceEntity(string $hourFrom, string $hourTo, int $weekDay): Price
    {

        $from = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourFrom);
        $to = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourTo);
        $entity = new Price();
        $entity->setTimeFrom($from);
        $entity->setTimeTo($to);
        $entity->setWeekDay($weekDay);

        return $entity;
    }
}
