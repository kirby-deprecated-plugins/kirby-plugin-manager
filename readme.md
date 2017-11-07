# Kirby Plugin Manager

![Version 0.2](https://img.shields.io/badge/version-0.2-blue.svg) ![MIT license](https://img.shields.io/badge/license-MIT-green.svg) [![Donate](https://img.shields.io/badge/give-donation-yellow.svg)](https://www.paypal.me/DevoneraAB)

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

## Create a group

To keep it simple, I will use `seo--group` from the example above.

1. Create `seo--group` folder. By default `--group` suffix makes it a group.
1. Move some plugins into `seo--group`.

## Include plugins

**You can...**

- Include single plugins, or single plugins from a group.
- Include a whole group. All the plugins in that group will be included.
- Sort the plugins by the array value order.

The array param `$plugins` contains the default plugins. It will not contain `__plugin-manager` because that's required.

```php
c::set('plugin.manager.include', function($plugins) {
    return [
        'load-first--group/kirby-init-class', // Include a plugin in a group
        'seo--group',                         // Include a whole group
        'kirby-blueprint-reader',             // Include a plugin
    ];
});
```

### Group prefix

For the manager to be aware of that a folder is a group, the suffix `--group` is added at the end of the folder name.

Be aware that if you change it to an empty string, all folders that does not have a matching file will be seens as a group. It means that it thinks that all subfolders will be treated as plugins.

```php
c::set('plugin.manager.suffix', '--group');
```

## Exclude plugins

**You can...**

- Exclude single plugins, or single plugins from a group.
- Exclude a whole group. All the plugins in that group will be excluded.

The array param `$plugins` contains the included plugins. If you use both include and exclude, include will run first.

```php
c::set('plugin.manager.exclude', function($plugins) {
    print_r($plugins);
    return [
        'load-first--group',      // Exclude a whole group
        'kirby-blueprint-reader', // Exclude a plugin
    ];
});
```

We have excluded all, except the `seo--group` so, the result will be:

```php
$result = [
    'seo--group/kirby-seo',
    'seo--group/kirby-sitemap-query',
];
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

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/jenstornell/kirby-plugin-groups/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- [Jens Törnell](https://github.com/jenstornell)