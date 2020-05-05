var $ = require('jquery');
window.$ = $;
window.jQuery = $;
var Popper = require('popper.js');
require('bootstrap')
require('./plugins/slick');
require('./components/functions.js');
require('./components/header.js');
require('./components/search.js');



$('.banner-slider .slick').slick({
  slidesToShow: 1,
  autoplay: false,
  dots: false,
});

$('.plans-carousel .slick').slick({
  centerMode: true,
  infinite: true,
  dots: true,
  slidesToShow: 1,
  infinite: true,
  variableWidth: true,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 1,
      },
    },
  ],
});

console.info('main.js end');
