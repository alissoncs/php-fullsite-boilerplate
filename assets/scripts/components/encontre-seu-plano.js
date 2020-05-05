// $('.cadastro').modal
console.info('encontre-seu-plano');

$('#modal-cadastro-plano')
.modal({
  backdrop: 'static',
  keyboard: false,
}).modal('show');


$('#modal-cadastro-plano').find('form').on('submit', function(event) {
  // $('#modal-cadastro-plano').modal('hide');
});

calculateHeight();

function calculateHeight() {

  var wapper = $('.encontre-seu-plano .plan-groups').width();
  var countPlans = $('.encontre-seu-plano .plan-wrapper').length;

  if (wapper > 1000) {
    $('.encontre-seu-plano .plan-wrapper').each(function() {
      $(this).css('width', (wapper / countPlans) + 'px');
      $(this).css('max-width', '100%');
    });
  } else {
    $('.encontre-seu-plano .plan-wrapper').each(function() {
      $(this).removeAttr('style');
    });
  }

  // $('.encontre-seu-plano .benefits-content').each(function() {
  //   var $content = $(this);
  //   var height = $content.innerHeight();
  //   var innerheight = $content.children().innerHeight();
  //   // console.log(height, innerheight);
  //   if (innerheight > height) {
  //     $content.addClass('has-hidden-content');
  //   }
  // });
}

// $('.encontre-seu-plano .benefits-content').on('click', function() {
//   // alert('eee');
//   $(this).toggleClass('show-hidden-content');
// });

// $('.encontre-seu-plano .benefits-title').on('click', function() {
//   // alert('asd');
//   $(this).toggleClass('open');
//   $(this).parent().find('.benefits-content').slideToggle();
// });

// $(window).on('resize', function() {
//   $('.encontre-seu-plano .benefits-content').removeAttr('style');
//   calculateHeight();
// });

// $(window).on('load', function() {
//   $('.encontre-seu-plano').addClass('loaded');

//   $('.encontre-seu-plano .benefits-content').removeAttr('style');
//   calculateHeight();
// });

$('.encontre-seu-plano').addClass('loaded');
