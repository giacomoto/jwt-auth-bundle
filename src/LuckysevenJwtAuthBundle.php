<?php

namespace Luckyseven\Bundle\LuckysevenJwtAuthBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class LuckysevenJwtAuthBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
