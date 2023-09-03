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

# Writng Builders
@todo
