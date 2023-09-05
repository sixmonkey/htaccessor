```
 _      _                                                   
| |__  | |_   __ _   ___   ___   ___  ___  ___   ___   _ __ 
| '_ \ | __| / _` | / __| / __| / _ \/ __|/ __| / _ \ | '__|
| | | || |_ | (_| || (__ | (__ |  __/\__ \\__ \| (_) || |   
|_| |_| \__| \__,_| \___| \___| \___||___/|___/ \___/ |_|   

```
# htaccessor
_Manage your (WordPress) .htaccess files with ease_

## Installation
```bash
composer require sixmonkey/htaccessor
```

## Usage
The htaccessor command line tool will always put all files into your current working directory.

**Make sure to run htaccessor from the root directory of your project.**

### Basic setup
Before you can use htaccessor some basic settings must be configured and stored to your project's root directory.
After the first run of htaccessor's setup command a file called `.htaccessor.json` will be created in your project's root directory.
This file contains all the settings needed to run htaccessor. You might want to commit this file to your project's repository, so that other developers can use htaccessor with the same settings, or to let your `.htaccess` file be created during deployments by your pipeline.

```bash
./vendor/bin/htaccessor setup
```
You will be asked to provide the location of your `.htaccess` file (your public folder). 
This can be either a relative or an absolute path. 
In most cases a relative path related to your project's root directory will be the best choice.

You can use the setup command to change your basic settings at any time.

### Editing an environment

```bash
./vendor/bin/htaccessor edit [?environment]
```

### Writing the .htaccess file for an environment
```bash
./vendor/bin/htaccessor write [environment]
```

### Deleting an environment

```bash
./vendor/bin/htaccessor delete [?environment]
```

## Adding Builders
Please feel free to contribute to htaccessor by adding your own builders.
Adding builders is easy!

Just run the following make command:

```bash
php htaccessor make:builder [BuilderName?]
```

This will generate two files for you:
- A builder class in the `app/Builders` directory (e.g. `app/Builders/BuilderName.php`)
- A matching view file in the `resources/views/builders` directory (e.g. `resources/views/builders/builder-name.blade.php`)

### Builder class
The builder class is a simple PHP class that extends the `App\Builders\Base\Builder` class.

#### Basics
Your builder needs to have a human-readable title so you have to implement the `getTitle()` method that returns a meaningful string describing your builder:

```php
public function getTitle(): string
{
    return 'Add htpasswd protection';
}
```

Furthermore your Builder might need ModRewrite to be enabled in your `.htaccess` file.
You can achieve this by setting the `$requiresModRewrite` attribute to `true` in your builder class:

```php
public static bool $requiresModRewrite = true;
```

Sometimes you might also want to achieve that your builder is added at a certain position in the `.htaccess` file.
You can achieve this by setting the `$position` attribute to a string that matches the position you want your builder to be added to:

```php
public static string $position = -999; // I am very certainly the first builder
```

#### Collecting settings for your builder
Your builder might need some settings to work properly.
You can collect these settings by implementing the `configure()` method that returns an array of settings:

```php
public function configure(): array
{
    return [
        'mainDomain' => $this->ask('What is the main domain?', $this->options['mainDomain'] ?? null),
        'protocol' => $this->confirm('Do you want to redirect to https?', true) ? 'https' : 'http',
    ];
}
```
You can use any method available for prompting for input in Laravel's artisan commands to collect your settings.
Please refer to the [Laravel documentation](https://laravel.com/docs/10.x/artisan#prompting-for-input) for more information on how to prompt for user input.

#### Doing something before or after writing the .htaccess file
Sometimes you might want to do something before or after your builder is written to the `.htaccess` file.
You can achieve this by implementing the `beforeWrite()` and `afterWrite()` methods:

##### beforeWrite
This method is called before your builder is written to the `.htaccess` file.
It should return a boolean value indicating whether the builder should be written to the `.htaccess` file or not.
```php
public function beforeWrite(): bool
{
    // Do something before the builder is written to the .htaccess file
    return true;
}
```

##### afterWrite
This method is called after your builder is written to the `.htaccess` file.
The result of the `write()` method is passed to this method as a parameter.
You can modify this result and need return it from this method.
```php
public function afterWrite(string $result): string
{
    // Do something after the builder is rendered and before it is written to the .htaccess file
    return $result;
}
```

### View files for builders
The view files for builders are simple blade templates. 
Please refer to the [Laravel documentation](https://laravel.com/docs/10.x/blade) for more information on how to use blade templates.

These view files will be rendered by htaccessor and the resulting output will be written to your `.htaccess` file.

An example view file for a builder that adds a redirect to a default domain to the `.htaccess` file:

```blade
RewriteCond %{HTTP_HOST} !^{{ $mainDomain }}*
RewriteRule ^(.*)$ {{ $protocol  }}://{{ $mainDomain }}/$1 [R=301,L]
```

All available settings for your builder will be passed to the view file as variables.
