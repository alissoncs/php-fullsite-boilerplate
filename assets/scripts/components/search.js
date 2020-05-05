console.info('search.js 2');

$('.topbar .search-wrapper, .mobile-menu-bar .search-toggle, .mobile-navigation .search-wrapper, .main-header-float .search-wrapper').on('click', function (event) {
  event.preventDefault();
  console.info('clicked to open search');
  $('.large-search')
    .addClass('active')
    .find('input[name=s]')
    .focus();
  return false;
}).on('blur', function (event) {
})

$('.large-search').each(function () {
  var $context = $(this);
  var $form = $(this).find('form');
  var $input = $form.find('input');
  var $results = $('.search-results', $context);

  $form.on('submit', function(event) { event.preventDefault() })

  function getResultsWrapper() {
    $results = $('.search-results', $context);
    return $results;
  }

  $context.find('input[name=s]')
    .on('focus', function () {
      console.info('focus event');
      addBodyOverlay();
    });

  $context.find('.close-button').on('click', function (event) {
    event.preventDefault();
    $context.removeClass('active');
    $context.find('input[name=s]').val('');
    getResultsWrapper().remove();
    removeBodyOverlay();
  });

  onClickEsc(function () {
    $context.removeClass('active');
    $context.find('input[name=s]').val('');
    removeBodyOverlay();
  });


  var timeout;
  $input.on('keyup', function (event) {
    var value = event.target.value;
    if (value) {

      if (timeout) {
        clearTimeout(timeout);
      }

      timeout = setTimeout(function () {
        getResultsWrapper().remove();

        $.get($form.attr('action') + '?s=' + value, function (data, err) {
          $context.append(data);
          // console.info('slick');
          $context.find('.medico-card-slick').slick({
            dots: true,
            arrows: false,
            slidesToShow: 1,
            margin: 20,
            centerPadding: '20px',
            infinite: false,
          });

        });

      }, 500);
    } else {
      getResultsWrapper().remove();
    }
  });


});
