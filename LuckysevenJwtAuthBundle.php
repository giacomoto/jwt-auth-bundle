<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LuckysevenJwtAuthBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
