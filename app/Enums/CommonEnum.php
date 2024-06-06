<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CommonEnum extends Enum
{
    const Administrator = 1;
    const User = 2;
    const Operator = 3;

    const Active = 1;
    const Inactive = 2;

    const Inpublish = 0;
    const Publish = 1;

    const No = 0;
    const Yes = 1;
}
