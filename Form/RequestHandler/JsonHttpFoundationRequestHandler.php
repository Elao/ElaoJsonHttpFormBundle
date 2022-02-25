<?php

declare(strict_types=1);

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
     * Methods that have a body
     *
     * @var array<string>
     */
    private static array $bodyMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    private ServerParams $serverParams;

    public function __construct(ServerParams $serverParams = null)
    {
        parent::__construct($serverParams);

        $this->serverParams = $serverParams ?: new ServerParams();
    }

    /**
     * @param mixed $request Support old versions of RequestHandlerInterface
     */
    public function handleRequest(FormInterface $form, $request = null): void
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, Request::class);
        }

        if (
            'json' === $request->getContentType()
            && \in_array($request->getMethod(), static::$bodyMethods, false)
        ) {
            $this->handleJsonRequest($form, $request);

            return;
        }

        parent::handleRequest($form, $request);
    }

    /**
     * Handle Json Request
     */
    protected function handleJsonRequest(FormInterface $form, Request $request): void
    {
        if ($this->isContentSizeValid($form)) {
            $name = $form->getName();
            $content = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $form->submit(null, false);
                $form->addError(
                    new FormError(
                        sprintf(
                            'The given JSON content could not be parsed: %s',
                            json_last_error_msg()
                        )
                    )
                );

                return;
            }

            if ('' === $name || 'DELETE' === $request->getMethod()) {
                $data = $content;
            } else {
                // Don't submit if the form's name does not exist in the request
                if (!isset($content[$name])) {
                    return;
                }

                $data = $content[$name];
            }

            $form->submit($data, 'PATCH' !== $request->getMethod());
        }
    }

    /**
     * Check content size
     *
     * Code from {@link HttpFoundationRequestHandler} max size verification.
     *
     * @author Bernhard Schussek <bschussek@gmail.com>
     */
    protected function isContentSizeValid(FormInterface $form): bool
    {
        // Mark the form with an error if the uploaded size was too large
        // This is done here and not in FormValidator because $_POST is
        // empty when that error occurs. Hence the form is never submitted.
        $contentLength = $this->serverParams->getContentLength();
        $maxContentLength = $this->serverParams->getPostMaxSize();

        if (null !== $maxContentLength && $contentLength > $maxContentLength) {
            // Submit the form, but don't clear the default values
            $form->submit(null, false);
            $form->addError(
                new FormError(
                    $form->getConfig()->getOption('post_max_size_message'),
                    null,
                    ['{{ max }}' => $this->serverParams->getNormalizedIniPostMaxSize()]
                )
            );

            return false;
        }

        return true;
    }
}
