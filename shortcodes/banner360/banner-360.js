var modal = document.querySelector("#modal");

if (modal) {
  var boxOpenModal = document.getElementById("open-modal");
  var closeModal = document.querySelector(".hz-modal-ipanorama__close");

  closeModal.addEventListener("click", function () {
    modal.style.display = "none";
  });

  boxOpenModal.addEventListener("click", function () {
    modal.style.display = "grid";
  });
}
