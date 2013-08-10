tinymce.init({
    selector: "textarea.tiny_full_with_image",
    width : 938,
    height: 420,
    remove_linebreaks : false,
    plugins: "code, textcolor, table, image, link, paste",
    paste_auto_cleanup_on_paste : true,
    paste_remove_styles: true,
    paste_remove_styles_if_webkit: true,
    paste_strip_class_attributes: true,
    tools: "inserttable",
    toolbar: "undo redo | styleselect | bold italic underline strikethrough | lignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code | forecolor | image | link"
});

tinymce.init({
    selector: "textarea.tiny_half",
    width : 433,
    height: 170,
    remove_linebreaks : false,
    plugins: "code, textcolor, table",
    tools: "inserttable",
    toolbar: "undo redo | styleselect | bold italic underline strikethrough | lignleft aligncenter alignright alignjustify | numlist bullist outdent indent | code | forecolor"
});