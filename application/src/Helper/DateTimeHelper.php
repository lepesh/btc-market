<?php

declare(strict_types=1);

namespace App\Helper;

use DateTime;

class DateTimeHelper
{
    public static function convert(?string $dateString): ?DateTime
    {
        if (empty($dateString)) {
            return null;
        }
        $time = strtotime($dateString);

        return $time ? (new DateTime())->setTimestamp($time) : null;
    }
}
