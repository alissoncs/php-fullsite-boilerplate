console.log('criacao-usuario.js');
$('.user-web-creation').each(function() {
  var $context = $(this);
  if ($context.find('.btn.ready').length) {
    $('.block-links li a', $context).click(function(event) {
      event.preventDefault();
      // alert('asdasd');
      $(this).parent().addClass('selected').siblings().removeClass('selected');
      $context
      .find('.btn.ready')
        .attr('disabled', false)
        .removeClass('disabled')
        .attr('href', $(this).attr('href'));
      return false;
    });
  }
});
