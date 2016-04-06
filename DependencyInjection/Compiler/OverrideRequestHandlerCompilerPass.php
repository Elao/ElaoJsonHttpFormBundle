<?php

namespace Elao\Bundle\JsonHttpFormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRequestHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('form.type_extension.form.request_handler')
            ->setClass('Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler');
    }
}
