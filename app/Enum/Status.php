<?php

namespace App\Enum;

enum Status: string
{
    case IN_PROGRESS = 'in-progress';
    case DONE = 'done';
    case TODO = 'to-do';
}
