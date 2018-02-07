let categorySelect = $("#publicationitemtranslation-publication_category_id")

categorySelect.on('select2:selecting', function (e) {
    if (!confirm('If you change the JSON scheme, all content will be lost. Continue?')) {
        e.preventDefault()
    }
});

categorySelect.on('select2:select', function () {
    window.location = location.origin + location.pathname + `?PublicationItem[publication_category_id]=${$(this).val()}`
});