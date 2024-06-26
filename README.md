# Noobframework

NoobFramework is an MVC framework designed specifically for beginners. It serves as an educational tool for those learning the [MVC design pattern](https://pt.wikipedia.org/wiki/MVC) in [PHP](https://www.php.net/).

## MVC

MVC stands for [Model-View-Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller), which is a widely-used software design pattern. Its fundamental concept involves dividing an application into three interconnected components:

- Model: Responsible for handling data manipulation, including reading, writing, and validation. 
- View: Represents the user interface layer, responsible solely for presenting data to the user. 
- Controller: Acts as an intermediary between the user and the application's logic. It receives user input, determines which model to interact with, and selects the appropriate view to render. Controllers contain methods known as actions.

## Requirements

* [PHP >= 8.3](https://www.php.net/downloads.php)
* [Composer](https://getcomposer.org)

## Installation

Clone or download this repository and install dependencies:
```bash
composer install
```

Choose one of the following options (Built-in server or Docker), to run the project.

### Built-in server

If you prefer to use PHP's built-in server for local development, navigate to the root of the project and run:

```bash
php -S localhost:8000
```

### Docker

For [Docker](https://www.docker.com/) users, a Dockerfile is provided at the root of the project with the necessary configurations to run the framework. To use Docker:

1. Build the Docker image:
```bash
docker build -t noobframework .
```
2. Run the Docker container:
```bash
docker run -d --name noobframework -p 80:80 -v $(pwd):/var/www/html noobframework
```

## Usage

1. Develop your application within the `app` folder. This folder is organized into Controllers, Models, and Views.
2. The public folder serves as the document root for your application.
3. Generate a `.env` file based on `.env.example` (copy, paste and rename it), then configure your application settings within it.
4. Define your routes in the `route/router.php` file. Routing follows the features of [nikic/fast-route](https://github.com/nikic/FastRoute).
5. To create a controller, add a new file inside `app/Controller`, similar to existing controllers (e.g., indexController.php). Similarly, add your models inside the Models folder and create views following the examples provided in the views folder.
6. Each controller can contain multiple actions (methods) corresponding to different user interactions.

For more, see [documentation](https://edigar.github.io/noobframework-doc).
