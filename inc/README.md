# Making the Web App Yours

The Web Application is customisable, but only for someone who knows PHP because I was way too lazy to make an Admin Dashboard.

### Core Configuration

The file **config.php** after the installation contains all the information regarding Databases and Database Drivers.

**Note** : It would be a good idea to restrict the access of **config.php** as it contains sensitive information that can be used to gain access to your databases.

The Error Reporting has been turned off by default for a better user experience, in order to receive error messages, just remove this line from the top : 

```php
error_reporting(0);
```

### App Configuration

The App Details and what to display on which pages is all stored inside the file **appconfig.php**.

It is well documented with details of where a particular string will be printed and what a loop is for.

Edit the File **ONLY AND ONLY IF** you know PHP. Otherwise, ask someone who knows PHP to do it for you.

Example : 

```php
// The wait time (In seconds) for the app to redirect to a link. Can be extended if someone wishes to run ads on the redirect.

$appwaittime = 0;	// Default set to 0.
```

The above code block specifies the time in seconds a user has to wait before finally being redirected to the link that has been shortened.

### Other configurations

There aren't any more configurations in the directory. Editing any other might break the entire code. So be careful about that. The **lock** file is set to 0 or 1 depending on the install status of the Application. All other files are files that need to be included in other files to work and have no individual significance of their own.

[Back to Main Documentation](https://github.com/deve-sh/Trimmer)