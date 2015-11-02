<?php

/**
 * @file
 * Contains \Drupal\community\Form\communitySettingsForm.
 */

namespace Drupal\community\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Configures Community settings.
 */
class CommunitySettingsForm extends ConfigFormBase {

  /**
   * Implements \Drupal\Core\Form\FormInterface::getFormID().
   */
  public function getFormID() {
    return 'community';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'community.settings',
    ];
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $community_config = $this->config('community.settings');

    // Put fieldsets into vertical tabs
    $form['community-settings'] = array(
      '#type' => 'vertical_tabs',
      '#attached' => array(
        'library' => array(
          'community/community.scripts',
        ),
      ),
    );

    // Container for credential forms
    $form['community_credentials'] = array(
      '#type'          => 'details',
      '#title'         => $this->t('Credentials'),
      '#description'   => $this->t('Account number and authorization information.'),
      '#group'         => 'community-settings',
    );

    $form['community_credentials']['community_access_license'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('community OnLine Tools XML Access Key'),
      '#default_value' => $community_config->get('access_license'),
      '#required' => TRUE,
    );
    $form['community_credentials']['community_shipper_number'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('community Shipper #'),
      '#description' => $this->t('The 6-character string identifying your community account as a shipper.'),
      '#default_value' => $community_config->get('shipper_number'),
      '#required' => TRUE,
    );
    $form['community_credentials']['community_user_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('community.com user ID'),
      '#default_value' => $community_config->get('user_id'),
      '#required' => TRUE,
    );
    $form['community_credentials']['community_password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $community_config->get('password'),
    );
    $form['community_credentials']['community_connection_address'] = array(
      '#type' => 'select',
      '#title' => $this->t('Server mode'),
      '#description' => $this->t('Use the Testing server while developing and configuring your site. Switch to the Production server only after you have demonstrated that transactions on the Testing server are working and you are ready to go live.'),
      '#options' => array('https://wwwcie.community.com/community.app/xml/' => $this->t('Testing'),
        'https://onlinetools.community.com/community.app/xml/' => $this->t('Production'),
      ),
      '#default_value' => "",
    );

    $form['services'] = array(
      '#type' => 'details',
      '#title' => $this->t('Service options'),
      '#description' => $this->t('Set the conditions that will return a community quote.'),
      '#group'         => 'community-settings',
    );

    $form['services']['community_services'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('community services'),
      '#default_value' => $community_config->get('services'),
      '#options' => "",
      '#description' => $this->t('Select the community services that are available to customers.'),
    );

    
	// Container for quote options
    $form['quote_options'] = array(
      '#type'          => 'details',
      '#title'         => $this->t('Quote options'),
      '#description'   => $this->t('Preferences that affect computation of quote.'),
      '#group'         => 'community-settings',
    );

    $form['quote_options']['community_all_in_one'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Product packages'),
      '#default_value' => $community_config->get('all_in_one'),
      '#options' => array(
        0 => $this->t('Each product in its own package'),
        1 => $this->t('All products in one package'),
      ),
      '#description' => $this->t('Indicate whether each product is quoted as shipping separately or all in one package. Orders with one kind of product will still use the package quantity to determine the number of packages needed, however.'),
    );

