<?php

/**
 * @file
 * Contains .
 */

/**
 * Field handler to translate a language into its readable form.
 *
 * @ingroup views_field_handlers
 */
class views_handler_field_locale_language extends views_handler_field {

  /**
   * {@inheritdoc}
   */
  public function option_definition() {
    $options = parent::option_definition();
    $options['native_language'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['native_language'] = array(
      '#title' => t('Native language'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['native_language'],
      '#description' => t('If enabled, the native name of the language will be displayed'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function render($values) {
<<<<<<< HEAD
    $languages = views_language_list(empty($this->options['native_language']) ? 'name' : 'native');
=======
    $languages = locale_language_list(empty($this->options['native_language']) ? 'name' : 'native');
>>>>>>> db130ce93 (Update modules to latest versions.  Issue: CEHWS-1221)
    $value = $this->get_value($values);
    return isset($languages[$value]) ? $languages[$value] : '';
  }

}
