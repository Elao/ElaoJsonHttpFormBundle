<?php

namespace Elao\Bundle\JsonHttpFormBundle\DependencyInjection\Compiler;

use Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideRequestHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container
            ->getDefinition('form.type_extension.form.request_handler')
            ->setClass(JsonHttpFoundationRequestHandler::class);
    }
}
