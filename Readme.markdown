# Theme Options

Theme Options Made Easy

# Install

Either install from composer `arcath/theme-options` or place `theme-options.php` in your theme and require it.

# Usage

Create a new ThemeOptions instance

```php
$themeOptions = new Arcath\ThemeOptions('slug');
```

In the `init` action define all your fields e.g.

```php
function slug_custom_fields(){
  global $themeOptions;

  $themeOptions->addThemeOptionCustomControl('logo_light',
    array(
      'type' => 'option',
      'default' => 0
    ),
    array(
      'section' => 'images',
      'label' => __('Light Logo', 'slug'),
      'description' => __('Light Logo used on darker backgrounds', 'glug'),
    ),
    'WP_Customize_Image_Control'
  );

  $themeOptions->addThemeOption('email',
    array(
      'capability' => 'edit_theme_options',
      'type'       => 'option',
      'default'    => 'foo@bar.com',
    ),
    array(
      'label' => 'Email',
      'section' => 'text',
      'description' => __('Your Email')
    )
  );
}

add_action('init', 'slug_custom_fields');
```

In `customize_register` create your sections/panels and apply the theme options.

```php
function slug_apply_custom_fields($wp_customize){
  global $themeOptions;

  $wp_customize->add_panel('global', array(
    'priority' => 1,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Global Options', 'slug'),
    'description'    => __('Global options that affect every page.', 'edit2017'),
  ));

  $wp_customize->add_section(
    'images',
    array(
      'title' => 'Images',
      'description' => 'Images used throughout the Theme',
      'capability' => 'edit_theme_options',
      'panel' => 'global'
    )
  );

  $wp_customize->add_section(
    'text',
    array(
      'title' => 'Text',
      'description' => 'Text Fields',
      'capability' => 'edit_theme_options',
      'panel' => 'global'
    )
  );
}

add_action('customize_register', 'slug_apply_custom_fields');
```

Then in your Theme you can pull any option using:

```php
<?php
global $themeOptions;
echo($themeOptions->themeOption('email'));
?>
```

Theme Options handles returning the default if it has not been set in the post meta etc...
