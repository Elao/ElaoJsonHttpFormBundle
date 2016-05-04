Elao JSON HTTP Form Bundle ![](https://img.shields.io/badge/Symfony-2.5-blue.svg)
==========================

[![Build Status](https://travis-ci.org/Elao/ElaoJsonHttpFormBundle.svg)](https://travis-ci.org/Elao/ElaoJsonHttpFormBundle)

Adds support of JSON requests for Forms:

Symfony forms will be able to handle both JSON POST/PUT/PATCH/DELETE requests and standard GET/POST requests (as they are by default).

The `JsonHttpFoundationRequestHandler` handles the request: If the request content-type is JSON, it decodes the JSON request content as an array and submits the form with its data.

Otherwise, it lets the default behaviour operate: the `HttpFoundationRequestHandler` will handle the request. So all your non-json form request will be treated just the way they've always been.

Installation:
-------------

Add _ElaoJsonHttpFormBundle_ to your composer.json:

```bash
$ php composer.phar require elao/json-http-form-bundle
```

Register the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Elao\Bundle\JsonHttpFormBundle\ElaoJsonHttpFormBundle(),
    );
}
```

That's it. You're good. Get some well deserved rest.

Usage:
---------

Given a `Rocket` entity with two attributes: `name` (a string) and `colors` (an array of strings).

The following form and controller are meant to create a new instance of Rocket:

```php
<?php

// ...

class RocketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ])
        ;
    }

    // ...
}
```

```php
<?php

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
\o/

License
-------

MIT


Author Information
------------------

http://www.elao.com/
