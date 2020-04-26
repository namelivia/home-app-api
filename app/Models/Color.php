<?php

namespace App\Models;

class Color extends BaseModel
{
    const WHITE = 1;
    const GREY = 2;
    const BLACK = 3;
    const MAGENTA = 4;
    const PINK = 5;
    const RED = 6;
    const BROWN = 7;
    const ORANGE = 8;
    const YELLOW = 9;
    const GREEN = 10;
    const CYAN = 11;
    const BLUE = 12;

    /**
     * Whether or not this model is
     * read-only or can be modified.
     *
     * @var bool
     */
    public static $readOnly = true;
}
