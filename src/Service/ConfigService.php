<?php

namespace App\Service;

use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use DateInterval;
use DateTimeImmutable;

class ConfigService
{

    public static function getRateHoursBetweenDates(DateTimeImmutable $from, DateTimeImmutable $to, array $configuredHours): array
    {

        /**
         * Divide the interval in minutes
         */
        $diff = $from->diff($to);
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

        $minutes_array = [];
        for ($i = 0; $i < $minutes; $i++) {
            $minutes_array[] = $from->add(new \DateInterval("PT{$i}M"));
        }

        /**
         * Check each minute if it is in a slot
         */
        $slots = [];
        $unallocated = 0;
        foreach ($minutes_array as $min) {
            $found = false;
            foreach ($configuredHours as $hour) {
                $minuteIsInRateHour = self::_checkBetweenRateHours($hour->getHourFrom(), $hour->getHourTo(), $min);

                if ($minuteIsInRateHour) {
                    $found = true;

                    $id = (string)$hour->getId();

                    if (!array_key_exists($id, $slots)) {
                        $slots[$id] = array(
                            'id' => $id,
                            'minutes' => 0,
                            'category' => $hour->getCategory(),
                            'priceNet' => $hour->getPriceNet(),
                            'priceNetTotal' => 0,
                        );
                    }
                    $slots[$id]['minutes']++;
                    $slots[$id]['priceNetTotal'] = $slots[$id]['priceNet'] / 60 * $slots[$id]['minutes'];
                }
            }
            if (!$found) {
                $unallocated++;
            }
        }
        return array(
            'slots' => $slots,
            'unallocated' => $unallocated
        );
    }

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
        $oneSecond = new DateInterval('PT1S');

        $end = \DateTime::createFromImmutable($timeEnd);
        $end->sub($oneSecond);

        if ($timeStart->format('Y-m-d') !== $end->format('Y-m-d')) {
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
        string $category,
        array  $existing): ConfigRateHours
    {

        try {
            $from = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourFrom . ':00');
            $toMutable = \DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourTo . ':00');
        } catch (\Exception $e) {
            throw new InvalidTimeConfigException('CONFIGSERVICE_INVALID_TIMES');
        }

        $removeSecond = new DateInterval('PT1S');
        $toMutable->sub($removeSecond);

        $to = DateTimeImmutable::createFromMutable($toMutable);

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
        $crh->setCategory($category);

        return $crh;
    }

    /**
     * Check if a given minute is in between DateRange, regardless of Date
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable $to
     * @param DateTimeImmutable $toCheck
     * @return bool
     */
    private static function _checkBetweenRateHours(DateTimeImmutable $from, DateTimeImmutable $to, DateTimeImmutable $toCheck): bool
    {
        $startTime = $from->format("H:i:s");
        $endTime = $to->format("H:i:s");
        $toCheckTime = $toCheck->format("H:i:s");

        if ($toCheckTime >= $startTime && $toCheckTime <= $endTime) {
            return true;
        }
        return false;
    }
}
