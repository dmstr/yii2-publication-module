var categorySelect = $("#publicationitem-category_id");

categorySelect.on("select2:selecting", function (e) {
  if ($(this).val() !== "") {
    if (!confirm("If you change the JSON scheme, all content will be lost. Continue?")) {
      e.preventDefault();
    }
  }
});

categorySelect.on("select2:select", function () {
  window.location = location.origin + location.pathname + "?PublicationItem[category_id]=" + $(this).val();
});