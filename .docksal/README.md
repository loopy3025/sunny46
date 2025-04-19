# Overview

Configure Docksal with WebNY Core Commands and Settings. This Docksal profile assumes you are using **blt** on a **Drupal 9** website.

# Resources
- [Docksal](https://docs.docksal.io/)
- [Docker](https://docs.docker.com/)
- [Acquia Pipelines](https://docs.acquia.com/pipelines/)
- [Extending BLT](https://docs.acquia.com/blt/extending-blt/)

# Setup Steps

## Delete your existing .docksal folder
- Delete your .docksal folder
- Commit that change to your repo
- Add .docksal to your gitignore file
- Commit the gitignore file to your repo

## Composer changes

### Add the following repository
```
"repositories": {
  "webny/docksal": {
    "type": "git",
    "url": "git@github.com:ny/docksal.git"
  }
}
```

### Require
Add this repo to the require-dev line of your composer file, using the most recent release. If you are using this in a profile, you will need to add it again to the sites that use the profile because composer does not inherit require-dev dependencies from dependencies.

#### Sites with BLT
For PHP 8.0 on BLT, use the 0.x version:
```
"require-dev": {
  "webny/docksal": "^0.1"
}
```
For PHP 8.1 on BLT, use the 1.3 version:
```
"require-dev": {
  "webny/docksal": "1.3"
}
```
For PHP 8.2 with BLT, use the ^1.0 version:
```
"require-dev": {
  "webny/docksal": "^1.0"
}
```
#### Sites without BLT
To install on a site without BLT, use the ^2.0 version:
```
"require-dev": {
  "webny/docksal": "^2.0"
}
```
Note that you are required to have `config:platform:php` set in composer.json for sites that don't use BLT.  Example:
```
    "config": {
        "platform": {
            "php": "8.3"
        }
    }
```
When you run `fin init` from the project root, the docksal.env will update the cli image to use whatever version of PHP you have set in composer.json. It also uses the cli setting in webnysettings.yml to set the cli version in that image. CLI is no longer manually set in docksal.env.

### Update Composer

Run the following commands to update composer.
```
composer update webny/docksal
```

## Acquia Pipelines Settings

You may also have to update your [Pipelines SSH settings](https://github.com/itswebny/docs/blob/master/development/acquia-pipelines.md#pipelines-ssh-setup-for-a-site) in order to use this private repository.

## Execution Methods

Please defer to any installation processes or instructions in a websites’s readme or docs page before running these commands.

### Without BLT

If your site is set up to install without BLT, then you should already have the drupal:setup:local command set in composer.json. Run the following commands to update composer. 

```
composer install
composer drupal:setup:local
```
When you run that command, it will first run `fin init` which updates our docksal settings based on our environment and start docksal, then it will install drupal, import config, and build the theme. You are, of course, welcome to run these commands individually instead.

#### BLT

```
composer install
rm -f docroot/sites/default/settings/local.settings.php
rm -f docroot/sites/default/local.drush.yml
cp .docksal/docksal.local.blt.yml blt/local.blt.yml
fin start
fin blt blt:init:settings
cp $DOCROOT_PATH/sites/default/local.drush.yml $PROJECT_ROOT/local.drush.yml
```
With this, you should be able to sql-sync your database, then run other drush, blt, or other commands. If you *do* decide that want to do a drupal install, then feel free to execute `fin blt drupal:install`

# Ongoing Use
Now that the docksal repo lives in your site and will automatically copy itself into your project root when composer installs dependencies, you won't need to run through *all* of the steps above very often. However, when you first start a *new feature* (I.E. a new Jira story), it is a good idea to start fresh by fetching the main branch of your site from github, creating a new working branch, and running through the "Fin init install" or the "Manual Install" listed in the Execution Methods section above.

As you develop a feature, you should be able to run various fin commands to start, stop, remove, or sync to your container as needed throughout the project. However, if you ever "nuke" your site and remove one or more of your untracked files (like the local.drush.yml file, the local.settings.php file, and/or the local.blt.yml file), then you will be forced to refer to the the install steps described in the previous section either to totally rebuild the container or fix parts of it. 

Real success when working with docksal containers comes when you understand what each of the setup steps actually do, which will help you troubleshoot any issues you may have. You should make yourself familiar with fin commands because simply running through the setup process above may not always produce the results you expect. For example, commands such as `fin restart` and `fin remove` may be necessary in troubleshooting situations. Check out the [Docksal](https://docs.docksal.io/) and [Docker](https://docs.docker.com/) documentation for more information.

# M1 Upgrade

Versions 1.1 and 0.2 of this repo add Mac M1 support. You will need to update docker to a version with M1 support (Mac with Apple silicon) by visiting the Docker installation page for installing on a Mac. [See our documentation on upgrading your mac for more information.](https://github.com/itswebny/docs/blob/master/moving-computers.md)

## Database Settings

Your database settings in your Drupal Local Settings script should be set to the Docksal container, but **this is normally an automated process**. We are detailing the steps here for troubleshooting purposes.

- Your existing version of `docroot/sites/default/settings/local.settings.php` is deleted.
- The `.docksal/docksal.local.blt.yml` file is copied to the `blt/local.blt.yml` file. This provides blt a source of information to use the docksal database. If you were using something other than docksal, the database would be different and the local blt file would have different stuff in it.
- The `blt source:build:settings` command is run which copies the `docroot/sites/default/settings/default.local.settings.php` file from your drupal site to a new `docroot/sites/default/settings/local.settings.php` file, then uses the database info from the local blt file to fill in the variables.

Again, you won't have to worry about these steps most of the time, but every once in a while you'll have to troubleshoot a local development issue and it's good to understand this process. Note that most of the time you get a database/sql error in docksal, it's probably due to an issue with this file not having been generated correctly.

```
/**
 * Database configuration.
 */
$databases = array(
  'default' =>
  array(
    'default' =>
    array(
      'database' => 'default',
      'username' => 'user',
      'password' => 'user',
      'host' => 'db',
      'port' => '3306',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
      'prefix' => '',
    ),
  ),
);
```
