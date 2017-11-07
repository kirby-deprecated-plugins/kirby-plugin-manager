# Installation

Use one of the alternatives below.

### 1. Kirby CLI

If you are using the [Kirby CLI](https://github.com/getkirby/cli) you can install this plugin by running the following commands in your shell:

```text
$ cd path/to/kirby
$ kirby plugin:install jenstornell/kirby-plugin-manager
```

### 2. Clone or download

1. [Clone](https://github.com/jenstornell/kirby-plugin-manager.git) or [download](https://github.com/jenstornell/kirby-plugin-manager/archive/master.zip)  this repository.
2. Unzip the archive if needed and rename the folder to `__plugin-manager`.

**Make sure that the plugin folder structure looks like this:**

```text
site/plugins/__plugin-manager/
```

### 3. Git Submodule

If you know your way around Git, you can download this plugin as a submodule:

```text
$ cd path/to/kirby
$ git submodule add https://github.com/jenstornell/kirby-plugin-manager site/plugins/__plugin-manager
```