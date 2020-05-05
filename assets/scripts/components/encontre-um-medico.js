console.info('encontre-um-medico');
/// doctor
function populateTipoPrestador(cidade) {
  $.get(window.BASE_API_URL + '/medicos/metadata/tipoprestador?cidade=' + cidade, function (data, err) {
    // console.log(data);
    if (data && data.tipo_prestador) {
      $('#select-tipo-prestador')
        .attr('disabled', false)
        .html('<option></option>');
      data.tipo_prestador.map(function (item) {
        var value = item.classe;
        var nome = item.descricao;
        $('#select-tipo-prestador')
          .append('<option value="' + value + '">' + nome + '</option>');
      });
      $('#select-tipo-prestador').val();
    }
    if (data && data.especialidade) {
      $('#select-especialidade')
        .attr('disabled', false)
        .html('<option></option>');
      data.especialidade.map(function (item) {
        var value = item.codigo_CBO;
        var nome = item.descricao;
        $('#select-especialidade')
          .append('<option value="' + value + '">' + nome + '</option>');
      });
      $('#select-especialidade').val();
    }
  });
}

$('#select-cidades').on('change', function (event) {
  console.info('select cidades');
  populateTipoPrestador(event.target.value);
});

$('.doctor-searcher').each(function () {
  var $context = $(this);
  var $selector = $context.find('.selector');
  var $form = $context.find('form');

  $selector.find('.btn').on('click', function (event) {
    event.preventDefault();

    $(this).addClass('active').siblings().removeClass('active');

    if ($(this).hasClass('advanced')) {
      $('input[name=tipo_busca]').val('AVANCADA');
      $context.find('.basic-panel').fadeOut(function () {
        $context.find('.advanced-panel').fadeIn();
      });
    } else {
      $('input[name=tipo_busca]').val('BASICA');
      $context.find('.advanced-panel').fadeOut(function () {
        $context.find('.basic-panel').fadeIn();
      });
      // $context.find('.advanced-panel').fadeOut();
    }

    return false;
  });

  $('input[name=documento]').on('change', function(event) {
    console.info('change', event.target.value);
    $('input[name=documento]').each(function() {
      $(this).val(event.target.value);
    });
  });

  $.get(window.BASE_API_URL + '/medicos/metadata/cidades', function (data, err) {
    if (data && data.data) {
      data.data.map(function (item) {
        var nome = item.nome
        var value = item.codigo_IBGE;
        $('#select-cidades').append('<option value="' + value + '">' + nome + '</option>');
      });
    }
  });

});


$(document).on('click', '.medico-card .btn-planos', function (event) {
  event.preventDefault();
  var $btn = $(this);
  var loadingLabel = $btn.attr('data-loading');
  var currentLabel = $btn.html();
  var url = $btn.data('http');

  $btn.attr('disabled', true).html(loadingLabel);
  $.get(url, function (data, err) {
    console.info('Resposta do prestador.php', data);
    $btn.attr('disabled', false).html(currentLabel);
  });

});


$('.medicos-results .clear-field-icon').on('click', function (event) {
  event.preventDefault();
  $('.medicos-results input[name=termo]').val('');
  $('.medicos-results form').submit();
  return false;
});

if (window.location.protocol === 'http:') {
  /// FAKE
  showPosition({
    coords: {
      latitude: -29.6857273,
      longitude: -51.1341868,
    }
  });
} else {
  $(window).on('load', getLocation);
}

function getLocation() {
  try {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition);
    } else {
      // alert('falha');
    }
  } catch (e) {
    // alert(e.message);
  }
}

function calculateDistance(lat1, lon1, lat2, lon2, unit) {
  if ((lat1 == lat2) && (lon1 == lon2)) {
    return 0;
  }
  else {
    var radlat1 = Math.PI * lat1 / 180;
    var radlat2 = Math.PI * lat2 / 180;
    var theta = lon1 - lon2;
    var radtheta = Math.PI * theta / 180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    if (dist > 1) {
      dist = 1;
    }
    dist = Math.acos(dist);
    dist = dist * 180 / Math.PI;
    dist = dist * 60 * 1.1515;
    if (unit == "K") { dist = dist * 1.609344 }
    if (unit == "N") { dist = dist * 0.8684 }
    return dist;
  }
}

function showPosition(position) {
  var userLat = position.coords.latitude;
  var userLng = position.coords.longitude;

  $('.medico-card .distance-info').each(function () {
    var self = $(this);
    var lat = self.data('lat');
    var lng = self.data('lng');
    if (lat && lng) {
      // lat = parseFloat(lat);
      // lng = parseFloat(lng);
      $(this).html(function() {
        var val = calculateDistance(userLat, userLng, lat, lng, 'K');
        if (val < 1) {
          val = $(this).data('label-less').replace('[km]', val);
        } else {
          val = $(this).data('label-more').replace('[km]', parseInt(val, 10));
        }
        return val;
      });
    }
  });
}

function refreshEvents() {
  getLocation();
}

/// carregar mais

var $btnLoadmore = $('.medicos-results .btn-load');
$btnLoadmore.on('click', function(event) {
  event.preventDefault();
  var $btn = $(this);

  if ($btn.is(':disabled')) {
    return false;
  }

  var page = $btn.attr('data-page');
  var label = $btn.html();

  $btn.attr('disabled', true).html(function() {
    return $(this).data('loading');
  });


  $.get(window.location.href + '&pagina=' + page).done(function(html) {
    $($btn.data('output')).first().append(html);
    $btn.attr('data-page', parseInt(page) + 1);
    $btn.attr('disabled', false).html(label);
    if ($btn.data('page') == $btn.data('total-pages')) {
      $btn.hide();
    }
  }).fail(function() {
    $btn.attr('disabled', false).html(label);
  });
  return false;
});

$(window).on('scroll', function() {
  // console.info( + $(window).scrollTop(), '>>',  $btnLoadmore.offset().top);
  if ($(window).scrollTop() > $btnLoadmore.offset().top - $(window).height()) {
    console.info('trigger');
    setTimeout(function() {
      $btnLoadmore.click();
      refreshEvents();
    }, 500);
  }
});

console.info('//// encontre-um-medico');

