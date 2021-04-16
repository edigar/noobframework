# noobframework

A MVC framework with automated [friendly URL](https://techterms.com/definition/friendly_url) in PHP for beginners. Aimed at anyone learning the [MVC design pattern](https://pt.wikipedia.org/wiki/MVC) in [PHP](https://www.php.net/).

## MVC

Acronym for [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller), it is a software design standard, whose basic principle is the division of the application into three layers: the user interaction layer (view), the data manipulation layer (model) and the layer controller.

A quick explanation of each layer:

* Model: Responsible for reading and writing data, and their validations.
* View: Layer of user interaction. It just displays the data.
* Controller: Responsible for receiving all user requests, controlling which model to use and which view will be shown to the user. Its methods called actions.

![MVC](https://upload.wikimedia.org/wikipedia/commons/a/a0/MVC-Process.svg)

## Requiriments

* PHP >= 7.1
* apache with Rewrite mode enabled

## Installation

Just clone or download this repository. :)

### Docker

If you use [docker](https://www.docker.com/), at the root of the project there is a Dockerfile file with the minimum requirements to run the project.

You can run with docker by building the image and running it.

For example, at the root of the project, execute:

```bash
docker build -t noobframework .
docker run -d --name noobframework -p 80:80 -v $(pwd):/var/www/html noobframework
```

### Built-in server

If you use the built-in PHP server on local development, start it with `router.php` as the input file, to effective route rewrite. Below is an example of the command. Run at the root of the project:

```bash
php -S localhost:8080 router.php
```

## How to use

Develop your app inside the `app` folder, which is divided into Controllers, Models and views folders, and the public folder will be your public-facing document root.

In the config folder you can add your application's settings, such as database and base url. Rename config.php.dist to config.php.

In order to create a controller, inside `app/Controller`, creates a file, with a name ending with Controller, like already existing controller (indexController.php). For example, if you want to create the user controller, create the file userController.php. In this example, the route [your-project]/user comes into existence.

Follow this rule to create a model, inside model folder and to create a view, follow the example that already exists in the view folder.

Each controller can have many actions (access methods).

Your application must respond to the url [your-project]/[controller-name]/[action-name]. If your action is called index, there is no need to type in the url.

For more, see [documentation](https://edigar.github.io/noobframework).
