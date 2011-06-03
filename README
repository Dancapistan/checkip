CheckIP is a simple, CakePHP-based webapp that displays the IP address of the current visitor and logs the corresponding user agent to a database.

It is aware of reverse proxies that respect the X-Forwarded-For header, so this application works with Varnish or nginx load balancers, as long as it is configured correctly.

Installation
============

This application uses [CakePHP 1.3](http://cakephp.org/) and has been tested using Apache 2.2 and MySQL 5.1 (though you can use 5.0 if you have existing data with collation issues preventing you from ugrading). (There is an SQL file called `visitors.sql` to give you an idea about the table structure.)

(These instructions are for Ubuntu. Redhat-based systems have things in different places and named different things. For example, `apache2` is often called `httpd` on Redhat systems. Please consult your operating system's documentation for more details.)

Setting Up Apache
-----------------

You will need to install a PHP driver for Apache, like modphp or fastcgi (recommended for production applications). Also you will want to install mod_rewrite so Apache can rewrite URLs to make them pretty for CakePHP.

For an Ubuntu system, that process will look something like this.

    $ sudo apt-get install libapache2-mod-fastcgi
    $ sudo a2enmod fastcgi
    $ sudo a2enmod rewrite
    $ sudo /etc/init.d/apache2 restart

Installing CheckIP
------------------

You will need to install the application in your document root file. Please consult the Apache documentation and your operating system configuration to determine this location, but it is usually near `/var/www/`.

Most likely, you will want to run this application as a subdomain (like `checkip.example.com`), in which case you will need to create a vhost file and restart Apache. In that case, install the application in whatever you set as the `DocumentRoot` value in the vhost configuration file (see the next section).

Setting up a vhost
------------------

Create a cname DNS entry in your zone for `checkip.example.com` to point to `example.com` (assuming that same server is where you're running this app).

Create the vhost file. For Ubuntu, that will probably look like this:

    $ sudo touch /etc/apache2/sites-available/checkip

Edit the vhost file and add the following content:

    <VirtualHost *:80>
      ServerName checkip.example.com
      DocumentRoot /var/www/checkip/app/webroot
    </VirtualHost>
    
Modify the `ServerName` above to match the cname, and modify `DocumentRoot` to match wherever you installed the app. Depending on your needs, you may wish to further customize this conf file. The [Apache docs](http://httpd.apache.org/docs/2.0/vhosts/) are good at listing all the possible values and configuration options.

Enable the site.

    $ sudo a2ensite checkip

Reload or restart Apache

    $ sudo /etc/init.d/apache2 reload

You should be able to navigate to your app now by going to `http://checkip.example.com` in your browser, though you will get a database connection error because we haven't set up the database.

Setting up the database
-----------------------

[Install MySQL](http://dev.mysql.com/doc/refman/5.1/en/linux-installation.html) if it is not already and make sure the server daemon is running:

    $ pgrep mysqld

If the above command returns a number, the daemon is running. If it returns nothing, there is no server.

Which port is the server listening to. This will be needed later on for CakePHP configuration.

    $ grep "^port" /etc/mysql/my.cnf 
    port		= 1234

or, depending on your MySQL user privelages (which are probably root since you're administering this system), you can run the following command:

    $ mysqladmin variables -u youruser -p | grep '| port' | awk '{ print $4 }'
    Enter password:
    1234

Just to make sure everything is working, log in to the server:

    $ mysql -u youruser -p -P 1234
    Enter password: 
    Welcome to the MySQL monitor.
    
    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
    
    mysql> 
    
Looks like it's working. Run the following command to create the database.

    $ echo 'CREATE DATABASE IF NOT EXISTS checkip;' | mysql -u youruser -p -P 1234

(You probably don't really need to modify the collation or character set because all we're going to be doing is keeping track of IP addresses and User Agent strings.)

If you are going to run the unit tests, create a second database called `test_checkip` using the same method as above.

Configuring
===========

You'll want to follow the CakePHP documentation for configuring an application, including modifying `/var/www/checkip/app/config/core.php` to change the security salt and cipherSeed, and to set `/var/www/checkip/app/tmp` to writable by the webserver user.

Also, edit the `/var/www/checkip/app/config/database.php` file, and add your own personal information:

    var $default = array(
    	'driver' => 'mysql',
    	'persistent' => false,
    	'host' => 'localhost',
    	'port' => 1234,
    	'login' => 'youruser',
    	'password' => 'yourpassword',
    	'database' => 'checkip',
    	'prefix' => '',
    );

If you are going to run the unit tests, create a `$test` connection with all of the same information, but change `database` from `checkip` to `test_checkip`.

Running the tests
=================

The CheckIP application comes with some testing using the [SimpleTest PHP framework](http://simpletest.sourceforge.net/). It is already included in the CheckIP application, so you should be able to go to `http://checkip.example.com/test.php` to see a list of tests.

(Note: Don't try to run all the Core Tests by clicking on "All tests"; it will fail due to [a known CakePHP issue](http://book.cakephp.org/view/1197/Preparing-for-testing#Running-Core-test-cases-1199). You can run the tests one by one, however.)

Click on "Test Cases" under "App" to the relevant tests. Then click on "controllers / VisitorsController" to run the tests for this application.

Due to the relatively simple nature of this application, only unit tests are included.

As the application grows in complexity, integration testing using [Selenium](http://seleniumhq.org/), [Watir](http://watir.com/), and [Rack::Test](https://github.com/brynary/rack-test) will probably be added. However, integration tests are slow by nature due to the overhead of launching browsers. So they are omitted by default.

There is a log of visitors in the database, stored in the visitors table. The tests use a Visitor fixture to replicate this table in the test database for testing purposes. Feel free to abuse it as needed.

This application currently ships with one fixture and one controller test suite:

 * `app/tests/fixtures/visitor_fixture.php` 
 * `app/tests/cases/controllers/visitors_controller.test.php`

