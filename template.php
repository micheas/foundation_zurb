<?php

/**
 * Preprocessor for html.tpl.php template file.
 */
function foundation_zurb_preprocess_html(&$variables) {
  // Add conditional CSS for IE
  drupal_add_css(path_to_theme() . '/stylesheet/ie.css', array('weight' => CSS_THEME, 'browsers' => array('!IE' => FALSE), 'preprocess' => FALSE));

  global $language;

  // Clean up the lang attributes
  $variables['html_attributes'] = 'lang="' . $language->language . '" dir="' . $language->dir . '"';

  // Add language body class.
  if (function_exists('locale')) {
    $variables['classes_array'][] = 'lang-' . $variables['language']->language;
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
        'class' => array('menu', 'inline'),
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
        'class' => array('inline', 'secondary-menu'),
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
  $variables['date'] = format_date($variables['created'], 'custom', 'M j, Y');
  $variables['submitted'] = t('posted by !username on !datetime', array('!username' => $variables['name'], '!datetime' => $variables['date']));

  // Add an unpublished class
  if ($variables['status'] == TRUE) {
    $variables['classes_array'][] = t('published');
  } else {
    $variables['classes_array'][] = t('unpublished');
  }

  $variables['classes_array'][] = 'clearfix';
		$variables['title_attributes_array']['class'][] = 'node-title';
}

/**
 * Override or insert variables into the field template.
 */
function foundation_zurb_preprocess_field(&$variables) {
  $variables['classes_array'][] = 'clearfix';
  $variables['title_attributes_array']['class'][] = 'field-label';
  $variables['content_attributes_array']['class'][] = 'field-items';

  // Edit classes for taxonomy term reference fields.
  if ($variables['field_type_css'] == 'taxonomy-term-reference') {
    $variables['content_attributes_array']['class'][] = 'comma-separated';
  }
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
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
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
