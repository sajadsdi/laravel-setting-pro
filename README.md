![Laravel Setting Pro](https://sajadsdi.github.io/images/laravel-setting-pro.jpg)

# Laravel Setting Pro

Effortless Management of Laravel Application Settings.

## Description

A Laravel package that provides a simple and effective way to handle application settings with support for persistent
storage using both file-based and database drivers.

## Features

The Laravel Setting Pro package simplifies application settings management in Laravel and provides these key features:

- **Flexible Storage**: Choose between file-based or database storage to suit your application's needs.
 
- **Flexible Database**: Choose between `mysql` or `mongodb` or create own db connection for settings.

- **Caching**: Improve performance with automatic caching of settings.

- **Queue Support**: Handle setting updates in the background, ensuring smooth user experiences.

- **Event Triggers**: Respond to setting changes in real-time by leveraging Laravel events.

- **Global Helper Function & Facade**: Access and manipulate settings anywhere in your app with a simple `setting` function or `Setting` facade.

- **Artisan Commands for Ease of Use**: Publish configuration and migrations effortlessly with the `setting:publish`
  artisan command; complete installations, migrations, and initial tests using the `setting:install` command.

- **Easy get & set and delete settings with dot notation**: `get` & `set` & `delete` and `has` operations on nested settings keys.

- **Easy import settings** : Import settings from a driver to default store driver.

- **Auto create setting** : you can use default value for get operation or set operation on any new setting name.

This package is designed for developers looking for an efficient and intuitive way to handle application settings. It
offers the necessary tools while keeping the process simple and maintainable.

## Requirements

- PHP ^8.1
- Laravel ^9.0|^10.0

## Installation

Use Composer to install the package:

```bash
composer require sajadsdi/laravel-setting-pro
```

## Setup

After the package installation, use the provided Artisan commands to publish configuration and migration files, and
complete the installation process:

First, publish the configuration and migration files using:

```bash
php artisan setting:publish
````

This will publish `_setting.php` to your `config` folder and migration file.

Then, use the installation command to perform migrations and any necessary setup operations:

```bash
php artisan setting:install
```

These commands will set up your settings table in the database and ensure that the package is ready to use.

## Usage

Two ways to use Laravel Setting Pro:

1. Using the `setting` function:

```php
// Get a setting value
$value = setting('my_setting')->get('key', 'default value');
//or 
$value = setting('my_setting','key', 'default value');
//or
$value = setting()->select('my_setting')->get('key', 'default value');


// Set a setting value
setting('my_setting')->set(['key' => 'value']);
//or
setting()->select('my_setting')->set(['key' => 'value']);


//delete a key from setting
setting('my_setting')->delete('key');
//or
setting()->select('my_setting')->delete('key');

```

2. Using the `Setting` facade:

```php
<?php
use Sajadsdi\LaravelSettingPro\Support\Setting;

// Get a setting value
$value = Setting::select('my_setting')->get('key', 'default value');
//or
$value = Setting::my_setting()->get('key', 'default value');
//or
$value = Setting::my_setting('key', 'default value');

// Set a setting value
Setting::select('my_setting')->set('key', 'value');
//or
Setting::my_setting()->set('key', 'value');

//delete key from setting
Setting::select('my_setting')->delete('key');
//or
Setting::my_setting()->delete('key');

//checking exists by has method
if(Setting::select('my_setting')->has('key')){
    echo "key exists!";
}else{
    echo "key not exists!";
}

```

## Advanced Usage
you can use `set` and `get` operation in any way as stated above, with dot notation and multiple keys and defaults like this:

```php
//get operation

$value = Setting::my_setting(['users.3.profile.pic','users.3.profile.name'], ["default.png","No name"]);
//or multi keys and single defaults
$value = setting('my_setting')->get(['users.3.profile.pic','users.3.profile.name'], ["no data"]);


//set operation 
setting::select('my_setting')->set(['users.3.profile.pic' => "profile.png",'users.3.profile.name' => "john"])

//delete multiple keys
setting::select('my_setting')->delete(['users.3.profile.pic','users.3.profile.name']);

//multiple keys checking exists by has method
if(Setting::select('my_setting')->has(['users.3.profile.pic','users.3.profile.name'])){
    echo "The keys are exists!";
}else{
    echo "The keys do not exist!";
}


// it's very Easy
```
This package use `Dot Notation Array` package to getting keys and setting operations you can see [Documentation](https://github.com/sajadsdi/array-dot-notation) to better use for Laravel Setting Pro

## Caching

You can set config to enable setting cache for optimal performance. To clear the settings cache, use:

```bash
php artisan cache:clear
```

## Configuration
You can change `_setting` config on laravel config path.

For more details about Configuration Laravel Setting Pro , [click here](CONFIGURATION.md)

### Contributing

We welcome contributions from the community to improve and extend this library. If you'd like to contribute, please follow these steps:

1. Fork the repository on GitHub.
2. Clone your fork locally.
3. Create a new branch for your feature or bug fix.
4. Make your changes and commit them with clear, concise commit messages.
5. Push your changes to your fork on GitHub.
6. Submit a pull request to the main repository.

### Reporting Bugs and Security Issues

If you discover any security vulnerabilities or bugs in this project, please let us know through the following channels:

- **GitHub Issues**: You can [open an issue](https://github.com/sajadsdi/laravel-setting-pro/issues) on our GitHub repository to report bugs or security concerns. Please provide as much detail as possible, including steps to reproduce the issue.

- **Contact**: For sensitive security-related issues, you can contact us directly through the following contact channels

### Contact

If you have any questions, suggestions, financial, or if you'd like to contribute to this project, please feel free to contact the maintainer:

- Email: thunder11like@gmail.com

We appreciate your feedback, support, and any financial contributions that help us maintain and improve this project.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

Made with ❤️ by [SajaD SaeeDi](mailto:thunder11like@gmail.com).
