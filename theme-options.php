<?php

namespace Arcath;

class ThemeOptions {
  public $themeSlug;
  private $themeOptions;

  // Sets the theme slug on creation of the library
  public function __construct($themeSlug = null){
    $this->themeSlug = $themeSlug;
    $this->themeOptions = array();

    add_action('customize_register', array($this, 'applyThemeOptions'));
  }

  public function themeOption($name){
    $options = (get_option($this->themeSlug.'_options')) ? get_option($this->themeSlug.'_options') : null;
  	if(isset($options[$name])){
  		$returnValue = apply_filters($this->themeSlug.'_options_'.$name, $options[$name]);
  	}else{
      $returnValue = apply_filters($this->themeSlug.'_options_'.$name, $this->themeOptions[$name][0]['default']);
    }

    $returnValue = apply_filters($this->themeSlug . '_' . $name, $returnValue);

    return $returnValue;
  }

  // Add a theme option (standard control class)
  public function addThemeOption($name, $settingArgs, $controlArgs){
    global $wp_customize;

    $controlArgs['settings'] = $this->optionName($name);

    $this->themeOptions[$name] = array($settingArgs, $controlArgs, false);
  }

  // Add a theme option (custom control class)
  public function addThemeOptionCustomControl($name, $settingArgs, $controlArgs, $controlClassString){
    $controlArgs['settings'] = $this->optionName($name);

    $this->themeOptions[$name] = array($settingArgs, $controlArgs, $controlClassString);
  }

  public function applyThemeOptions(){
    global $wp_customize;

    foreach($this->themeOptions as $name => $args){
      if($args[2] == false){
        $wp_customize->add_setting($this->optionName($name), $args[0]);
        $wp_customize->add_control($this->optionName($name), $args[1]);
      }else{
        $wp_customize->add_setting($this->optionName($name), $args[0]);
        $wp_customize->add_control( new $args[2]($wp_customize, $this->optionSlug($name), $args[1]));
      }
    }
  }

  public function headerOrFeaturedImage($imageSize = 'header-image'){
    if(get_the_post_thumbnail(null, $size = $imageSize)){
      $attachmentImageSrc = wp_get_attachment_image_src(get_post_thumbnail_id(), $imageSize);
      return $attachmentImageSrc[0];
    }else{
      return get_header_image();
    }

    return "";
  }

  public function sanitizeHexColor($color) {
    if('' === $color)
			return '';

		if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color))
			return $color;

    return null;
	}

  // PRIVATE FUNCTIONS

  private function optionName($name){
    return $this->themeSlug . "_options[" . $name . "]";
  }

  private function optionSlug($name){
    return $this->themeSlug . "_" . $name;
  }
}
?>
