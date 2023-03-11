<?php

namespace App\Service;

use App\Entity\Price;
use App\Entity\ConfigRateHours;
use App\Exception\InvalidTimeConfigException;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;

class ConfigService
{

    /**
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable $to
     * @param Price[] $configuredPrices
     * @return array
     */
    public static function getRateHoursBetweenDates(DateTimeImmutable $from, DateTimeImmutable $to, array $configuredPrices): array
    {

        /**
         * Divide the interval in minutes
         */
        $diff = $from->diff($to);
        $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

        $minutes_array = [];
        for ($i = 0; $i < $minutes; $i++) {
            $tmpDate = DateTime::createFromImmutable(($from->add(new DateInterval("PT{$i}M"))));
            $minutes_array[] = array(
                'minutes' => $from->add(new DateInterval("PT{$i}M")),
                'weekday' => intval($tmpDate->format('N')),
            );
        }

        /**
         * Check each minute if it is in a slot
         */
        $slots = [];
        $unallocated = 0;
        $totalMinutes = 0;
        $totalPriceNet = 0;
        $usedCategories = [];
        foreach ($minutes_array as $values) {
            $found = false;
            foreach ($configuredPrices as $hour) {
                $minuteIsInRateHour = self::_checkBetweenRateHours($hour->getTimeFrom(), $hour->getTimeTo(), $values['minutes']);

                if ($minuteIsInRateHour && $values['weekday'] === $hour->getWeekDay()) {
                    $found = true;

                    $priceIdByCat = $hour->getCategory();

                    /**
                     * TODO: Possibly sort by startDate
                     */
                    if (!array_key_exists($priceIdByCat, $slots)) {
                        $usedCategories[] = $hour->getCategory();
                        $slots[$priceIdByCat] = array(
                            'id' => (string)$hour->getId(),
                            'minutes' => 0,
                            'category' => $hour->getCategory(),
                            'priceNet' => $hour->getPriceNet(),
                            'priceNetTotal' => 0,
                        );
                    }
                    $slots[$priceIdByCat]['minutes']++;
                    $slots[$priceIdByCat]['priceNetTotal'] = $slots[$priceIdByCat]['priceNet'] / 60 * $slots[$priceIdByCat]['minutes'];
                }
            }
            if (!$found) {
                $unallocated++;
            }
        }


        ksort($slots);
        sort($usedCategories);
        $slotArray = [];

        foreach ($slots as $slot) {
            $slotArray[] = $slot;
            $totalMinutes += $slot['minutes'];
            $totalPriceNet += $slot['priceNetTotal'];
        }

        return array(
            'slots' => $slotArray,
            'unallocated' => $unallocated,
            'categories' => $usedCategories,
            'total' => array(
                'minutes' => $totalMinutes,
                'priceNet' => $totalPriceNet,
            )
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
            throw new InvalidTimeConfigException('CONFIG_INVALID_TIMES');
        }

        if (!self::_isSmallerStart($new_start, $new_end)) {
            throw new InvalidTimeConfigException('CONFIG_START_SMALLER_END');
        }

        return
            ($new_start >= $existing_start && $new_start < $existing_end) ||
            ($new_end > $existing_start && $new_end <= $existing_end) ||
            ($new_start <= $existing_start && $new_end >= $existing_end);
    }

    /**
     * @param DateTime $timeStart
     * @param DateTime $timeEnd
     * @return bool
     */
    private static function _isSmallerStart(DateTime $timeStart, DateTime $timeEnd): bool
    {
        $endCheck = clone $timeEnd;

        if ($timeEnd->format('H:i:s') === '23:59:59') {
            $oneSecond = new DateInterval('PT1S');
            $oneDay = new DateInterval('P1D');
            $endCheck->add($oneSecond);
            $endCheck->add($oneDay);
        }

        if ($endCheck <= $timeStart) {
            return false;
        }

        return true;
    }

    /**
     * @param DateTime $timeStart
     * @param DateTime $timeEnd
     * @return bool
     */
    private static function _isValidTimeRange(DateTime $timeStart, DateTime $timeEnd): bool
    {

        $end = clone $timeEnd;

        if ($timeEnd->format('H:i:s') === '23:59:59') {
            $oneSecond = new DateInterval('PT1S');
            $end->add($oneSecond);
        }

        if ($timeStart->format('Y-m-d') !== $end->format('Y-m-d')) {
            return false;
        }
        return true;
    }

    /**
     * @param string $timeFrom
     * @param string $timeTo
     * @param float $priceNet
     * @param string $category
     * @param string $name
     * @param int $weekDay
     * @param Price[] $existing
     * @return Price
     * @throws InvalidTimeConfigException
     */
    public static function createNewPrice(
        string $timeFrom,
        string $timeTo,
        float  $priceNet,
        string $category,
        string $name,
        int    $weekDay,
        array  $existing
    ): Price
    {

        try {
            $from = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $timeFrom);
            $to = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $timeTo);
        } catch (Exception $e) {
            throw new InvalidTimeConfigException('CONFIG_INVALID_TIMES');
        }

        $removeSecond = new DateInterval('PT1S');
        $to->sub($removeSecond);

        $overlaps = false;
        foreach ($existing as $price) {
            $isOverlapping = self::checkOverlappingTimes(
                [$from, $to],
                [$price->getTimeFrom(), $price->getTimeTo()]
            );

            if ($isOverlapping && $price->getWeekDay() === $weekDay) {
                $overlaps = true;
                break;
            }
        }

        if ($overlaps) {
            throw new InvalidTimeConfigException('CONFIG_OVERLAP_TIMES');
        }

        $price = new Price();
        $price->setPriceNet($priceNet);
        $price->setName($name);
        $price->setCategory($category);
        $price->setTimeFrom(DateTimeImmutable::createFromMutable($from));
        $price->setTimeTo(DateTimeImmutable::createFromMutable($to));
        $price->setWeekDay($weekDay);

        return $price;
    }

    /**
     * @throws InvalidTimeConfigException
     * @deprecated
     */
    public static function createNewConfigRateHour(
        string $hourFrom,
        string $hourTo,
        float  $priceNet,
        string $category,
        array  $daysOfWeek,
        array  $existing,
    ): ConfigRateHours
    {

        try {
            $from = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourFrom . ':00');
            $toMutable = DateTime::createFromFormat('Y-m-d H:i:s', '1970-01-01 ' . $hourTo . ':00');
        } catch (Exception $e) {
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
                foreach ($config->getConfigWeekDays() as $configWeekDay) {
                    if (in_array($configWeekDay->getDayOfWeek(), $daysOfWeek)) {
                        $overlaps = true;
                        break 2;
                    }
                }
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
     * @param DateTimeInterface $from
     * @param DateTimeInterface $to
     * @param DateTimeInterface $toCheck
     * @return bool
     */
    private static function _checkBetweenRateHours(DateTimeInterface $from, DateTimeInterface $to, DateTimeInterface $toCheck): bool
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
