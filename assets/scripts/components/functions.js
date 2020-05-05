function addBodyOverlay() {
  console.info('add body overlay');
  $('body').addClass('overflow overlay-enabled');
}

function removeBodyOverlay() {
  console.info('remove body overlay');
  $('body').removeClass('overflow overlay-enabled');
}


function onClickEsc(call) {
  $(document).keyup(function(e) {
    if (e.keyCode === 27) call(e);   // esc
  });
}

function onClickOut(call) {
  $(document).click(function(e) {
    call(e);
  });
}


$('input.mask-tel').mask('(99) 99999-9999');
$('input.mask-number').mask('999999999999999999999999999999999999');
