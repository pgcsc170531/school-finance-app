<?php

namespace App\Tuition\Domain\Model;

enum FeeType: string
{
    case TUITION = 'tuition';
    case FACILITY = 'facility';
    case TECHNOLOGY = 'technology';
    case EXAM = 'exam';
    case OTHER = 'other';
}