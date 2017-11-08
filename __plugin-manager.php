<?php
namespace PluginManager;
use f;
use str;
use c;

$plugin_manager = new PluginManager();
$plugin_manager->run();

class PluginManager {
    function __construct() {
        $this->root = kirby()->roots()->plugins();
        $this->defaults = $this->defaults();
        $this->callback = c::get('plugin.manager');
    }

    // Run
    function run() {
        $plugins = $this->callback();
        if(empty($plugins)) {
            $plugins = $this->defaults();
        }
        if($this->is_associative($plugins)) {
            $plugins = $this->resolveArray($plugins);
        }
        $plugins = $this->all($plugins);
        $this->runPlugins($plugins);
    }

    // Resolve array
    function resolveArray($array = []) {
        $names = [];
        foreach($array as $name => $path) {
            $names[] = $name;
        }
        return $names;
    }

    // Is associative
    function is_associative($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    // Unset groups
    function unset($plugin, $plugins) {
        $output = $plugins;
        if(is_string($plugin)) {
            $output = $this->unsetSingle($plugin, $plugins);
        } elseif(is_array($plugin)) {
            foreach($plugin as $item) {
                $output = $this->unsetSingle($item, $output);
            }
        }
        return $output;
    }

    // Unset single group
    function unsetSingle($plugin, $plugins) {
        foreach($plugins as $name => $path) {
            if(str::contains($name, $plugin . '/')) {
                unset($plugins[$name]);
            }
        }
        return $plugins;
    }

    // Callback
    function callback() {
        $plugins = [];
        if(is_callable($this->callback)) {
            $plugins = call($this->callback, ['plugins' => $this->all($this->defaults), 'group' => $this]);
        } elseif(is_array($this->callback)) {
            $plugins = $this->callback;
        } elseif(is_string($this->callback)) {
            $plugins[] = $this->callback;
        }
        return $plugins;
    }

    // Run plugins
    function runPlugins($plugins) {
        foreach($plugins as $name => $path) {
            if(f::exists($path)) {
                include_once $path;
            }
        }
    }

    // All plugins from an array
    function all($data) {
        $all = [];
        foreach($data as $name) {
            $dir = $this->root . DS . $name;
            $file = $this->root . DS . $name . '.php';

            if(is_dir($dir)) {
                $filepath = $dir . DS . f::name($name) . '.php';
                if(f::exists($filepath)) {
                    $all[$name] = $filepath;
                } else {
                    if($this->isGroup($name)) {
                        $plugins = $this->getGroups($name) ?? [];
                        foreach($plugins as $plugin) {
                            $group_path = $plugin . DS . f::name($plugin) . '.php';
                            if(f::exists($group_path)) {
                                $all[$name . '/' . f::name($plugin)] = $group_path;
                            }
                        }
                    }
                }
            } elseif(f::exists($file)) {
                $all[$name] = $file;
            }
        }
        return $all;
    }

    // Get groups
    function getGroups($name) {
        $dir = $this->root . DS . $name;
        $glob = glob($dir . DS . '*', GLOB_ONLYDIR|GLOB_NOSORT);
        $plugins = [];
        foreach($glob as $dir) {
            if(file_exists($dir . DS . f::name($dir) . '.php')) {
                $plugins[] = $dir;
            }
        }
        return $plugins;
    }

    // Disable core plugin manager
    function disableCore($plugins) {
        foreach($plugins as $key => $plugin) {
            if(f::extension($plugin) == 'php') {
                $plugins[$key] = f::name($plugin);
            } elseif(f::extension($plugin) !== '') {
                unset($plugins[$key]);
            }

            if(!empty($plugins[$key])) {
                kirby()->plugins[$plugins[$key]] = 'Disabled';
            }
        }
        return $plugins;
    }

    // Defaults
    function defaults() {
        $plugins = array_diff(scandir($this->root), array('.', '..'));
        $plugins = array_values($plugins);
        unset($plugins[0]);
        $plugins = $this->disableCore($plugins);
        return array_values($plugins);
    }

    // Is group
    function isGroup($path) {
        if(str::endsWith($path, c::get('plugin.manager.suffix', '--group'))) return true;
    }
}