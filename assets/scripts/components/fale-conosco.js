console.info('fale-conosco');

$('.fale-conosco').each(function () {
  var $context = $(this);
  var $form = $context.find('.form-element');
  var $links = $context.find('.group-links');

  $links.find('a').on('click', function (event) {
    if (!$(this).attr('href') || $(this).attr('href').length < 5) {
      event.preventDefault();
      var $clicked = $(this);
      $links.fadeOut(400, function () {
        $form.fadeIn();
        $form.find('.icon img').attr('src', $clicked.find('.icon img').attr('src'));
        $form.find('.form-element-title .h3').html($clicked.find('strong').html());
        $form.find('[name=assunto]').val($clicked.find('strong').html());
        $form.find('[name=send_to]').val($clicked.data('config-to'));
        $form.find('[name=config_to]').val($clicked.data('config-to'));
      });
      return false;
    }
  });

  $context.find('.form-element .close').on('click', function (event) {
    event.preventDefault();
    $form.fadeOut(400, function () {
      $links.fadeIn();
    });
    return false;
  });

  $form.find('form').validate({
    submitHandler: function (form) {
      var $theForm = $(form);
      var url = $theForm.attr('action');
      var $btn = $theForm.find('[type=button]');

      var loading = $btn.attr('data-loading');
      var label = $btn.html();

      $form.find('.success-message').hide();
      $form.find('.error-message').hide();

      $btn.html(loading);

      $.ajax({
        url: url,
        data: $theForm.serialize(),
        method: 'POST',
      }).done(function () {
        $btn.html(label);
        form.reset();
        $form.find('.success-message').fadeIn();
      }).fail(function () {
        $btn.html(label);
        $form.find('.error-message').fadeIn();
      });
    }
  });

  $form.find('form').on('submit', function (event) {
    event.preventDefault();

  });
});
