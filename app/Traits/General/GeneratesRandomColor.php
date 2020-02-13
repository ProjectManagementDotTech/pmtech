<?php

namespace App\Traits\General;

trait GeneratesRandomColor
{
    //region Static Protected Implementation

    /**
     * Generate a random color in HTML notation (without the '#').
     *
     * @return string
     */
    static protected function generateRandomColor(): string
    {
        return str_pad(dechex(rand(0, 16777215)), 6, '0', STR_PAD_LEFT);
    }

    //endregion
}
