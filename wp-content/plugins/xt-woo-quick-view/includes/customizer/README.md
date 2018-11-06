# xtkirki-helpers

Helper scripts for theme authors.

If you're starting a new theme based on [underscores](https://github.com/Automattic/_s), we recommend you start using our fork from [https://github.com/aristath/_s](https://github.com/aristath/_s) as it already includes both of the methods described below, and also has 2 example settings for typography.

## Integrating XTKirki in your themes

If you want to use XTKirki in your themes, it is not recommended to include the plugin files in your theme.
By recommending your users install XTKirki as a plugin they can get bugfixes easier and faster.
A lot of themes use TGMPA to recommend installing plugins, but if you only require XTKirki then  using TGMPA might be an overkill.
In that case, you can use the [include-xtkirki.php](https://github.com/aristath/xtkirki-helpers/blob/master/include-xtkirki.php) file to recommend the installation of XTKirki.

When the user visits the customizer, if they don’t have XTKirki installed they will see a button prompting them to install it.
You can configure the description in that file and make sure you change the textdomain in that file from `textdomain` to the actual textdomain your theme uses.

### Usage:

In your theme's `functions.php` file add the following line, changing `'/inc/include-xtkirki.php'` to the path where the file is located:

```php
require_once get_template_directory() . '/inc/include-xtkirki.php';
```

## Making sure that `output` works when XTKirki is not installed

If you use the `output` argument in your fields, XTKirki will automatically generate the CSS needed for your theme, as well as any Google-Fonts scripts.
In order to make sure that user styles will continue to work even if they uninstall the XTKirki plugin, you can include the [`class-my-theme-xtkirki`](https://github.com/aristath/xtkirki-helpers/blob/master/class-my-theme-xtkirki.php) file.

### Usage:

* Rename the file to use the actual name of your theme.
  Example: `twentysixteen-xtkirki.php`.
* Inside the file, search for `My_Theme_XTKirki` and replace it using your theme-name as a prefix.
  Example: `Twentysixteen_XTKirki`.
* In your theme's `functions.php` file add the following line, changing `'/inc/class-my-theme-xtkirki'` to the path where the file is located:

```php
require_once get_template_directory() . '/inc/class-my-theme-xtkirki.php';
```

Once you do the above, instead of using `XTKirki` to add your config, panels, sections & fields as documented in the [XTKirki Documentation](https://xtkirki.org) examples, you will have to use your own class.

Example:

#### Good:

```php
Twentysixteen_XTKirki::add_config( 'my_theme', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );
```

#### Bad:

```php
XTKirki::add_config( 'my_theme', array(
	'capability'    => 'edit_theme_options',
	'option_type'   => 'theme_mod',
) );
```

The `Twentysixteen_XTKirki` class will act as a proxy to the `XTKirki` class.
If the XTKirki plugin is installed, then it will be used to add your panels, sections, fields etc.
However if the plugin is not installed, the `Twentysixteen_XTKirki` will make sure that all your CSS and google-fonts will still work.
