# Kirby Plugin Groups

*Version 0.1*

If you have  a lot of plugins, it can be useful to order them by groups.

**Plugin folder example**

```text
_group-init
  kirby-init-class
  kirby-dependencies
group-seo
  kirby-keyword-map
  kirby-seo
  kirby-sitemap-query
kirby-blueprint-reader
kirby-scheduled-pages
```

*Kirby Plugin Groups is technically not a plugin, so you can't install it. Just follow the setup below.*

## 1. Setup

1. If you don't already have a `site.php` in your main directory of your site (next to the index.php), create it.
1. Add the code below to your `site.php` file.

```php
<?php
$kirby = kirby();

function loadPluginGroups($dir) {
    foreach(glob($dir . DS . '*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $file = $dir . DS . basename($dir) . ".php";
        if(file_exists($file)) {
            $kirby = kirby();
            require_once $file;
        }
    }
}
```

If it looks too messy, you can include the function as a file like below.

```php
require_once __DIR__ . DS . 'site-plugin-groups.php';
```

## 2. Create a group

To keep it simple, I will use `group-seo` from the example below.

1. Create `group-seo` folder.
1. Create `group-seo.php` inside the group-seo folder. The filename should match the folder name.
1. Add `<?php loadPluginGroups(__DIR__);` inside the group-seo.php file.

The group doesn't need to be prefixed with `group-`. It can be called anything.

## 3. Disable a group

To disable a whole group of plugins, just rename the group folder. Then it will no longer match the file inside and no longer run.

## Example

In this example we have created two groups, `_group-init` and `group-seo`.

**Plugins folder**

```text
_group-init
  kirby-init-class
  kirby-dependencies
group-seo
  kirby-keyword-map
  kirby-seo
  kirby-sitemap-query
kirby-blueprint-reader
kirby-scheduled-pages
```

### Init plugin group

In the plugin group `_group-init`, we group plugins that needs to run early. Maybe the `kirby-init-class` is required by other plugins and needs to run first.

### Bundle plugin group

In the plugin group `group-seo`, we group plugins by their type. In this case we group all the SEO plugins together. If you have many plugins, it can be helpful with groups to keep a organized folder structure.

## Troubleshooting

If you have a plugin inside a group that include one or more files with [roots](https://getkirby.com/docs/cheatsheet#roots), it will not work.

```php
include kirby()->roots()->plugins() . DS . 'plugin-name' . DS . 'subfolder';
```

If you have a plugin inside a group that include the files with a relative path, it should work just fine.

```php
include __DIR__ . DS . 'plugin-name' . DS . 'subfolder';
```

## Changelog

**0.1**

- Initial release

## Requirements

- [**Kirby**](https://getkirby.com/) 2.5+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/jenstornell/kirby-plugin-groups/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- [Jens Törnell](https://github.com/jenstornell)