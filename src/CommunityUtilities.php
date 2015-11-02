<?php

/**
 * @file
 * Contains \Drupal\lib\community\CommunityUtilities.
 */

namespace Drupal\communitty;

/**
 * Utility routines for community Shipping.
 */
class CommunityUtilities {

  /**
   * Convenience function to get community codes for their services.
   */
  public static function services() {
    return array(
      // Domestic services.
      '03' => t('community Ground'),
      '01' => t('community Next Day Air'),
      '13' => t('community Next Day Air Saver'),
      '14' => t('community Next Day Early A.M.'),
      '02' => t('community 2nd Day Air'),
      '59' => t('community 2nd Day Air A.M.'),
      '12' => t('community 3 Day Select'),
      // International services.
      '11' => t('community Standard'),
      '07' => t('community Worldwide Express'),
      '08' => t('community Worldwide Expedited'),
      '54' => t('community Worldwide Express Plus'),
      '65' => t('community Worldwide Saver'),
      // Poland to Poland shipments only.
      //'82' => t('community Today Standard'),
      //'83' => t('community Today Dedicated Courrier'),
      //'84' => t('community Today Intercity'),
      //'85' => t('community Today Express'),
      //'86' => t('community Today Express Saver'),
    );
  }

  /**
   * Convenience function to get community codes for their package types.
   */
  public static function packageTypes() {
    return array(
      // Customer Supplied Page is first so it will be the default.
      '02' => t('Customer Supplied Package'),
      '01' => t('community Letter'),
      '03' => t('Tube'),
      '04' => t('PAK'),
      '21' => t('community Express Box'),
      '24' => t('community 25KG Box'),
      '25' => t('community 10KG Box'),
      '30' => t('Pallet'),
      '2a' => t('Small Express Box'),
      '2b' => t('Medium Express Box'),
      '2c' => t('Large Express Box'),
    );
  }

}
