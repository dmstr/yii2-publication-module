$(function() {
  var targetList = $('[data-sortable="target-list"]');
  $( '.list-group').sortable({
    connectWith: '.list-group',
    revert: 150,
    scroll: false,
    tolerance: 'pointer',
    placeholder: 'placeholder',
    items: '.list-group-item',
    sort: function(event, ui) {
      ui.item.addClass('active');
    },
    stop: function(event, ui) {
      var tagIds = [];
      ui.item.removeClass('active');
      targetList.find('.list-group-item').each(function() {
        tagIds.push($(this).data('key'));
      });
      $.post($('span[data-url]').data('url'),{tagIds: tagIds,itemId: $('span[data-item-id]').data('item-id')},function(resp) {
        if (resp === '1') {
          setTimeout(function() {
            ui.item.removeClass('list-group-item-success').addClass('list-group-item-info');
          }, 500);
          if (tagIds.length === 0) {
            location.reload();
          }
        } else {
          ui.item.addClass('list-group-item-danger');
        }
      });

    }
  }).disableSelection();
});