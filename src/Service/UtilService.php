<?php

namespace App\Service;

use DateTime;
use DateTimeImmutable;
use IntlDateFormatter;

class UtilService
{

    /**
     * @param DateTimeImmutable|DateTime $dateTime
     * @return string
     */
    public static function dateFormatter(DateTimeImmutable|DateTime $dateTime): string
    {
        $formatter = new IntlDateFormatter(
            'de_DE',
            IntlDateFormatter::LONG,
            IntlDateFormatter::LONG,
            'Europe/Berlin'
        );

        $formatter->setPattern("Y-m-d'T'HH:mm:ssxxx");

        return $formatter->format($dateTime);
    }

}
