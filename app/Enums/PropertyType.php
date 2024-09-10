<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PropertyType extends Enum
{
    const MULTISELECT = 'multiselect';
    const SINGLESELECT = 'singleselect';
    const NUMBER = 'number';
    const TEXT = 'text';
    const BOOL = 'bool';
}
