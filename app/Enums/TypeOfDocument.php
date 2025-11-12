<?php

namespace App\Enums;

enum TypeOfDocument:int
{
    case INE = 1;
    case CURP = 2;
    case RFC = 3;
}
