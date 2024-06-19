const selectTime = document.querySelector(".hz-current-selection__input");

if (selectTime) {
  const list = document.querySelector(".hz-options");
  selectTime.addEventListener("click", function () {
    if (list.style.display === "block" || list.style.display === "flex") {
      list.style.display = "none";
    } else {
      list.style.display = "block";
    }
  });

  const options = document.querySelectorAll(".hz-option");
  const inputTime = document.getElementById("hz-time");

  options.forEach(function (i) {
    // console.log(i)
    i.addEventListener("click", function (e) {
      const val = this.dataset.value;
      const text = this.innerHTML;
      selectTime.querySelector(".hz-current-selection__input__text").innerHTML =
        text;
      inputTime.value = val;

      list.style.display = "none";
    });
  });
}

// Ventana modal de confirmación
jQuery(document).ready(function ($) {
  const modalWindow = document.getElementById("hz-modal-booking");

  if (modalWindow) {
    const closeBtn = modalWindow.querySelector(".hz-modal-close");
    const openBtn = document.getElementById("hz-open-modal");
    const cancelBtn = document.querySelector(".hz-book-cancel");

    // Habilitar botón de reserva si se cumplen las condiciones

    function enableBtnBooking(isEnabled){
      if(isEnabled){
        $('.hz-book-pay').removeClass('disabled')
      } else{
        $('.hz-book-pay').addClass('disabled')
      }
    }

    $('form.cart').on('change', function(e){
      if($('#wc-bookings-form-end-time').val() !== undefined && $('#wc-bookings-form-end-time').val() > 0){
        enableBtnBooking(true)
      } else {
        enableBtnBooking(false)
      }
    })

    openBtn.addEventListener("click", function (e) {
      //Bloqueo para que no se muestre la modal si no ha seleccionado un paquete
      const opcionSeleccionada = $("#wc-bookings-form-end-time option:selected").text();

      // Si no se ha seleccionado una hora final retorna false
      if($('#wc-bookings-form-end-time').val() == undefined || $('#wc-bookings-form-end-time').val() == "0") return false;
      if(opcionSeleccionada == "Selecciona un paquete"){
        modalWindow.style.display = "none";
      } else {
        modalWindow.style.display = "flex";
      }
    });

    closeBtn.addEventListener("click", function () {
      modalWindow.style.display = "none";
    });

    cancelBtn.addEventListener("click", function () {
      modalWindow.style.display = "none";
    });
  }

  // Restar una hora en el front de carrito para considerar una hora entre una reserva y otra
  const duracionElement = $("dd.variation-Duracin p");
  // Obtener el texto actual y convertirlo a un número de horas
  const duracionActual = duracionElement.text().trim(); // "7 horas"
  const horas = parseInt(duracionActual); // Convertir "7 horas" a 7
  // Restar una hora al valor actual
  const horasRestadas = horas - 1;
  // Actualizar el contenido del elemento <dd> con el nuevo valor
  duracionElement.text(horasRestadas + " horas"); // Actualizar el texto a la nueva duración


  // Agregar imagenes y overlay a input de Decoración en la reserva de producto
  $('.hz-decoration .image-container').removeAttr("for");

  $(".option-container").append('<div id="overlayDecoration" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index: 999;"><div id="imageContainer" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:100%;text-align:center;"><img id="largeImage" src="" alt="" style="width:60%;height:60%;"></div></div>');

  $('.hz-decoration .image-container img').on('click', function () {
    var src = $(this).attr('src');
    $('#largeImage').attr('src', src);
    $('#overlayDecoration').show();
  });

  $('#overlayDecoration').on('click', function () {
    $(this).hide();
  });

  // Añadir flechas a los input de tipo number en la reserva de producto

  // Función para añadir flechas
  function addArrows(selector) {
    $(selector).append('<div class="up-arrow">▲</div>');
    $(selector).append('<div class="down-arrow">▼</div>');
  }

  // Función para manejar clics
  function handleClick(selector, inputSelector, isDownArrow) {
    $(selector).on('click', function (e) {
      var $element = $(e.target);
      e.preventDefault();
      if (!$element.data('clickedAt') || +new Date() - $element.data('clickedAt') > 300) {
        var valor = parseInt($(inputSelector).val());
        if (isDownArrow && valor > 0) {
          $(inputSelector).val(valor - 1);
        } else if (!isDownArrow) {
          $(inputSelector).val(valor + 1);
        }
        $(inputSelector).trigger("change");
        $("#hz-open-modal").prop("disabled", true);
        setTimeout(function () {
          $("#hz-open-modal").prop("disabled", false);
        }, 2000);
      }
      $element.data('clickedAt', +new Date());
    });
  }

  // Añadir flechas y manejar clics
  addArrows(".hz-additional__person");
  handleClick(".hz-additional__person .up-arrow", "#wc_bookings_field_persons");
  handleClick(".hz-additional__person .down-arrow", "#wc_bookings_field_persons", true);

  addArrows(".hz-additional__hour");
  handleClick(".hz-additional__hour .up-arrow", "#wc_bookings_field_hour");
  handleClick(".hz-additional__hour .down-arrow", "#wc_bookings_field_hour", true);
});
