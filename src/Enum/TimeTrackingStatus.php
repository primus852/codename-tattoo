<?php

namespace App\Enum;

enum TimeTrackingStatus: string
{
    case Open = "OPEN";
    case None = "NONE";
    case NoneShow = "NONE_SHOW";
    case Finished = "FINISHED";
}
