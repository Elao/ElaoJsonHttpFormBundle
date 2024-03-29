<?php

declare(strict_types=1);

namespace Elao\Bundle\JsonHttpFormBundle\Tests\Form\RequestHandler;

use Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Request;

class RequestHandlerTest extends TestCase
{
    private JsonHttpFoundationRequestHandler $requestHandler;
    private FormFactoryInterface $factory;

    protected function setUp(): void
    {
        $serverParams = $this->createMock(ServerParams::class);
        $this->requestHandler = new JsonHttpFoundationRequestHandler($serverParams);
        $this->factory = Forms::createFormFactoryBuilder()->getFormFactory();
    }

    public function testJsonPostRequest(): void
    {
        $form = $this->getSampleForm();
        $data = $this->getSampleData();

        $content = json_encode(['rocket' => $data]);
        $server = [
            'REQUEST_METHOD' => 'POST',
            'HTTP_CONTENT_TYPE' => 'application/json',
        ];

        \assert(\is_string($content));

        $request = new Request([], [], [], [], [], $server, $content);

        $this->requestHandler->handleRequest($form, $request);
        $this->assertEquals($data, $form->getData());
    }

    public function testClassicPostRequest(): void
    {
        $form = $this->getSampleForm();
        $data = $this->getSampleData();
        $request = new Request([], ['rocket' => $data], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $this->requestHandler->handleRequest($form, $request);
        $this->assertEquals($data, $form->getData());
    }

    /**
     * @return array<string,string|array<string>>
     */
    private function getSampleData(): array
    {
        return [
            'name' => 'Méliès',
            'colors' => ['brown', 'pink'],
        ];
    }

    private function getSampleForm(): FormInterface
    {
        return $this->factory
            ->createNamed('rocket', FormType::class, [], [])
            ->add('name', TextType::class)
            ->add(
                'colors',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'choices' => [
                        'White' => 'white',
                        'Orange' => 'orange',
                        'Blonde' => 'blonde',
                        'Pink' => 'pink',
                        'Blue' => 'blue',
                        'Brown' => 'brown',
                    ],
                ]
            );
    }
}
