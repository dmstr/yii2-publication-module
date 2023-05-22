function timezoneOffset(date) {
  var offset = date.getTimezoneOffset() / 60;
  if (offset >= 0) {
    offset = '+' + offset;
  }
  return offset;
}

$('[data-date-time-picker-timezone]').on('change', function () {
  var val = $(this).val();
  var infoText = $(this).siblings('.timezone-offset-info-text');
  if (val === "") {
    infoText.addClass('hidden');
  } else {
    infoText.removeClass('hidden');
    infoText.find('.timezone-offset').text(timezoneOffset(new Date($(this).val())));
  }
}).trigger('change');
