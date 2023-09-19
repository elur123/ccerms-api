<?php

namespace App\Enums;

enum SectionTypeEnum: int
{
    case FIRST_SEMESTER_FIRST_TERM = 1;
    case FIRST_SEMESTER_SECOND_TERM = 2;
    case SECOND_SEMESTER_FIRST_TERM = 1;
    case SECOND_SEMESTER_SECOND_TERM = 2;
    case SUMMER = 5;
}   