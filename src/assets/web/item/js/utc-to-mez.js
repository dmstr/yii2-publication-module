function timezoneOffset(date) {
  var offset = date.getTimezoneOffset() / 60;
  if (offset >= 0) {
    offset = '+' + offset;
  }
  return offset;
}

var relEl = $('#release-date-mez-offset');
$('#publicationitem-release_date').on('change', function () {
  var val = $(this).val();
  if (val === "") {
    relEl.parent('.mez-info').addClass('hidden');
  } else {
    relEl.parent('.mez-info').removeClass('hidden');
    relEl.html(timezoneOffset(new Date($(this).val())));
  }
}).trigger('change');

var endEl = $('#end-date-mez-offset');
$('#publicationitem-end_date').on('change', function () {
  var val = $(this).val();
  if (val === "") {
    endEl.parent('.mez-info').addClass('hidden');
  } else {
    endEl.parent('.mez-info').removeClass('hidden');
    endEl.html(timezoneOffset(new Date($(this).val())));
  }
}).trigger('change');
