jQuery(document).ready(function ($) {
  $(".hz-carousel-features").slick({
    slidesToShow: 4,
    prevArrow:
      '<div class="hz-slick-arrow hz-slick-prev"><i class="fa-solid fa-chevron-left"></i></div>',
    nextArrow:
      '<div class="hz-slick-arrow hz-slick-next"><i class="fa-solid fa-chevron-right"></i></div>',
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 2,
        },
      },
    ],
  });
});
