window.addEventListener('load', function () {
    initSelectize();
    var inputsList = document.querySelectorAll("textarea[data-schemaformat='html']");
    var inputs = [].slice.call(inputsList);
    inputs.forEach(function (input) {
        console.log(input.name);
        CKEDITOR.replace(input, CKCONFIG);
        CKEDITOR.instances[input.name].on('change', function () {
            var ckvalue = CKEDITOR.instances[input.name].getData();
            input.value = ckvalue;
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            input.dispatchEvent(evt);
        });
    })
});

// FILEFLY-JS copied from widgets2-module widgets-init.js to keep module independent from widgets2 - triggers onload
function initSelectize() {
    // create filepicker, with api endpoint search
    // TODO: cleanup & refactoring
    $('input[type="filefly"]').selectize({
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
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            },
            option: function (item, escape) {
                return '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2" style="height: 150px">' +
                    '<img class="img-responsive" style="max-height: 100px" src="/filefly/api?action=stream&path=' + (item.path) + '" />' +
                    '<span class="">' + escape(item.path) + '</span>' +
                    //'<span class="badge">#' + escape(item.access_owner) + '</span>' +
                    '</div>';
            }
        },
        load: function (query, callback) {
            //if (!query.length) return callback();
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
                    console.log('selectize: success');
                    console.log(data);
                    callback(data);
                }
            });
        }
    });   
}
