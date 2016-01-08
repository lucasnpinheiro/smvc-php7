![Simple MVC Framework](http://simplemvcframework.com/app/templates/publicthemes/smvc/images/logo.png)

#SMVC-PHP7 [Experimental]

SMVC-PHP7 is a un-official fork of Simple MVC Framework for PHP7. This is specifically targetted to support PHP7 features. It is designed with the new features introduced in PHP7. Use this framework cautiously.

##Requirements

 The framework requirements are very limited

 - Apache Web Server or equivalent with mod rewrite support
 - PHP 7 or greater is required

Although a database is not required, if a database is to be used the system is designed to work with a MySQL database. The framework can be changed to work with another database type.

## Installation

1. Download the framework
2. Unzip the package.
3. Upload the framework files to your server. Normally the public/index.php file will be at your root.
4. Open the app/routes.php file with a text editor, setup your routes.
5. Open app/config.example.php and set your base path (if the framework is installed in a folder the base path should reflect the folder path /path/to/folder/ otherwise a single / will do. and database credentials (if a database is needed). Set the default theme. When you are done, rename the file to config.php
6. Edit .htaccess file and save the base path. (if the framework is installed in a folder the base path should reflect the folder path /path/to/folder/ otherwise a single / will do.

## FAQ
Q. Why only PHP7?
A. PHP7 looks more "professional"

Q. Why so early?
A. Just for fun.

Q. Whats new in PHP7?
A. See http://php.net/manual/en/migration70.new-features.php