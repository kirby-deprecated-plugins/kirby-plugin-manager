# Kirby Plugin Groups

*Version 0.1*

If you have  a lot of plugins, it can be useful to order them by groups.

**Example**

```text
kirby-blueprint-reader
kirby-group-seo
├─kirby-keyword-map
├─kirby-seo
└─kirby-sitemap-query
kirby-scheduled-pages
```

*Kirby Plugin Groups is not a normal plugin, so you can't install it. Just follow the setup below.*

## Setup

### 1. Add code to `site.php`

1. If you don't already have a `site.php` in your main directory of your site (next to the index.php), create it.
1. Add the code below to your `site.php` file.

```php
<?php
$kirby = kirby();

function loadPluginGroups($dir) {
    foreach(glob($dir . DS . '*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $file = $dir . DS . basename($dir) . ".php";
        if(file_exists($file))
            require_once $file;
    }
}
```

If it looks too messy, you can always include the function as a file like below.

```php
require_once __DIR__ . DS . 'site-plugin-groups.php';
```

## Usage

### Normal structure

A normal plugin structure looks like like below.

```text
kirby-blueprint-reader
kirby-keyword-map
kirby-scheduled-pages
kirby-seo
kirby-sitemap-query
```

### Plugin group structure

With Kirby Plugin Groups, you can arrange them in plugin groups, like below.

```text
kirby-blueprint-reader
kirby-group-seo
├─kirby-keyword-map
├─kirby-seo
└─kirby-sitemap-query
kirby-scheduled-pages
```

### Create a group

To keep it simple, I will follow the example above.

1. Create `kirby-group-seo` folder.
1. Create `kirby-group-seo/kirby-group-seo.php` file.
1. Inside `kirby-group-seo/kirby-group-seo.php` add `<?php loadPluginGroups(__DIR__);`.

The group can be called anything, but try to prevent collisions with other folders names. The filename must match the folder name.

## Troubleshooting

If the you have a plugin inside a plugin group, that includes files with `roots`, it will not work.

```php
include kirby()->roots()->plugins() . DS . 'plugin-name' . DS . 'subfolder';
```

If the plugin instead include the files relative to the current folder, it will work just fine.

```php
include __DIR__ . DS . 'plugin-name' . DS . 'subfolder';
```

## Changelog

**0.1**

- Initial release

## Requirements

- [**Kirby**](https://getkirby.com/) 2.5+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/username/plugin-name/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- [Jens Törnell](https://github.com/jenstornell)