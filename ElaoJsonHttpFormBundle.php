<?php

namespace Elao\Bundle\JsonHttpFormBundle;

use Elao\Bundle\JsonHttpFormBundle\DependencyInjection\Compiler\OverrideRequestHandlerCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ElaoJsonHttpFormBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideRequestHandlerCompilerPass());
    }
}
