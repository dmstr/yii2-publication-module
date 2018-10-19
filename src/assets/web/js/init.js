window.addEventListener('load', function () {
  initSelectize();
  var inputsList = document.querySelectorAll("textarea[data-schemaformat='html']");
  var inputs = [].slice.call(inputsList);
  inputs.forEach(function (input) {
    CKEDITOR.replace(input, CKCONFIG);
    CKEDITOR.instances[input.name].on('change', function () {
      var ckvalue =
        input.value = CKEDITOR.instances[input.name].getData();
      var evt = document.createEvent("HTMLEvents");
      evt.initEvent("change", false, true);
      input.dispatchEvent(evt);
    });
  })
});

// create filepicker, with api endpoint search
function initSelectize() {
  var fileflies = $('input[type="filefly"]');
  fileflies.selectize({
    valueField: 'path',
    labelField: 'path',
    searchField: 'path',
    placeholder: 'Select a file...',
    maxItems: 1,
    preload: true,
    options: [],
    create: false,
    render: {
      item: function (item, escape) {
        console.log(escape);
        return '<div class="" style="height: 70px">' +
          '<img class="pull-left img-responsive" style="max-width: 100px; max-height: 70px" src="/filefly/api?action=stream&path=' + (item.path) + '" />' +
          '<span class="">' + escape(item.path) + '</span><br/>' +
          '</div>';
      },
      option: function (item, escape) {
        return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
          '<img class="img-responsive" style="max-height: 100px" src="/filefly/api?action=stream&path=' + (item.path) + '" />' +
          '<span class="">' + escape(item.path) + '</span>' +
          '</div>';
      }
    },
    load: function (query, callback) {
      $.ajax({
        url: '/filefly/api',
        type: 'GET',
        dataType: 'json',
        data: {
          action: 'search',
          q: query,
          page_limit: 20
        },
        error: function (e) {
          console.log(e);
          alert('Your request could not be processed, see log for details.');
        },
        success: function (data) {
          callback(data);
        }
      });
    }
  });

  // update JSON content when selectized inputs change.
  fileflies.on('change', function () {
    for (var name in content_widget_jsonEditor.editors) {
      content_widget_jsonEditor.editors[name].refreshValue();
      content_widget_jsonEditor.editors[name].onChange(true);
    }
  });
}
