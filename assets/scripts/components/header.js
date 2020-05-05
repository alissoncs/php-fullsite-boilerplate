console.info('header.js');

var $acessorapido = $('.acesso-rapido');
var $acessorapidobtn = $('.acesso-rapido-btn');

function  hideAcessoRapido() {
  if ($acessorapidobtn.hasClass('open')) {
    $acessorapidobtn.removeClass('open');
    removeBodyOverlay();
  }
}

$('.acesso-rapido-btn button').first().on('click', function(event) {
  event.preventDefault();
  event.stopPropagation();
  if ($acessorapidobtn.hasClass('open')) {
    hideAcessoRapido();
  } else {
    $acessorapidobtn.addClass('open');
    addBodyOverlay();
  }
});

$acessorapido.on('click', function(e) {
  event.stopPropagation();
});

onClickEsc(hideAcessoRapido);

onClickOut(hideAcessoRapido);

// bar
console.info('asdates');
$('.mobile-menu-bar a.access, .acesso-rapido-mobile .close').on('click', function(event) {
  event.preventDefault();
  if ($('.acesso-rapido-mobile').hasClass('visible')) {
    $('.acesso-rapido-mobile').removeClass('visible');
    $('body').removeClass('overflow');
  } else {
    $('body').addClass('overflow');
    $('.acesso-rapido-mobile').addClass('visible');
  }
  return false;
});
