<?php

namespace Elao\Bundle\JsonHttpFormBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Elao\Bundle\JsonHttpFormBundle\DependencyInjection\Compiler\OverrideRequestHandlerCompilerPass;

class ElaoJsonHttpFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideRequestHandlerCompilerPass());
    }
}
