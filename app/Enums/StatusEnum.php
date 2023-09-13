<?php

namespace App\Enums;

enum StatusEnum: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case DECLINED = 3;
    case CANCELED = 4;
    case MINOR = 5;
    case MAJOR = 6;
    case REVISED = 7;
    case RESCHED = 8;
    case ONGOING = 9;
    case DONE = 10;

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }
}