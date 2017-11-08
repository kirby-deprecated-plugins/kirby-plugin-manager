# Kirby Plugin Manager

![Version 0.3](https://img.shields.io/badge/version-0.3-blue.svg) ![MIT license](https://img.shields.io/badge/license-MIT-green.svg) [![Donate](https://img.shields.io/badge/give-donation-yellow.svg)](https://www.paypal.me/DevoneraAB)

Include, exclude, sort and use groups for your plugins.

**Plugin folder example**

```text
__plugin-manager
kirby-blueprint-reader
load-first--group
  kirby-dependencies
  kirby-init-class
seo--group
  kirby-seo
  kirby-sitemap-query
```

**[Installation instructions](docs/install.md)** - Make sure the plugin folder name is `__plugin-manager`.

## Create a group

To keep it simple, I will use `seo--group` from the example above.

1. Create `seo--group` folder. By default `--group` suffix makes it a group.
1. Move some plugins into `seo--group`.

### Group prefix

For the manager to be aware of that a folder is a group, the suffix `--group` is added at the end of the folder name.

Be aware that if you change it to an empty string, all folders that does not have a matching file will be seen as a group. It means that all subfolders will be treated as plugins.

```php
c::set('plugin.manager.suffix', '--group');
```

## Filter and sort plugins

### Include as string or array

The most simple way to include a plugin is with a `string`. The below will include all plugins from the `seo--group`.

```php
c::set('plugin.manager', 'seo--group');
```

You can include and sort the plugins as an array.

```php
c::set('plugin.manager', [
    'load-first--group/kirby-init-class',
    'seo--group',
    'kirby-blueprint-reader',
]);
```

### Include callback

The array param `$plugins` contains the default plugins. The plugins will be sorted in the order you place them.

You can return the plugins as key/value pairs or like here, just as values. `$plugins` has a key that is the name and a value that is the path to the plugin file.

```php
c::set('plugin.manager', function($plugins) {
    return [
        'load-first--group/kirby-init-class',
        'seo--group',
        'kirby-blueprint-reader',
    ];
});
```

### Exclude plugin(s)

[PHP unset](http://php.net/manual/en/function.unset.php) supports multiple variables as well.

```php
c::set('plugin.manager', function($plugins) {
    unset($plugins['seo--group/kirby-seo']);

    return $plugins;
});
```

### Exclude group(s)

To exclude a group, we need to use `$group`. We can exclude a single plugin with a `string`, or multiple groups with an `array`.

```php
c::set('plugin.manager', function($plugins, $group) {
    $plugins = $group->unset('seo--group', $plugins);

    return $plugins;
});
```

## Troubleshooting

### Includes

If you have a plugin inside a group that include one or more files with [roots](https://getkirby.com/docs/cheatsheet#roots), it will not work.

```php
include kirby()->roots()->plugins() . DS . 'plugin-name' . DS . 'subfolder';
```

If you have a plugin inside a group that include the files with a relative path, it should work just fine.

```php
include __DIR__ . DS . 'plugin-name' . DS . 'subfolder';
```

### Dependencies

In very rare cases, a plugin can be dependent on another plugin. If you are using include and groups, make sure that the plugins are loaded in the correct order.

## Changelog

**0.3**

- Removed option `plugin.manager.include`.
- Removed option `plugin.manager.exclude`.
- Added option `plugin.manager` that replaces include and exclude.
- The plugin is now faster.

**0.2**

- Added `package.json`.
- Include config added with `plugin.manager.include`.
- Exclude config added with `plugin.manager.exclude`.
- Group suffix config added with `plugin.manager.suffix`.
- Changed name from "Kirby Plugin Groups" to "Kirby Plugin Manager".
- Made as a real plugin.
- Complete rewrite.

**0.1**

- Initial release

## Requirements

- [**Kirby**](https://getkirby.com/) 2.5+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/jenstornell/kirby-plugin-manager/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- [Jens Törnell](https://github.com/jenstornell)