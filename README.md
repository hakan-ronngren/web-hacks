# web-hacks

Various web-related things, some of them written in PHP to work on my WordPress site:

 * A demo of how to make paragraphs appear from a dimmed state when
   scrolled up to the center of the viewport
 * A PHP script that calls OpenAPI to generate a poem and an HTML page
   that demonstrates it
 * Intended as an extension of the poem script: another PHP script that
   calls OpenAPI to create an illustration of a poem. The results I have
   had so far are quite disappointing.
 * A demo of how to remove the theme ad from the footer of a WordPress
   site when there is no setting for it.

My development environment is a Mac where I can create and run containers with docker.

For the OpenAPI labs, you need a file `htdocs/openapi-config.php`:

```php
<?php
define('OPENAPI_KEY', 'your-openai-api-key');
```

The file is in `.gitignore` so it should not inadvertently be added to git. It is also in the `htdocs/.htaccess` file, so even if the web server gets misconfigured and you happen to click on the wrong thing, your key won't just pop up in your face.
