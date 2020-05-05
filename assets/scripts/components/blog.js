$('.form-newsletter form').on('submit', function (event) {
  event.preventDefault();
  var url = $(this).attr('action');
  var $form = $(this);

  var $btn = $(this).find('[type=submit]');

  $form.find('.success-message').hide();
  $form.find('.error-message').hide();

  var label = $btn.html();
  var labelLoading = 'Cadastrando...';
  $btn.html(labelLoading).attr('disabled', true);

  $.ajax({
    url: url,
    data: $(this).serialize(),
    method: 'POST',
  }).done(function () {
    $form[0].reset();
    $form.find('.success-message').fadeIn();
    $btn.html(label).attr('disabled', false);
    setTimeout(function () {
      $form.find('.success-message').fadeOut();
    }, 7000);
  }).fail(function () {
    $btn.html(label).attr('disabled', false);
    $form.find('.error-message').fadeIn();
  });
  return false;
});


$('.icon-likes').on('click', function (event) {
  event.preventDefault();
  var id = $(this).data('post') || $(this).data('noticia');
  var path = $(this).data('post') ? 'blog' : 'noticias';
  var $self = $(this);

  $.post(window.BASE_API_URL + '/' + path + '/likes/' + id, function (data, err) {
    if (data && data.likes) {
      $self.find('.count').html(data.likes);
    }
  });
  return false;
});


var $menuActive = $('.blog-menu').find('li.active');
if ($menuActive.length) {
  var position = $menuActive.position().left;
  // console.info('item menu position', position);
  $('.blog-menu').scrollLeft(position);
}
