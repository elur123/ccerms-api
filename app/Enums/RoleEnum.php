<?php

namespace App\Enums;

enum RoleEnum: int
{
    case ADMIN = 1;
    case RESEARCH_COORDINATOR = 2;
    case SUBJECT_TEACHER = 3;
    case ADVISER = 4;
    case PANEL = 5;
    case STUDENT = 6;
    case STATISTICIAN = 7;
}