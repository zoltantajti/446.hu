var scripts = document.getElementsByTagName('script');var src = scripts[scripts.length - 1].src;var token = src.match(/token=[0-9]+/);
$("#filter").keyup((event) => {let input = $("#filter");let val = input.val();let regex = /\s|[^\w\s]/;if(regex.test(val)){input.addClass("is-invalid");$("#UserFilterButton").prop("disabled", true);}else{if(input.hasClass("is-invalid")){input.removeClass("is-invalid");};$("#UserFilterButton").prop("disabled", false);}})
$("#UserFilterButton").on('click', (event) => {let filter = $("#filter").val();var regex = /\s|[^\w\s]/;if(!regex.test(filter)){window.location.assign('admin/users/' + filter);$("#filter").addClass("is-invalid");alert("A szűrés nem tartalmazhat speciális karaktert!");}})