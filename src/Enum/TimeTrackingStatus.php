<?php

namespace App\Enum;

enum TimeTrackingStatus: string
{
    case Open = "OPEN";
    case None = "NONE";
    case NoneShow = "NONE_SHOW";
    case Finished = "FINISHED";

//    public static function fromString(string $name): string
//    {
//        foreach (self::cases() as $status) {
//            if ($name === $status->value) {
//                return $status->value;
//            }
//        }
//        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
//    }

}
