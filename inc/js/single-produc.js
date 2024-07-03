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

  var decorationOptions = $('#yith-wapo-block-1');
  var onlySingleRoom = $('.ha-single-room');
  var planOptions = $('#yith-wapo-block-2');
  plan && decorationOptions.remove();
  plan && onlySingleRoom.remove();
  !plan && planOptions.remove();

  if(plan) {
    // Eliminar el atributo 'checked' de todos los inputs tipo radio con clase 'yith-wapo-option-value'
    $('.yith-wapo-option-value').prop('checked', false);
  
    var labelsArray = [];
    $('.yith-wapo-label').each(function() {
      var labelText = $(this).text().trim();
      var normalizedText = normalizeText(labelText);
      var labelFor = $(this).attr('for');
      labelsArray.push({ text: normalizedText, for: labelFor });

      // Comparar el valor del parámetro 'plan' con el 'normalizedText'
      if (plan === normalizedText) {
        $('#' + labelFor).prop('checked', true);
        found = true;
        // Mostrar la card correspondiente
        $('#' + labelFor).closest('.yith-wapo-option').show();
    } else {
        // Ocultar todas las cards
        $('#' + labelFor).closest('.yith-wapo-option').hide();
    }
    });
  }

  console.log(labelsArray);

  // Print the parameters to console
  console.log('Plan:', plan);
  console.log('Type:', type);
});


function normalizeText(text) {
  // Convertir a minúsculas
  text = text.toLowerCase();
  // Reemplazar espacios por guiones
  text = text.replace(/\s+/g, '-');
  // Eliminar acentos
  text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
  return text;
}