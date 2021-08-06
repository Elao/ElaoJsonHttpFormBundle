# Elao JSON HTTP Form Bundle ![](https://img.shields.io/badge/Symfony-5.3-blue.svg)

[![Build Status](https://travis-ci.org/Elao/ElaoJsonHttpFormBundle.svg)](https://travis-ci.org/Elao/ElaoJsonHttpFormBundle)

Adds support of JSON requests for Forms:

Symfony forms will be able to handle both JSON POST/PUT/PATCH/DELETE requests and standard GET/POST requests (as they are by default).

The `JsonHttpFoundationRequestHandler` handles the request: If the request content-type is JSON, it decodes the JSON request content as an array and submits the form with its data.

Otherwise, it lets the default behaviour operate: the `HttpFoundationRequestHandler` will handle the request. So all your non-json form request will be treated just the way they've always been.

## Installation

Require _ElaoJsonHttpFormBundle_:

```shell
composer require elao/json-http-form-bundle
```

## Usage

Given a `Rocket` entity with two attributes: `name` (a string) and `colors` (an array of strings).

The following form and controller are meant to create a new instance of Rocket:

```php
<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

// ...

class RocketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('colors', ChoiceType::class, [
                'multiple' => true,
                'choices'  => [
                    'White'  => 'white',
                    'Orange' => 'orange',
                    'Blonde' => 'blonde',
                    'Pink'   => 'pink',
                    'Blue'   => 'blue',
                    'Brown'  => 'brown',
                ]
            ])
        ;
    }

    // ...
}
```

```php
<?php

namespace AppBundle\Controller;

// ...

class RocketController extends Controller
{
    public function newAction(Request $request)
    {
        $rocket = new Rocket();
        $form   = $this->createForm(new RocketType(), $rocket)->getForm();

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {
            // The $rocket object is now correctly hydrated with the data from the form.
            // Whether the request is a classic GET/POST request or a JSON one.
        }
    }
}
```

The Controller and Form above now accept the following JSON POST request:

```http
POST /rockets HTTP/1.1
Accept: application/json
Content-Type: application/json
Content-Length: 43

{"name":"Melies","colors":["pink","brown"]}
```
It works \o/

## License
-------

MIT


Author Information
------------------

http://www.elao.com/
