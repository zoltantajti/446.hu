tinymce.init({
	selector: 'textarea',
	language: 'hu_HU',
	menubar: false,
	plugins: `accordion advlist anchor autolink code fullscreen image link lists media quickbars table visualblocks preview template`,
	toolbar: `fullscreen code | template | blocks | bold italic underline | alignleft aligncentre alignright alignjustify | indent outdent | image media | link | numlist bullist | visualblocks | preview`,
	content_css: '../../../assets/css/bootstrap.min.css',
    content_css_cors: true,
	image_class_list: [
		{title: 'Left', value: ''},
		{title: 'Right', value: 'float-right'}
	],
	templates: (callback) => {
		$.getJSON("rest/getTemplates", function(data){
			var templates = [];
			$.each(data, function(key,val){
				console.log(val);
				templates.push(val);
			});
			callback(templates);
		});
	},
	setup: (editor) => {
        editor.on('init', () => {
            editor.getContainer().style.transition='border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out';
        });
        editor.on('focus', () => {
            editor.getContainer().style.boxShadow='0 0 0 .2rem rgba(0, 123, 255, .25)';
            editor.getContainer().style.borderColor='#80bdff';
        });
        editor.on('blur', () => {
            editor.getContainer().style.boxShadow='';
            editor.getContainer().style.borderColor='';
        });
    }
});