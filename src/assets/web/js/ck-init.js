
// window.addEventListener('load', function () {
//    var inputsList = document.querySelectorAll('[data-schemaformat="html"]');
//    var inputs = [].slice.call(inputsList);
//    inputs.forEach(function (input) {
//        console.log(input.name);
//        CKEDITOR.replace(input, CKCONFIG);
//        CKEDITOR.instances[input.name].on('change', function () {
//             var ckvalue = CKEDITOR.instances[input.name].getData();
//            input.value = ckvalue;
//            var evt = document.createEvent("HTMLEvents");
//            evt.initEvent("change", false, true);
//            input.dispatchEvent(evt);
//        });
//    });
// });


// triggers on load, saves values, works
window.onload = function () {
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
};

