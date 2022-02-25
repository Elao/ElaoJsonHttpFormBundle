<?php

declare(strict_types=1);

namespace Elao\Bundle\JsonHttpFormBundle;

use Elao\Bundle\JsonHttpFormBundle\DependencyInjection\Compiler\OverrideRequestHandlerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ElaoJsonHttpFormBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideRequestHandlerCompilerPass());
    }
}
