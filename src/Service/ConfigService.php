<?php

namespace App\Service;

use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use DateInterval;
use DateTimeImmutable;

class ConfigService
{

    /**
     * Check if two tuples of times do overlap
     * @param array $new
     * @param array $existing
     * @return bool
     * @throws InvalidTimeConfigException
     */
    public static function checkOverlappingTimes(array $new, array $existing): bool
    {
        $new_start = $new[0];
        $new_end = $new[1];
        $existing_start = $existing[0];
        $existing_end = $existing[1];

        if (!self::_isValidTimeRange($new_start, $new_end)) {
            throw new InvalidTimeConfigException('CONFIGSERVICE_INVALID_TIMES');
        }

        if ($new_end <= $new_start) {
            throw new InvalidTimeConfigException('CONFIGSERVICE_START_SMALLER_END');
        }

        return
            ($new_start >= $existing_start && $new_start < $existing_end) ||
            ($new_end > $existing_start && $new_end <= $existing_end) ||
            ($new_start <= $existing_start && $new_end >= $existing_end);
    }

    /**
     * @param DateTimeImmutable $timeStart
     * @param DateTimeImmutable $timeEnd
     * @return bool
     */
    private static function _isValidTimeRange(DateTimeImmutable $timeStart, DateTimeImmutable $timeEnd): bool
    {
        if ($timeStart->format('Y-m-d') !== $timeEnd->format('Y-m-d')) {
            return false;
        }
        return true;
    }

    /**
     * @throws InvalidTimeConfigException
     */
    public static function createNewConfigRateHour(
        string $hourFrom,
        string $hourTo,
        float  $priceNet,
        array  $existing): ConfigRateHours
    {

        try {
            $from = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourFrom . ':00');
            $to = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourTo . ':00');
        } catch (\Exception $e) {
            throw new InvalidTimeConfigException('CONFIGSERVICE_INVALID_TIMES');
        }

        $removeSecond = new DateInterval('PT1S');
        $to->sub($removeSecond);

        $overlaps = false;

        foreach ($existing as $config) {
            $isOverlapping = self::checkOverlappingTimes(
                [$from, $to],
                [$config->getHourFrom(), $config->getHourTo()]
            );

            if ($isOverlapping) {
                $overlaps = true;
                break;
            }
        }

        if ($overlaps) {
            throw new InvalidTimeConfigException('CONFIGSERVICE_OVERLAP_TIMES');
        }

        $crh = new ConfigRateHours();
        $crh->setHourTo($to);
        $crh->setHourFrom($from);
        $crh->setPriceNet($priceNet);

        return $crh;

    }
}
