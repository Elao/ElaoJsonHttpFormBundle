<?php

namespace Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler;

use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Request;

/**
 * A JSON request processor using the {@link Request} class of the HttpFoundation
 * component.
 *
 * @author Thomas Jarrand <thomas.jarrand@gmail.com>
 */
class JsonHttpFoundationRequestHandler extends HttpFoundationRequestHandler
{
    /**
     * @var ServerParams
     */
    private $serverParams;

    /**
     * {@inheritdoc}
     */
    public function __construct(ServerParams $serverParams = null)
    {
        parent::__construct($serverParams);

        $this->serverParams = $serverParams ?: new ServerParams();
    }

    /**
     * {@inheritdoc}
     */
    public function handleRequest(FormInterface $form, $request = null)
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, 'Symfony\Component\HttpFoundation\Request');
        }

        if ($request->getMethod() === 'POST' && $request->getContentType() === 'json') {
            return $this->handleJsonRequest($form, $request);
        } else {
            return parent::handleRequest($form, $request);
        }
    }

    /**
     * Handle Json Request
     *
     * @param FormInterface $form
     * @param Request $request
     */
    protected function handleJsonRequest(FormInterface $form, Request $request)
    {
        if ($this->isContentSizeValid($form)) {
            $data = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $form->submit(null, false);
                $form->addError(new FormError(sprintf(
                    'The given JSON content could not be parsed: %s',
                    json_last_error_msg()
                )));

                return;
            }

            $form->submit($data, 'PATCH' !== $request->getMethod());
        }
    }

    /**
     * Check content size
     *
     * Code from {@link HttpFoundationRequestHandler} max size verification.
     * @author Bernhard Schussek <bschussek@gmail.com>
     *
     * @param FormInterface $form
     *
     * @return boolean
     */
    protected function isContentSizeValid(FormInterface $form)
    {
        $contentLength    = $this->serverParams->getContentLength();
        $maxContentLength = $this->serverParams->getPostMaxSize();

        if (!empty($maxContentLength) && $contentLength > $maxContentLength) {
            // Submit the form, but don't clear the default values
            $form->submit(null, false);

            $form->addError(new FormError(
                $form->getConfig()->getOption('post_max_size_message'),
                null,
                array('{{ max }}' => $this->serverParams->getNormalizedIniPostMaxSize())
            ));

            return false;
        }

        return true;
    }
}
