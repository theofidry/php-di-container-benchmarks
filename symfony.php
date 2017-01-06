<?php

declare(strict_types = 1);

use DiContainerBenchmarks\Container\Symfony\Resource\CompiledSingletonContainer;
use DiContainerBenchmarks\Container\Symfony\Resource\CompiledSingletonContainer1;
use DiContainerBenchmarks\Container\Symfony\Resource\CompiledSingletonContainer2;
use DiContainerBenchmarks\Fixture\Class100;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

require __DIR__.'/vendor/autoload.php';

//build();

profile('Container #0', CompiledSingletonContainer::class);
profile('Container #1', CompiledSingletonContainer1::class);
profile('Container #2', CompiledSingletonContainer2::class);


function profile(string $title, string $containerClass)
{
    $blackfire = new \Blackfire\Client();

    $config = new \Blackfire\Profile\Configuration();
    $config->setTitle($title);
    $config->setSamples(10000);
    $config->setReference(0);

    $probe = $blackfire->createProbe($config, false);
    for ($i = 1; $i <= $config->getSamples(); $i++) {
        $probe->enable();

        $congtainer = new $containerClass();
        $container->get(Class100::class);

        $probe->close();
    }
    $blackfire->endProbe($probe);
}

function build()
{
    // Build container with prototype services
    $containerBuilder = new ContainerBuilder();

    for ($i = 1; $i <= 100; $i++) {
        $definition = new Definition('DiContainerBenchmarks\Fixture\Class' . $i, []);
        $definition->setShared(false);
        $definition->setAutowired(true);
        $containerBuilder->setDefinition('DiContainerBenchmarks\Fixture\Class' . $i, $definition);
    }

    $containerBuilder->compile();

    $dumper = new PhpDumper($containerBuilder);
    file_put_contents(
        __DIR__."/src/Container/Symfony/Resource/CompiledPrototypeContainer.php",
        $dumper->dump(
            [
                "namespace" => "DiContainerBenchmarks\\Container\\Symfony\\Resource",
                "class" => "CompiledPrototypeContainer"
            ]
        )
    );

    // Build container with singleton services
    $containerBuilder = new ContainerBuilder();

    for ($i = 1; $i <= 100; $i++) {
        $definition = new Definition('DiContainerBenchmarks\Fixture\Class' . $i, []);
        $definition->setShared(true);
        $definition->setAutowired(true);
        $containerBuilder->setDefinition('DiContainerBenchmarks\Fixture\Class' . $i, $definition);
    }

    $containerBuilder->compile();

    $dumper = new PhpDumper($containerBuilder);
    file_put_contents(
        __DIR__."/src/Container/Symfony/Resource/CompiledSingletonContainer.php",
        $dumper->dump(
            [
                "namespace" => "DiContainerBenchmarks\\Container\\Symfony\\Resource",
                "class" => "CompiledSingletonContainer"
            ]
        )
    );
}
