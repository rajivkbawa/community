/**
 * @file
 * Utility functions to display settings summaries on vertical tabs.
 */

(function ($) {

Drupal.behaviors.communityAdminFieldsetSummaries = {
  attach: function (context) {
    $('details#edit-community-credentials', context).drupalSetSummary(function(context) {
      var server = $('#edit-community-connection-address :selected', context).text().toLowerCase();
      return Drupal.t('Using community @role server', { '@role': server });
    });



  }
};

})(jQuery);
