class MCEditor {
    constructor() { }

    init = (id, plugins, toolbar) => {
        tinymce.init({
            selector: 'textarea#' + id,
            plugins: plugins,
            toolbar: toolbar
        });
    }
}

export { MCEditor };