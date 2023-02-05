<?php

namespace App\Tests\Service;

use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use App\Service\ConfigService;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class ConfigServiceTest extends TestCase
{

    private array $overlapping = array(
        [
            'new' => array('start' => '11:00', 'end' => '21:00'),
            'existing' => array('start' => '16:00', 'end' => '21:00')
        ],
        [
            'new' => array('start' => '16:00', 'end' => '21:00'),
            'existing' => array('start' => '11:00', 'end' => '21:00')
        ],
        [
            'new' => array('start' => '10:00', 'end' => '20:00'),
            'existing' => array('start' => '05:00', 'end' => '15:00')
        ],
        [
            'new' => array('start' => '20:00', 'end' => '21:00'),
            'existing' => array('start' => '10:00', 'end' => '20:30')
        ],
    );

    private array $nonOverlapping = array(
        [
            'new' => array('start' => '05:00', 'end' => '10:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '20:00', 'end' => '21:00'),
            'existing' => array('start' => '10:00', 'end' => '20:00')
        ],
    );

    private array $invalid = array(
        [
            'new' => array('start' => '10:00', 'end' => '10:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '10:00', 'end' => '09:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '10:00', 'end' => '31:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '31:00', 'end' => '10:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
    );

    private array $invalidSame = array(
        [
            'new' => array('start' => '17:00', 'end' => '18:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
    );

    private array $invalidHours = array(
        [
            'new' => array('start' => '32:00', 'end' => '11:00'),
            'existing' => array('start' => '22:00', 'end' => '23:00')
        ],
        [
            'new' => array('start' => '11:00', 'end' => '32:00'),
            'existing' => array('start' => '22:00', 'end' => '23:00')
        ],
        [
            'new' => array('start' => '31:00', 'end' => '32:00'),
            'existing' => array('start' => '22:00', 'end' => '23:00')
        ],
        [
            'new' => array('start' => 'xxx', 'end' => '12:00'),
            'existing' => array('start' => '22:00', 'end' => '23:00')
        ],
    );

    private array $invalidEnd = array(
        [
            'new' => array('start' => '10:00', 'end' => '10:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '10:00', 'end' => '09:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
        [
            'new' => array('start' => '17:00', 'end' => '10:00'),
            'existing' => array('start' => '15:00', 'end' => '20:00')
        ],
    );

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidOverlapCreationOfRateHourConfigEntity(){
        /**
         * Invalid by Overlap
         */
        foreach ($this->invalidSame as $item) {

            $existing = self::_createRateHoursConfigEntity($item['existing']['start'], $item['existing']['end']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIGSERVICE_OVERLAP_TIMES');
            ConfigService::createNewConfigRateHour(
                $item['new']['start'],
                $item['new']['end'],
                140,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidTimesCreationOfRateHourConfigEntity(){
        /**
         * Invalid by End Time
         */
        foreach ($this->invalidEnd as $item) {

            $existing = self::_createRateHoursConfigEntity($item['existing']['start'], $item['existing']['end']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIGSERVICE_START_SMALLER_END');
            ConfigService::createNewConfigRateHour(
                $item['new']['start'],
                $item['new']['end'],
                140,
                array($existing)
            );
        }
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public function testInvalidTimerangeCreationOfRateHourConfigEntity(){
        /**
         * Invalid by End Time
         */
        foreach ($this->invalidHours as $item) {

            $existing = self::_createRateHoursConfigEntity($item['existing']['start'], $item['existing']['end']);

            $this->expectException(Exception::class);
            $this->expectExceptionMessage('CONFIGSERVICE_INVALID_TIMES');
            ConfigService::createNewConfigRateHour(
                $item['new']['start'],
                $item['new']['end'],
                140,
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

            $existing = self::_createRateHoursConfigEntity($item['existing']['start'], $item['existing']['end']);

            $entry = ConfigService::createNewConfigRateHour(
                $item['new']['start'],
                $item['new']['end'],
                140,
                array($existing)
            );

            $this->assertInstanceOf(ConfigRateHours::class, $entry);
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
                self::_createDTImmutableFromHourString($overlap['new']['start']),
                self::_createDTImmutableFromHourString($overlap['new']['end']),
            ];
            $existing = [
                self::_createDTImmutableFromHourString($overlap['existing']['start']),
                self::_createDTImmutableFromHourString($overlap['existing']['end']),
            ];
            $this->assertTrue(ConfigService::checkOverlappingTimes($new, $existing));
        }

        /**
         * Non-Overlapping Cases
         */
        foreach ($this->nonOverlapping as $nonOverlap) {
            $new = [
                self::_createDTImmutableFromHourString($nonOverlap['new']['start']),
                self::_createDTImmutableFromHourString($nonOverlap['new']['end']),
            ];
            $existing = [
                self::_createDTImmutableFromHourString($nonOverlap['existing']['start']),
                self::_createDTImmutableFromHourString($nonOverlap['existing']['end']),
            ];
            $this->assertFalse(ConfigService::checkOverlappingTimes($new, $existing));
        }

        /**
         * Invalid Cases
         */
        foreach ($this->invalid as $invalid) {
            $new = [
                self::_createDTImmutableFromHourString($invalid['new']['start']),
                self::_createDTImmutableFromHourString($invalid['new']['end']),
            ];
            $existing = [
                self::_createDTImmutableFromHourString($invalid['existing']['start']),
                self::_createDTImmutableFromHourString($invalid['existing']['end']),
            ];

            $this->expectException(Exception::class);
            ConfigService::checkOverlappingTimes($new, $existing);
        }
    }

    private function _createDTImmutableFromHourString(string $hour): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hour . ':00');
    }

    private function _createRateHoursConfigEntity(string $hourFrom, string $hourTo): ConfigRateHours
    {
        $from = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourFrom . ':00');
        $to = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourTo . ':00');
        $entity = new ConfigRateHours();
        $entity->setHourFrom($from);
        $entity->setHourTo($to);

        return $entity;
    }
}
