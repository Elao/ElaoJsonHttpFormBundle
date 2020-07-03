<?php

namespace Elao\Bundle\JsonHttpFormBundle\Tests\Form\RequestHandler;

use Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler;
use Symfony\Component\Form\Extension\Core\Type\{FormType, TextType, ChoiceType};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\HttpFoundation\Request;

class RequestHandlerTest extends TestCase
{
    private $requestHandler;
    private $factory;

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
        $request = new Request(
            [], [], [], [], [], [
            'REQUEST_METHOD' => 'POST',
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], json_encode(['rocket' => $data])
        );

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
