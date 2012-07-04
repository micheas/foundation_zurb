<?php

/**
 * Preprocessor for html.tpl.php template file.
 */
function foundation_zurb_preprocess_html(&$variables) {
  // Add conditional CSS for IE
  drupal_add_css(path_to_theme() . '/css/framework/ie.css', array('weight' => CSS_THEME, 'browsers' => array('!IE' => FALSE), 'preprocess' => FALSE));

  global $language;

  // Clean up the lang attributes
  $variables['html_attributes'] = 'lang="' . $language->language . '" dir="' . $language->dir . '"';

  // Add language body class.
  if (function_exists('locale')) {
    $variables['classes_array'][] = 'lang-' . $variables['language']->language;
  }

  // Custom fonts from Google web-fonts
  $font = str_replace(' ', '+', theme_get_setting('zurb_foundation_font'));
  if (theme_get_setting('zurb_foundation_font')) {
    drupal_add_css('http://fonts.googleapis.com/css?family=' . $font , array('type' => 'external', 'group' => CSS_THEME));
  }

  // Classes for body element. Allows advanced theming based on context
  if (!$variables['is_front']) {
    // Add unique class for each page.
    $path = drupal_get_path_alias($_GET['q']);
    // Add unique class for each website section.
    list($section, ) = explode('/', $path, 2);
    $arg = explode('/', $_GET['q']);
    if ($arg[0] == 'node' && isset($arg[1])) {
      if ($arg[1] == 'add') {
        $section = 'node-add';
      }
      elseif (isset($arg[2]) && is_numeric($arg[1]) && ($arg[2] == 'edit' || $arg[2] == 'delete')) {
        $section = 'node-' . $arg[2];
      }
    }
    $variables['classes_array'][] = drupal_html_class('section-' . $section);
  }

  // Store the menu item since it has some useful information.
  $variables['menu_item'] = menu_get_item();
  if ($variables['menu_item']) {
    switch ($variables['menu_item']['page_callback']) {
      case 'views_page':
        $variables['classes_array'][] = 'views-page';
        break;
      case 'page_manager_page_execute':
      case 'page_manager_node_view':
      case 'page_manager_contact_site':
        $variables['classes_array'][] = 'panels-page';
        break;
    }
  }
}

/**
 * Implements hook_html_head_alter().
 */
function foundation_zurb_html_head_alter(&$head_elements) {
  // HTML5 charset declaration.
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8',
  );

  // Optimize mobile viewport.
 $head_elements['mobile_viewport'] = array(
	'#type' => 'html_tag',
	'#tag' => 'meta',
	'#attributes' => array(
		'name' => 'viewport',
		'content' => 'width=device-width',
		),
 );

  // Force IE to use Chrome Frame if installed.
  $head_elements['chrome_frame'] = array(
  	'#type' => 'html_tag',
  	'#tag' => 'meta',
  	'#attributes' => array(
  		'content' => 'ie=edge, chrome=1',
  		'http-equiv' => 'x-ua-compatible',
    ),
  );

  // Remove image toolbar in IE.
  $head_elements['ie_image_toolbar'] = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
      'http-equiv' => 'ImageToolbar',
      'content' => 'false',
    ),
  );

}

