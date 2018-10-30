window.addEventListener('load', function () {

  var trigger = function (event, element) {
    console.log('...trigger');
    setTimeout(function () {
      var e = document.createEvent("HTMLEvents");
      e.initEvent(event, false, true);
      element.dispatchEvent(e);
    }, 0);
  };

  var initCKEditor = function (input) {
    console.log('...initCKEditor');
    // CKCONFIG is defined in crud/WidgetController
    var instance = CKEDITOR.replace(input, window.CKCONFIG);
    instance.on('change', function () {
      this.updateElement();
      trigger('change', input);
    });
  };

  var initSelectizeEditor = function (input) {
    console.log('...initSelectizeEditor');
    $(input).selectize({
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
          },
          success: function (data) {
            callback(data);
          }
        });
      },
      onItemAdd: function () {
        console.log('...onItemAdd');
        trigger('change', input);
      },
      onItemRemove: function () {
        console.log('...onItemRemove');
        trigger('change', input);
      }
    });
  };

  window.jsonEditors.forEach(function (jsonEditor) {
    jsonEditor.theme.afterInputReady = function (input) {
      console.log('...afterInputReady');
      var dataAttribute = input.getAttribute('data-schemaformat');
      switch(dataAttribute) {
        case 'html':
          initCKEditor(input);
          break;
        case 'filefly':
          initSelectizeEditor(input);
          break;
      }
    };
  });

});