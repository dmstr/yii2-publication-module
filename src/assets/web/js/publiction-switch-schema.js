$("#publicationitem-publication_category_id").on('change.select2', function (e) {
    if (confirm('If you change the JSON scheme, all changes will be lost. Continue?')) {
        window.location = location.origin + location.pathname + `?PublicationItem[publication_category_id]=${$(this).val()}`
    } else {
        e.preventDefault()
    }
});