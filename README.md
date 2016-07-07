# SITARIUM

[![StyleCI](https://styleci.io/repos/60374517/shield?style=flat)](https://styleci.io/repos/60374517)
[![Build Status](https://travis-ci.org/sitarium/sitarium.svg)](https://travis-ci.org/sitarium/sitarium)
[![Latest Stable Version](https://poser.pugx.org/sitarium/sitarium/v/stable)](https://packagist.org/packages/sitarium/sitarium)
[![Total Downloads](https://poser.pugx.org/sitarium/sitarium/downloads)](https://packagist.org/packages/sitarium/sitarium)
[![Latest Unstable Version](https://poser.pugx.org/sitarium/sitarium/v/unstable)](https://packagist.org/packages/sitarium/sitarium)
[![License](https://poser.pugx.org/sitarium/sitarium/license)](https://packagist.org/packages/sitarium/sitarium)

Sitarium is a web application to host simple websites and offer an easy to edit for non experts. No need for a complex admin interface or back office. Sitarium is the new What you edit is what you get!

Sitarium targets web designers and web developers that want to go straight to the point. Sitarium accelerates the tasks where your added value is minimum to let you focus on what matters.

## Installing Sitarium platform

Sitarium is built on the beautiful Laravel framework and has only few requirements: 
- PHP 5.5.9+ (PHP 7 recommended)
- A database (MySQL recommended)
- Command line access to the web server to run Artisan commands (even though using WebArtisan package can be a workaround) 

### Step 1: Deploy Sitarium source code

Easy task: just rely on Composer and run the command `composer create-project sitarium/sitarium MySitarium` to download the source code and all the dependencies.

Set the `/MySitarium/public` folder as the Document Root of your webserver (or virtual host).

### Step 2: Set up the database

Provide the database connection details in the `/config/database.php` file (driver, server, login, password, database name...)

Sitarium requires only a few tables to work with. Instead of creating everything manually, you only have to run a simple command from the place you put Sitarium source code.
php artisan migrate

### Step 3: TODO USER MANAGEMENT?

_TODO_

## Deploying a new website on Sitarium

Sitarium can host almost any web template and transform it into a live editable website. Just follow these simple steps.

### Step 1: Deploy your website

Create a new folder for your website in the `/public/websites` directory.

You can also create a symbolic link to the website folder located anywhere.

### Step 2: rename the HTML files to make them compatible with Blade templating system

Sounds complex? Not at all! All you need to do is to rename the `.htm` or `.html` extension by `.blade.php` and that's it!

If you're interested, you can find [more info about Blade templating system](https://laravel.com/docs/5.2/blade)

### Step 3: extract the common parts

You have a header that is repeated on several pages? To allow the updates to be done on all the pages at once, you just need to extract this part in a dedicated file.

Create a file named `_header.blade.php`, insert inside it the common HTML code.

In the files that use the header, replace this common HTML code by a simple tag `<include data-source="header" />` and you're done!

NB: the underscore before the file name for the common code is important to distinguish extracts of code and complete standalone pages.

### Step 4: Add the special css classes

This is the fun part: define where to enable Sitarium powerful features. All you need to do is to add special css classes.

#### `.sitarium_editable`

Applies to blocks of text (`div`, `span`, `p`...) or images (`img`).

Enables the live editing features.

#### `.sitarium_repeatable`

Applies to lists of elements (`div`, `ul`...).

Enables the duplicating and suppressing features.

#### *COMING SOON* `.sitarium_connection_link` and `.sitarium_disconnection_link`

Applies preferably to links (`a`).

Triggers the login or logout forms.

### Step 5: Insert your website in the database

Insert a new line in the _websites_ table corresponding to your domain name. Your DNS only needs to point to Sitarium web server (see your registrar configuration), and you are ready to go! 

## Want to contribute?

Sitarium is a young project that will be happy to receive any help!

Feel free to propose pull requests and bug reports :-)

## License

Sitarium is open-sourced licensed under the [MIT license](http://opensource.org/licenses/MIT).
