<?php
namespace PluginManager;
use f;
use str;
use c;

$plugin_manager = new PluginManager();
$plugin_manager->plugins();

class PluginManager {
    function __construct() {
        $this->root = kirby()->roots()->plugins();
    }

    // Plugins
    function plugins() {
        $include = $this->includePlugins($this->appendItems($this->defaultPlugins()));
        $exclude = $this->excludePlugins($include);

        $this->runPlugins($this->appendItems($exclude));
    }

    // Include plugins
    function includePlugins($all) {
        $include = $this->callback(c::get('plugin.manager.include'), $this->resolveArray($all));
        return $this->resolveArray($this->appendItems($include));
    }

    // Exclude plugins
    function excludePlugins($all) {
        $exclude = $this->callback(c::get('plugin.manager.exclude'), $all);

        foreach($all as $key => $name) {
            $root_name = str::split($name, '/');
            $is_group = $this->isGroup($root_name[0]);
            $in_group = in_array($root_name[0], $exclude);
            $in_exclude = in_array($all[$key], $exclude);

            if(($is_group && $in_group) || $in_exclude) {
                unset($all[$key]);
            }
        }
        return $all;
    }

    // Callback to array
    function callback($data, $fallback) {
        if(is_callable($data)) {
			$callback = call($data, ['plugins' => $fallback]);
		}
		if(isset($callback) && is_array($callback)) {
            return $callback;
        } else {
            return $fallback;
        }
    }

    // Exclude fallback
    function resolveArray($array = []) {
        foreach($array as $name => $path) {
            $names[] = $name;
        }
        return $names;
    }

    // Include fallback
    function defaultPlugins() {
        $paths = array_diff(scandir($this->root), array('.', '..'));
        return array_values($paths);
    }

    // Run plugins
    function runPlugins($all) {
        $kirby = kirby();
        foreach($all as $name => $filedir) {
            $kirby->plugins[$name] = $filedir;
            $filedir = str_replace('/', DS, $filedir);
            if(is_dir($filedir)) {
                $filedir .= DS . f::name($name) . '.php';
            }            
            include_once $filedir;
        }
    }

    // Relative path
    function relativePath($path) {
        return str_replace($this->root . DS, '', $path);
    }

    // Path to name
    function name($path) {
        return str_replace(DS, '/', $this->relativePath($path));
    }

    // Append items
    function appendItems($items) {
        $all = [];
        foreach($items as $filedir) {
            $path = $this->root . DS . $filedir;

            if(is_dir($path)) {
                if(file_exists($path . DS . f::name($path) . '.php')) {
                    $all[$this->name($path)] = $path;
                } else {
                    $plugins = $this->appendPluginGroup($path) ?? [];
                    foreach($plugins as $plugin) {
                        $all[$this->name($plugin)] = $plugin;
                    }
                }
            } else if(f::extension($path) == 'php') {
                $all[$this->name($path)] = $path;
            }
        }
        unset($all['__plugin-manager']);
        return $all;
    }

    // Append plugin group
    function appendPluginGroup($path) {
        $group = $this->relativePath($path);
        $paths = [];
        if($this->isGroup($group)) {
            $paths = $this->appendPluginGroupItems($path);
        }
        return $paths;
    }

    // Is group
    function isGroup($path) {
        if(str::endsWith($path, c::get('plugin.manager.suffix', '--group'))) return true;
    }

    // Append plugin groups
    function appendPluginGroupItems($dir) {
        $glob = glob($dir . DS . '*', GLOB_ONLYDIR|GLOB_NOSORT);
        $plugins = [];
        foreach($glob as $dir) {
            if(file_exists($dir . DS . f::name($dir) . ".php")) {
                $plugins[] = $dir;
            }
        }
        return $plugins;
    }
}