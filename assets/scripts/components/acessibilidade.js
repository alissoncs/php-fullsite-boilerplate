var $acessibilidade = $('.acessibilidade');

var contrast = document.createElement('link');
contrast.setAttribute('rel', 'stylesheet');
contrast.setAttribute('type', 'text/css');
contrast.setAttribute('href', HIGH_CONTRAST_CSS);

if (localStorage && localStorage.getItem('contrast')) {
  $('body').append(contrast).addClass('high-contrast');
}

$acessibilidade.find('.btn-contrast').on('click', function(event) {
  event.preventDefault();

  if ($('body').hasClass('high-contrast')) {
    $(contrast).remove();
    $('body').removeClass('high-contrast');
    localStorage && localStorage.removeItem('contrast');
  } else {
    localStorage && localStorage.setItem('contrast', 1);
    $('body').append(contrast).addClass('high-contrast');
  }

  return false;
});

var currentFontSize = 16;
var maxFontSize = 28;
var minFontSize = 8;

$acessibilidade.find('.btn-font-less').on('click', function(event) {
  event.preventDefault();

  if (currentFontSize === minFontSize) {
    return false;
  }

  $('body').removeClass('font-size-' + currentFontSize + 'px');
  currentFontSize -= 2;
  $('body').addClass('font-size-' + currentFontSize + 'px');

  return false;
});


$acessibilidade.find('.btn-font-more').on('click', function(event) {
  event.preventDefault();

  if (currentFontSize === maxFontSize) {
    return false;
  }

  $('body').removeClass('font-size-' + currentFontSize + 'px');
  currentFontSize += 2;
  $('body').addClass('font-size-' + currentFontSize + 'px');

  return false;
});

