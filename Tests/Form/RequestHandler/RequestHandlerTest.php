<?php

namespace Elao\Bundle\JsonHttpFormBundle\Tests\Form\RequestHandler;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Elao\Bundle\JsonHttpFormBundle\Form\RequestHandler\JsonHttpFoundationRequestHandler;

class RequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->serverParams = $this->getMock(
            'Symfony\Component\Form\Util\ServerParams',
            ['getNormalizedIniPostMaxSize', 'getContentLength']
        );
        $this->requestHandler = new JsonHttpFoundationRequestHandler($this->serverParams);
        $this->factory        = Forms::createFormFactoryBuilder()->getFormFactory();
    }

    /**
     * Test JSON POST request
     */
    public function testJsonPostRequest()
    {
        $form    = $this->getSampleForm();
        $data    = $this->getSampleData();
        $request = new Request([], [], [], [], [], [
            'REQUEST_METHOD'    => 'POST',
            'HTTP_CONTENT_TYPE' => 'application/json',
        ], json_encode($data));

        $this->requestHandler->handleRequest($form, $request);
        $this->assertEquals($data, $form->getData());
    }

    /**
     * Test Classic POST request
     */
    public function testClassicPostRequest()
    {
        $form    = $this->getSampleForm();
        $data    = $this->getSampleData();
        $request = new Request([], ['rocket' => $data], [], [], [], ['REQUEST_METHOD' => 'POST']);

        $this->requestHandler->handleRequest($form, $request);
        $this->assertEquals($data, $form->getData());
    }

    /**
     * Get sample data
     *
     * @return array
     */
    private function getSampleData()
    {
        return [
            'name'   => "Méliès",
            'colors' => ['pink', 'brown']
        ];
    }

    /**
     * Get sample form
     *
     * @return Form
     */
    private function getSampleForm()
    {
        return $this->factory
            ->createNamed('rocket', 'form', [], [])
            ->add('name', 'text')
            ->add('colors', 'choice', [
                'multiple' => true,
                'choices'  => [
                    'white'  => 'White',
                    'orange' => 'Orange',
                    'blonde' => 'Blonde',
                    'pink'   => 'Pink',
                    'blue'   => 'Blue',
                    'brown'  => 'Brown',
                ]
            ]);
    }
}
