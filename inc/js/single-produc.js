jQuery(document).ready(function($) {
  // Function to get URL parameters
  function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(window.location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  }

  // Get 'plan' and 'type' parameters
  var plan = getUrlParameter('plan');
  var type = getUrlParameter('type');

  // Print the parameters to console
  console.log('Plan:', plan);
  console.log('Type:', type);
});