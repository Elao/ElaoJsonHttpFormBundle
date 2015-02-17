<?php

namespace Elao\Bundle\JsonHttpFormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler;

/**
 * Form type extension for Json Http Foundation request handler
 */
class FormTypeJsonHttpFoundationExtension extends AbstractTypeExtension
{
    /**
     * @var RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(RequestHandlerInterface $requestHandler = null)
    {
        $this->requestHandler = $requestHandler ?: new JsonHttpFoundationRequestHandler();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setRequestHandler($this->requestHandler);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