function foundation_zurb_preprocess_page(&$variables) {
  // Add page--node_type.tpl.php suggestions
  if (!empty($variables['node'])) {
    $variables['theme_hook_suggestions'][] = 'page__' . $variables['node']->type;
  }

  $variables['logo_img'] = '';
  if (!empty($variables['logo'])) {
    $variables['logo_img'] = theme('image', array(
      'path'  => $variables['logo'],
      'alt'   => strip_tags($variables['site_name']) . ' ' . t('logo'),
      'title' => strip_tags($variables['site_name']) . ' ' . t('Home'),
						'attributes' => array(
        'class' => array('logo'),
      ),
    ));
  }
  $variables['linked_logo']  = '';
  if (!empty($variables['logo_img'])) {
    $variables['linked_logo'] = l($variables['logo_img'], '<front>', array(
      'attributes' => array(
        'rel'   => 'home',
        'title' => strip_tags($variables['site_name']) . ' ' . t('Home'),
      ),
      'html' => TRUE,
    ));
  }
  $variables['linked_site_name'] = '';
  if (!empty($variables['site_name'])) {
    $variables['linked_site_name'] = l($variables['site_name'], '<front>', array(
      'attributes' => array(
        'rel'   => 'home',
        'title' => strip_tags($variables['site_name']) . ' ' . t('Home'),
      ),
    ));
  }

  // Site navigation links.
  $variables['main_menu_links'] = '';
  if (isset($variables['main_menu'])) {
    $variables['main_menu_links'] = theme('links__system_main_menu', array(
      'links' => $variables['main_menu'],
      'attributes' => array(
        'id' => 'main-menu',
        'class' => array('menu'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      ),
    ));
  }
  $variables['secondary_menu_links'] = '';
  if (isset($variables['secondary_menu'])) {
    $variables['secondary_menu_links'] = theme('links__system_secondary_menu', array(
      'links' => $variables['secondary_menu'],
      'attributes' => array(
        'id'    => 'secondary-menu',
        'class' => array('secondary', 'menu'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      ),
    ));
  }

  // Convenience variables
  $left = $variables['page']['sidebar_first'];
  $right = $variables['page']['sidebar_second'];

  // Dynamic sidebars
  if (!empty($left) && !empty($right)) {
    $variables['main_grid'] = 'six';
    $variables['sidebar_first_grid'] = 'three';
  } elseif (empty($left) && !empty($right)) {
    $variables['main_grid'] = 'nine';
    $variables['sidebar_first_grid'] = '';
  } elseif (!empty($left) && empty($right)) {
    $variables['main_grid'] = 'nine';
    $variables['sidebar_first_grid'] = 'three';
  } else {
    $variables['main_grid'] = 'twelve';
    $variables['sidebar_first_grid'] = '';
  }

}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 */
function foundation_zurb_preprocess_node(&$variables) {
  // Add a class for the view mode.
  if (!$variables['teaser']) {
    $variables['classes_array'][] = 'view-mode-' . $variables['view_mode'];
  }

  $variables['title_attributes_array']['class'][] = 'node-title';
}

/**
 * Implements template_preprocess_block().
 */
function sasson_preprocess_block(&$vars) {
  // Add a striping class.
  $vars['classes_array'][] = 'block-' . $vars['zebra'];

  $vars['title_attributes_array']['class'][] = 'block-title';

  // In the header region visually hide block titles.
  if ($vars['block']->region == 'header') {
    $vars['title_attributes_array']['class'][] = 'element-invisible';
  }
}

function foundation_zurb_field($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div ' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  foreach ($variables['items'] as $delta => $item) {
    $output .= drupal_render($item);
  }

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Override or insert variables into the field template.
 */
function foundation_zurb_preprocess_field(&$variables) {
  $variables['title_attributes_array']['class'][] = 'field-label';

  // Edit classes for taxonomy term reference fields.
  if ($variables['field_type_css'] == 'taxonomy-term-reference') {
    $variables['content_attributes_array']['class'][] = 'comma-separated';
  }
  /**
   * Convinence variables
  $name = $vars['element']['#field_name'];
  $bundle = $vars['element']['#bundle'];
  $mode = $vars['element']['#view_mode'];
  $classes = &$vars['classes_array'];
  $title_classes = &$vars['title_attributes_array']['class'];
  $content_classes = &$vars['content_attributes_array']['class'];
  $item_classes = array();
 
  // Global field classes
  $classes[] = 'field-wrapper';
  $title_classes[] = 'field-label';
  $content_classes[] = 'field-items';
  $item_classes[] = 'field-item';
 
  // Uncomment the lines below to see variables you can use to target a field
  // print '<strong>Name:</strong> ' . $name . '<br/>';
  // print '<strong>Bundle:</strong> ' . $bundle  . '<br/>';
  // print '<strong>Mode:</strong> ' . $mode .'<br/>';
 
  ///* Add specific classes to targeted fields 
  switch ($mode) {
    // All teasers 
    case 'teaser':
      switch ($field) {
        // Teaser read more links
        case 'node_link':
          $item_classes[] = 'more-link';
          break;
        // Teaser descriptions
        case 'body':
        case 'field_description':
          $item_classes[] = 'description';
          break;
      }
      break;
  }
 
  switch ($field) {
    case 'field_authors':
      $title_classes[] = 'inline';
      $content_classes[] = 'authors';
      $item_classes[] = 'author';
      break;
  }
 
  // Apply odd or even classes along with our custom classes to each item
  foreach ($vars['items'] as $delta => $item) {
    $item_classes[] = $delta % 2 ? 'odd' : 'even';
    $vars['item_attributes_array'][$delta]['class'] = $item_classes;
  }
   **/
}

/**
 * Generate the HTML output for a menu link and submenu.
 *
 * @param $vars
 *   An associative array containing:
 *   - element: Structured array data for a menu link.
 *
 * @return
 *   A themed HTML string.
 *
 * @ingroup themeable
 */
function foundation_zurb_menu_link(array $vars) {
  $element = $vars['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  // Adding a class depending on the TITLE of the link (not constant)
  $element['#attributes']['class'][] = drupal_html_id($element['#title']);
  // Adding a class depending on the ID of the link (constant)
  if (isset($element['#original_link']['mlid']) && !empty($element['#original_link']['mlid'])) {
    $element['#attributes']['class'][] = 'mid-' . $element['#original_link']['mlid'];
  }
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

/**
 * Output custom Breadcrumb
 */
function foundation_zurb_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $title = strip_tags(drupal_get_title());

  if (!empty($breadcrumb)) {
    // Provide a navigational heading to give context for breadcrumb links to
    // screen-reader users. Make the heading invisible with .element-invisible.
    $output = '<h2 class="element-invisible">' . t('You are here') . '</h2>';

    $output .= '<div class="breadcrumb">' . implode(' &raquo; ', $breadcrumb) . ' &raquo; ' . $title . '</div>';
    return $output;
  }
}

/**
 * Implements hook_preprocess_block()
 */
 
function foundation_zurb_preprocess_block(&$vars) {
//  $block_id = $vars['block']->module . '-' . $vars['block']->delta;
//  $classes = &$vars['classes_array'];
//  $title_classes = &$vars['title_attributes_array']['class'];
//  $content_classes = &$vars['content_attributes_array']['class'];
 
  // Add global classes to all blocks 
//  $title_classes[] = 'block-title';
//  $content_classes[] = 'block-content';

  // Add classes based on the block delta. 
//  switch ($block_id) {
//    // System Navigation block
//    case 'system-navigation':
//      $classes[] = 'block-rounded';
//      $title_classes[] = 'block-fancy-title';
//      $content_classes[] = 'block-fancy-content';
//      break;
//    /* Main Menu block */
//    case 'system-main-menu':
//    /* User Login block */
//    case 'user-login':
//      $title_classes[] = 'element-invisible';
//      break;
//  }
}

function foundation_zurb_form_alter(&$form, &$form_state, $form_id) {
//  if ($form_id == 'search_form') {
//    $form['basic']['keys']['#title'] = '';
//    $form['basic']['keys']['#size'] = '25';
//  $form['basic']['keys']['#default_value'] = 'Type to search';
//  }
}


/**
 * Implements theme_field__field_type().
 */
function foundation_zurb_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h2 class="field-label">' . $variables['label'] . ': </h2>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '">' . $output . '</div>';

  return $output;
}
