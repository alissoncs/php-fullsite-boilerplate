console.info('home.js');

$('.banner-slider .slick').slick({
  slidesToShow: 1,
  autoplay: false,
  dots: false,
});

$('.plans-carousel .slick').slick({
  // centerMode: true,
  dots: true,
  arrows: true,
  slidesToShow: 2,
  infinite: true,
  // variableWidth: true,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 1,
        dots: true,
        arrows: true,
      },
    },
  ],
});

if (window.location.hash) {
  var hash = window.location.hash + '';
  window.location.hash = '';
  if ($(hash).length) {
    $('body, html').stop()
      .animate({
        scrollTop: $(hash).offset().top - 90
      }, 500, 'swing', function () {
      });
  }
}

$('a').on('click', function() {
  var hash = ($(this).attr('href') || '').indexOf('#') !== -1;
  // console.log(hash);
  if (hash) {
    var hashpath = '#' + $(this).attr('href').split('#')[1];
    // console.log(hashpath);
    if ($(hashpath).length) {
      event.preventDefault();
      $('body, html').stop()
        .animate({
          scrollTop: $(hashpath).offset().top - 90
        }, 500, 'swing', function () {
        });
        return false;
    }
  }
});



$('input.pretty-toggle').each(function (event) {
  var css = '';
  if ($(this).val() === '1') {
    css = ' active';
  }
  var $wrap = $('<span class="pretty-toggle ' + css + '" role="checkbox" />');
  // $wrap.append($(this));
  // console.log($wrap);
  $(this).wrap($wrap);
  $(this).before('<i class="on">On</i>');
  $(this).before('<i class="off">Off</i>');
  // $(this).after('<span class="pretty-toggle ' + css + '" role="checkbox"><i class="on">On</i><i class="off">Off</i></span>');
});

$('span.pretty-toggle').on('click', function () {
  $(this).toggleClass('active');
  $(this).find('input').val($(this).hasClass('active') ? '1' : '0');
});


$('.custom-select').each(function () {
  $(this).select2({
    placeholder: $(this).attr('data-placeholder') || 'Selecione',
    allowClear: true,
    language: 'pt-BR',
  });
});


/// menu mobile

$('.menu-toggle').on('click', function() {
  $('.mobile-navigation').toggleClass('visible');
  $('body').toggleClass('overflow');

  $('.mobile-navigation .navigation > ul > li > ul').hide();

});

$('.mobile-navigation .navigation > ul > li > a').on('click', function(event) {
  var ul = $(this).parent().find('> ul');
  console.info('click mobile-navigation', $(this), ul);
  if (ul && ul.length) {
    event.preventDefault();
    ul.slideToggle();
    return false;
  } else if ($(this).attr('href')) {
    window.location.href = $(this).attr('href');
  }
});


$('.main-header .navigation').find('li').each(function() {
  if ($(this).find('.submenu').length) {
    $(this)
      .mouseenter(function() {
        $('body').addClass('overlay-enabled');
      }).mouseleave(function() {
        $('body').removeClass('overlay-enabled');
      });
  }
});


$('.footer-sitemap .more > a').on('click', function(event) {
  event.preventDefault();
  event.stopPropagation();
  $(this).closest('ul').find('.hide').removeClass('hide');
  $(this).parent().hide();
  return false;
});

$('.footer-sitemap .block-menu h3').on('click', function(event) {
  var $child = $(this).closest('.block-menu').find('ul');

  if ($child.length && $(window).width() < 768) {
    event.preventDefault();
    event.preventDefault();
    $(this).parent().toggleClass('open');
    $child.slideToggle();
    return false;
  }
});

$(window).on('resize', function() {
  $('.footer-sitemap .block-menu').each(function() {
    $(this).removeClass('open');
    $(this).find('ul').removeAttr('style');
  });
});




