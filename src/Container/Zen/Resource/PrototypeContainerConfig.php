<?php
declare(strict_types=1);

namespace DiContainerBenchmarks\Container\Zen\Resource;

use WoohooLabs\Zen\Config\AbstractContainerConfig;
use WoohooLabs\Zen\Config\EntryPoint\WildcardEntryPoint;
use WoohooLabs\Zen\Config\Hint\WildcardHint;

class PrototypeContainerConfig extends AbstractContainerConfig
{
    protected function getEntryPoints(): array
    {
        return [
            new WildcardEntryPoint(__DIR__."/../../../../src/Fixture")
        ];
    }

    protected function getDefinitionHints(): array
    {
        return [];
    }

    protected function getWildcardHints(): array
    {
        return [
            WildcardHint::prototype(
                __DIR__."/../../../../src/Fixture",
                'DiContainerBenchmarks\Fixture\Class*',
                'DiContainerBenchmarks\Fixture\Class*'
            )
        ];
    }
}
