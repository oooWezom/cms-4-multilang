
$(document).ready(function(){
	//start colorpicker
	$('.demo').each( function() {
		$(this).minicolors({
			control: $(this).attr('data-control') || 'hue',
			defaultValue: $(this).attr('data-defaultValue') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: $(this).attr('data-letterCase') || 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: $(this).attr('data-position') || 'bottom left',
			change: function(hex, opacity) {
				if( !hex ) return;
				if( opacity ) hex += ', ' + opacity;
				try {
					console.log(hex);
				} catch(e) {}
			},
			theme: 'bootstrap'
		});

	});
	//end colorpicker
	
	
	//start map
	var geocoder;
	var map;
	
	var codeAddress = function () {
		var address = $('#address').val();
		geocoder.geocode({
			'address': address
		}, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			} else {
				alert("Geocode was not successful for the following reason: " + status);
			}
		});
	};
	
	function initialize() {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(-34.397, 150.644);
		var mapOptions = {
			zoom: 8,
			center: latlng
		};
		map = new google.maps.Map(document.getElementById('mapWrap'), mapOptions);
		codeAddress()
		
	}
	if($('#mapWrap').length){
		google.maps.event.addDomListener(window, 'load', initialize);

	}
	
	$('#address').on('keydown',function(e){
		if(e.keyCode == 13){
			codeAddress();
			return false;
		}
	});
	
	$('.geocode').on('click',function(){
		codeAddress();
		return false	
	});
	
	//end map
	
	
	//start ref doc
	$('.refreshDocument').on('click',function(){
		refreshBlock($('body'),3000);	
		return false;
	});
	//end ref doc
	
	
	//start spinner
	$('.spinner').spinner();
	//end spinner
	
	
	
	
	//start wysiwyg full
	tinymce.init({
		selector: "textarea.tinymce",
		skin : "wezom",
		language : 'ru',
		plugins: [
			"advlist autolink lists link image charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars code fullscreen",
			"insertdatetime media nonbreaking save table contextmenu directionality",
			"emoticons template paste textcolor colorpicker textpattern responsivefilemanager"
		],
		toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image fullscreen",
		toolbar2: "print preview media | forecolor backcolor emoticons | responsivefilemanager ",
		image_advtab: true,
		templates: [
			{title: 'Test template 1', content: 'Test 1'},
			{title: 'Test template 2', content: 'Test 2'}
		],
		external_filemanager_path:"/Wezom/Media/js/tinymce/filemanager/",
		filemanager_title:"Менеджер файлов" ,
		external_plugins: { "filemanager" : "filemanager/plugin.min.js"},
		default_language:'ru'

	});
	//end wysiwyg full
	
	//start tags
	$('#tag1').tagsInput({
		'height':'100%',
		'width':'auto',
		'defaultText':'Добавить тег'
	});

	$('#tag2').tagsInput({
		width:'100%',
		height:'auto',
		'defaultText':'Добавить тег',
		autocomplete_url:'test/fake_json_endpoint.html' // jquery ui autocomplete requires a json endpoint
	});
	//end tags
	
	
	//start autocompete
	$("#autocomplete-example").typeahead({
        name: "autocomplete-example",
        local: ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]
    });
	//end autocomplete
	
	
	
	
	
	
	//alert2(message,openCallback,closeCallbak,headrContent,footerContent)
	
	
	$('.basic-alert').on('click',function(){
		
		$(document).alert2({
			message:'Hello world!',
			openCallback: function(){
				console.log('open callback');
			},
			closeCallback: function(){
				console.log('close callback');
			},
			headerCOntent: false,
			footerContent: '<button type="button" class="btn btn-primary popup-modal-dismiss">ОК</button>',
			typeMessage: false
		});
		return false;	
	});
	
	$('.confirm-dialog').on('click',function(){
		$(document).alert2({
			message:'Вы уверены?',
			openCallback: function(){},
			closeCallback: function(){},
			headerCOntent: false,
			footerContent: '<button type="button" class="btn btn-default popup-modal-dismiss">Нет</button> <button type="button" class="btn btn-primary popup-modal-dismiss">Да</button>',
			typeMessage: false
		});
		return false;	
	});
	
	$('.multiple-buttons').on('click',function(){
		$(document).alert2({
			message:'Какой-то текст',
			openCallback: function(){},
			closeCallback: function(){},
			headerCOntent: 'Какой-то заголовок',
			footerContent: '<button type="button" class="btn btn-success popup-modal-dismiss">success</button> <button type="button" class="btn btn-danger popup-modal-dismiss">danger</button> <button type="button" class="btn btn-primary popup-modal-dismiss">Кнопка</button>',
			typeMessage: false
		});
		return false;
	});
	
	
	$('.programmatic-close').on('click',function(){
		$(document).alert2({
			message:'Это окно закроется через 3 секунды',
			openCallback: function(){
				setTimeout(function(){
					$.magnificPopup.close();	
				},3000)		
			},
			closeCallback: function(){},
			headerCOntent: 'Какой-то заголовок',
			footerContent: '<button type="button" class="btn btn-primary popup-modal-dismiss">ОК</button>',
			typeMessage: false
		});
		return false;
	});
	
	
	$('.alertWorning').on('click',function(){
		$(document).alert2({
			message:'worning alert',
			typeMessage: 'warning'
		});
		return false;
	});
	$('.alertSuccess').on('click',function(){
		$(document).alert2({
			message:'success alert',
			typeMessage: 'success'
		});
		return false;
	});
	$('.alertInfo').on('click',function(){
		$(document).alert2({
			message:'info alert',
			typeMessage: 'info'
		});
		return false;
	});
	$('.alertDanger').on('click',function(){
		$(document).alert2({
			message:'danger alert',
			typeMessage: 'danger'
		});
		return false;
	});
	
	
	$(document).on('click', '.popup-modal-dismiss', function (e) {
		//console.log('dismiss')
		e.preventDefault();
		$.magnificPopup.close();
	});

	
});