    // Form to select package types
    $form['quote_options']['community_package_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Default Package Type'),
      '#default_value' => $community_config->get('package_type'),
      '#options' => '',
      '#description' => $this->t('Type of packaging to be used.  May be overridden on a per-product basis via the product node edit form.'),
    );
    $form['quote_options']['community_classification'] = array(
      '#type' => 'select',
      '#title' => $this->t('community Customer classification'),
      '#options' => array(
        '01' => $this->t('Wholesale'),
        '03' => $this->t('Occasional'),
        '04' => $this->t('Retail'),
      ),
      '#default_value' => $community_config->get('classification'),
      '#description' => $this->t('The kind of customer you are to community. For daily pickcommunity the default is wholesale; for customer counter pickcommunity the default is retail; for other pickcommunity the default is occasional.'),
    );

    $form['quote_options']['community_negotiated_rates'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Negotiated rates'),
      '#default_value' => $community_config->get('negotiated_rates'),
      '#options' => array(1 => $this->t('Yes'), 0 => $this->t('No')),
      '#description' => $this->t('Is your community account receiving negotiated rates on shipments?'),
    );

    // Form to select pickup type
    $form['quote_options']['community_pickup_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Pickup type'),
      '#options' => array(
        '01' => 'Daily Pickup',
        '03' => 'Customer Counter',
        '06' => 'One Time Pickup',
        '07' => 'On Call Air',
        '11' => 'Suggested Retail Rates',
        '19' => 'Letter Center',
        '20' => 'Air Service Center',
      ),
      '#default_value' => $community_config->get('pickup_type'),
    );

    $form['quote_options']['community_residential_quotes'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Assume community shipping quotes will be delivered to'),
      '#default_value' => $community_config->get('residential_quotes'),
      '#options' => array(
        0 => $this->t('Business locations'),
        1 => $this->t('Residential locations (extra fees)'),
      ),
    );

    $form['quote_options']['community_unit_system'] = array(
      '#type' => 'select',
      '#title' => $this->t('System of measurement'),
      '#default_value' => $community_config->get('unit_system', \Drupal::config('uc_store.settings')->get('length.units')),
      '#options' => array(
        'in' => $this->t('Imperial'),
        'cm' => $this->t('Metric'),
      ),
      '#description' => $this->t('Choose the standard system of measurement for your country.'),
    );

    $form['quote_options']['community_insurance'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Package insurance'),
      '#default_value' => $community_config->get('insurance'),
      '#description' => $this->t('When enabled, the quotes presented to the customer will include the cost of insurance for the full sales price of all products in the order.'),
    );

    // Container for markup forms
    $form['community_markcommunity'] = array(
      '#type'          => 'details',
      '#title'         => $this->t('Markcommunity'),
      '#description'   => $this->t('Modifiers to the shipping weight and quoted rate.'),
      '#group'         => 'community-settings',
    );

    // Form to select type of rate markup
    $form['community_markcommunity']['community_rate_markup_type'] = array(
      '#type' => 'select',
      '#title' => $this->t('Rate markup type'),
      '#default_value' => $community_config->get('rate_markup_type'),
      '#options' => array(
        'percentage' => $this->t('Percentage (%)'),
        'multiplier' => $this->t('Multiplier (×)'),
        'currency' => $this->t('Addition (!currency)', array('!currency' => \Drupal::config('uc_store.settings')->get('currency.symbol'))),
      ),
    );

    // Form to select rate markup amount
    $form['community_markcommunity']['community_rate_markup'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Shipping rate markup'),
      '#default_value' => $community_config->get('rate_markup'),
      '#description' => $this->t('Markup shipping rate quote by currency amount, percentage, or multiplier.'),
    );

    // Form to select type of weight markup
    $form['community_markcommunity']['community_weight_markup_type'] = array(
      '#type'          => 'select',
      '#title'         => $this->t('Weight markup type'),
      '#default_value' => $community_config->get('weight_markup_type'),
      '#options'       => array(
        'percentage' => $this->t('Percentage (%)'),
        'multiplier' => $this->t('Multiplier (×)'),
        'mass'       => $this->t('Addition (!mass)', array('!mass' => '#')),
      ),
      '#disabled' => TRUE,
    );

    // Form to select weight markup amount
    $form['community_markcommunity']['community_weight_markup'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Shipping weight markup'),
      '#default_value' => $community_config->get('weight_markup'),
      '#description'   => $this->t('Markup community shipping weight on a per-package basis before quote, by weight amount, percentage, or multiplier.'),
      //'#disabled' => TRUE,
    );

    // Container for label printing
    $form['community_labels'] = array(
      '#type'          => 'details',
      '#title'         => $this->t('Label Printing'),
      '#description'   => $this->t('Preferences for community Shipping Label Printing.  Additional permissions from community are required to use this feature.'),
      '#group'         => 'community-settings',
    );

    $intervals = array(86400, 302400, 604800, 1209600, 2419200, 0);
    $period = array_map(array(\Drupal::service('date.formatter'), 'formatInterval'), array_combine($intervals, $intervals));
    $period[0] = $this->t('Forever');

    // Form to select how long labels stay on server
    $form['community_labels']['community_label_lifetime'] = array(
      '#type'          => 'select',
      '#title'         => $this->t('Label lifetime'),
      '#default_value' => $community_config->get('label_lifetime'),
      '#options'       => $period,
      '#description'   => $this->t('Controls how long labels are stored on the server before being automatically deleted. Cron must be enabled for automatic deletion. Default is never delete the labels, keep them forever.'),
    );

    // Taken from system_settings_form(). Only, don't use its submit handler.
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    );
    $form['actions']['cancel'] = array(
      //'#markup' => $this->l($this->t('Cancel'), new Url('quote.methods')),
    );

    if (!empty($_POST) && $form_state->getErrors()) {
      drupal_set_message($this->t('The settings have not been saved because of the errors.'), 'error');
    }
    if (!isset($form['#theme'])) {
      $form['#theme'] = 'system_settings_form';
    }

    return parent::buildForm($form, $form_state);
  }


  /**
   * Implements \Drupal\Core\Form\FormInterface::validateForm().
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $old_password = $this->config('community.settings')->get('password');
    if (!$form_state->getValue('community_password')) {
      if ($old_password) {
        $form_state->setValueForElement($form['community_credentials']['community_password'], $old_password);
      }
      else {
        $form_state->setErrorByName('community_password', $this->t('Password field is required.'));
      }
    }

    if (!is_numeric($form_state->getValue('community_rate_markup'))) {
      $form_state->setErrorByName('community_rate_markup', $this->t('Rate markup must be a numeric value.'));
    }
    if (!is_numeric($form_state->getValue('community_weight_markup'))) {
      $form_state->setErrorByName('community_weight_markup', $this->t('Weight markup must be a numeric value.'));
    }
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $community_config = $this->config('community.settings');

    $values = $form_state->getValues();
    $community_config
      ->set('access_license', $values['community_access_license'])
      ->set('shipper_number', $values['community_shipper_number'])
      ->set('user_id', $values['community_user_id'])
      ->set('password', $values['community_password'])
      ->set('connection_address', $values['community_connection_address'])
      ->set('services', $values['community_services'])
      ->set('pickup_type', $values['community_pickup_type'])
      ->set('package_type', $values['community_package_type'])
      ->set('classification', $values['community_classification'])
      ->set('negotiated_rates', $values['community_negotiated_rates'])
      ->set('residential_quotes', $values['community_residential_quotes'])
      ->set('rate_markup_type', $values['community_rate_markup_type'])
      ->set('rate_markup', $values['community_rate_markup'])
      ->set('weight_markup_type', $values['community_weight_markup_type'])
      ->set('weight_markup', $values['community_weight_markup'])
      ->set('label_lifetime', $values['community_label_lifetime'])
      ->set('all_in_one', $values['community_all_in_one'])
      ->set('unit_system', $values['community_unit_system'])
      ->set('insurance', $values['community_insurance'])
      ->save();

    drupal_set_message($this->t('The configuration options have been saved.'));

    // @todo: Still need these two lines?
    //cache_clear_all();
    //drupal_theme_rebuild();

    parent::submitForm($form, $form_state);
  }

}
