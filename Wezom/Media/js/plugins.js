//Cufon.replace('.font_1', {hover:true});

$(document).ready(function(){
	
	//start colorpicker
	$('.statusColor').each(function(){
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
	
	//start spinner
	var sumRecount = function(spinEl,val){
		var spinTr = spinEl.closest('tr');
		var spinCost = $('.tableOrderItemsCost',spinTr);
		var spinSumm = $('.tableOrderItemsSumm',spinTr);
		
		spinSumm.text(parseFloat(spinCost.text()) * val);	
	};
	$('.tableOrderItems .spinner').each(function(){
		sumRecount($(this),$(this).val());
	});
	$('.tableOrderItems .spinner').spinner({
		spin: function( event, ui ) {	
			sumRecount($(this),ui.value);
		},
		change: function( event, ui ) {	
			sumRecount($(this),$(this).val());
		}	
	});
	//end spinner
	
	//start data tables
	
	//plug dd.mm.yyyy
	$.extend(jQuery.fn.dataTableExt.oSort, {
		"date-uk-pre": function (a) {
			var x;
			try {
				var dateA = a.replace(/ /g, '').split(".");
				var day = parseInt(dateA[0], 10);
				var month = parseInt(dateA[1], 10);
				var year = parseInt(dateA[2], 10);
				var date = new Date(year, month - 1, day);
				x = date.getTime();
			}
			catch (err) {
				x = new Date().getTime();
			}

			return x;
		},

		"date-uk-asc": function (a, b) {
			return a - b;
		},

		"date-uk-desc": function (a, b) {
			return b - a;
		}
	});
	//plug
	
	
	if($('.table-data:not(.orderList)').length){
		$('.table-data').dataTable({
			stateSave: true,
			"language": {
				"url": "js/Russian.json"
			},
			sDom: "<'rowSection'<'dataTables_header clearfix '<'col-md-6'l><'col-md-6'f>r>>t<'rowSection'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
			iDisplayLength: 10,
			fnDrawCallback: function () {

				var o = $(this).closest(".dataTables_wrapper").find("div[id$=_filter] input");
				if (o.parent().hasClass("input-group")) {
					return
				}
				o.addClass("form-control");
				o.wrap('<div class="input-group"></div>');
				o.parent().prepend('<span class="input-group-addon"><i class="fa-search"></i></span>')
			}
		});
	}
	var orderList = $('.orderList').dataTable({
		stateSave: false,
		"language": {
			"url": "js/Russian.json"
		},
		sDom: "<'rowSection'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'f>r>>t<'rowSection'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
		"aoColumnDefs": [{
			"bSortable": false,
			"aTargets": [ 0, 8]
		},
		{ "sType": "date-uk", "aTargets": [4] }
		],
		iDisplayLength: 10,
		fnDrawCallback: function () {

			var o = $(this).closest(".dataTables_wrapper").find("div[id$=_filter] input");
			if (o.parent().hasClass("input-group")) {
				return
			}
			o.addClass("form-control");
			o.wrap('<div class="input-group"></div>');
			o.parent().prepend('<span class="input-group-addon"><i class="fa-search"></i></span>')
		},
		"order": [[ 4, "desc" ]]
	});


	var table_perepiski = $('.table_perepiski').dataTable({
		stateSave: false,
		"language": {
			"url": "js/Russian.json"
		},
		sDom: "<'rowSection'<'dataTables_header clearfix'<'col-md-6'l><'col-md-6'f>r>>t<'rowSection'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
		"aoColumnDefs": [{
			"bSortable": false,
			"aTargets": [5]
		},
		{ "sType": "date-uk", "aTargets": [4] }
		],
		iDisplayLength: 10,
		fnDrawCallback: function () {

			var o = $(this).closest(".dataTables_wrapper").find("div[id$=_filter] input");
			if (o.parent().hasClass("input-group")) {
				return
			}
			o.addClass("form-control");
			o.wrap('<div class="input-group"></div>');
			o.parent().prepend('<span class="input-group-addon"><i class="fa-search"></i></span>')
		},
		"order": [[ 4, "desc" ]]

	});
	
	
	$(window).on('resize', function () {
		if(table_perepiski.length){
			table_perepiski.fnAdjustColumnSizing();
		}

	});
	
	//end data tables
	
	
	//start iphone check
	$('.switch:checkbox').iphoneStyle({
		resizeContainer: true,
        resizeHandle: true,
		checkedLabel: 'Вкл',
        uncheckedLabel: 'Выкл',
		onChange: function(elem, value) { 
        	
			if(elem.is('.fieldToogle')){
				var fieldToogle = elem.data('toogle');
				var toggleEl = $(fieldToogle);
				if(value){
					toggleEl.show();
				}else{
					toggleEl.hide();
				}	
			}
			
        }
	});
	$('.switch',$('label')).on('click',function(){
		return false;
	});
	//end iphone check
	
	//start tabs
	if($('.liTabs').length){
		$('.liTabs').liTabs({
			duration: 300, // A string or number determining how long the animation will run
			effect:'clasic' //clasic, fade, hSlide, vSlide
		});
	}
	//end tabs
	
	//start accordion
	$('.anyClass').liHarmonica({
		onlyOne:false,
		speed:200,
		currentFirst:true
	});
	//end accordion
	
	//start datapicker

	$('.datepicker').datepicker({
		showOtherMonths: true,
		selectOtherMonths: false,
		dateFormat: 'dd.mm.y'
	});
	$('.datepicker').datepicker('option', $.datepicker.regional['ru']);
	$('.datepicker').each(function() {    
		$(this).datepicker('setDate', $(this).val());
	});

	//end datapicker
	
	
	//start tooltip
	if($('.tip').length){
		$('.tip').liTip();
	}
	
	$('.bs-focus-tooltip').liTip({
		tipEvent: 'focus'	
	});
	
	$('.bs-tooltip').liTip();
	
	$(document).on('mouseenter','.bs-tooltip',function(){
		if(!$(this).is('.liTipLink')){
			$(this).liTip();
			$(this).trigger('mouseenter');
		}
	});
	
	//end tooltip
	
	//start datapickerrange
	if($('.range').length){
		var cb = function(start, end, label) {
			//console.log(start.toISOString(), end.toISOString(), label);
			$('.range span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
		  };
	
		  var optionSet1 = {
			// startDate: moment().subtract('days', 29),
			endDate: moment(),
//			minDate: '01/01/2012',
//			maxDate: '12/31/2014',
			dateLimit: { days: 60 },
			showDropdowns: true,
			showWeekNumbers: true,
			timePicker: false,
			timePickerIncrement: 1,
			timePicker12Hour: true,
			ranges: {
			   'Сегодня': [moment(), moment()],
			   'Вчера': [moment().subtract('days', 1), moment().subtract('days', 1)],
			   'Последние 7 дней': [moment().subtract('days', 6), moment()],
			   'Последние 30 дней': [moment().subtract('days', 29), moment()],
			   'Текущий месяц': [moment().startOf('month'), moment().endOf('month')],
			   'Прошлый месяц': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
			},
			opens: 'left',
			buttonClasses: ['btn btn-default'],
			applyClass: 'btn-small btn-primary',
			cancelClass: 'btn-small',
			format: 'DD.MM.YYYY',
			separator: ' to ',
			locale: {
				applyLabel: 'Применить',
				cancelLabel: 'Отмена',
				fromLabel: 'От',
				toLabel: 'До',
				customRangeLabel: 'Выбрать период',
				daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб'],
				monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
				firstDay: 1,
				weekLabel: 'Нед',
			}
		  };
			
		  
		  // $('.range span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
	
		  $('.range').daterangepicker(optionSet1, cb);
	
	
	
			$('.range').on('show.daterangepicker', function() { 
				//console.log("show event fired"); 
				$('.dropdown').removeClass('dropdownMenuOpen');
			});
			$('.range').on('hide.daterangepicker', function() { 
				//console.log("hide event fired"); 
			});
			$('.range').on('cancel.daterangepicker', function(ev, picker) { 
				//console.log("cancel event fired"); 
			});
			$('.range').on('apply.daterangepicker', function(ev, picker) { 
				/*console.log("apply event fired, start/end dates are " 
				+ picker.startDate.format('MMMM D, YYYY') 
				+ " to " 
				+ picker.endDate.format('MMMM D, YYYY')
				); */
				var uri = $('#ordersToolbar').data('uri');
				$.ajax({
					url: '/wezom/ajax/getURI',
					type: 'POST',
					dataType: 'JSON',
					data: {
						from: picker.startDate.format('DD.MM.YYYY'),
						to: picker.endDate.format('DD.MM.YYYY'),
						uri: uri
					},
					success: function(data){
						if( data.success ) {
                            window.location.href = data.uri;
                        }
					}
				});
			});
			
			$('.rangeOrderList').on('apply.daterangepicker', function(ev, picker) {

				/*
				console.log("apply event fired, start/end dates are " 
				+ picker.startDate.format('MMMM D, YYYY') 
				+ " to " 
				+ picker.endDate.format('MMMM D, YYYY')
				);
				refreshBlock($('body'),1000);*/
			});
		 
	
	
	
		  $('#options1').click(function() {
			$('.range').data('daterangepicker').setOptions(optionSet1, cb);
		  });
	
		  $('#options2').click(function() {
			$('.range').data('daterangepicker').setOptions(optionSet2, cb);
		  });
	
		  $('#destroy').click(function() {
			$('.range').data('daterangepicker').remove();
		  });
	  
	 }
	//end datapickerrange
	
	
	
	
	if($('.validat').length){
		$('.validat').liValidForm({
			captcha: false,
			row: 'form-group', //form str selector 
			label: 'control-label', //form str selector 
		}) 
	}
	
	
	//start popup
	$('.popup-modal').magnificPopup({
		type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom',
		callbacks: {
			open: function() {	
				if(this.currItem.el.attr('title')){
					this.content.prepend( $('<div>').attr('id','cboxTitle').html(this.currItem.el.attr('title')))	
				}
			},
			close: function() {
				$('#cboxTitle',this.content).remove();
			}
		}
	});	
	
	//start popup support
	$('.supportContent').magnificPopup({
		type: 'ajax',
		alignTop: true,
		closeBtnInside: true,
		overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
	}); 
	
	
	//alert
	
	(function ($) {
		var methods = {
			init: function (options) {
				var o = {
					message:'',
					openCallback: function(){},
					closeCallback: function(){},
					headerCOntent: false,
					footerContent: false,
					typeMessage: false
				};
				if (options) {
					$.extend(o, options);
				}
	
				return this.each(function () {
					
					var message = o.message,
						openCallback = o.openCallback,
						closeCallback = o.closeCallback,
						headerCOntent = o.headerCOntent,
						footerContent = o.footerContent,
						typeMessage = o.typeMessage;
						
					var headerContentFunc = function(hc){	
						if(hc){
							return '<div class="modal-header"><h4 class="modal-title">'+hc+'</h4></div>'
						}else{
							return ''	
						}
					};
					var footerContentFunc = function(fc){	
						if(fc){
							return '<div class="modal-footer">'+fc+'</div>'
						}else{
							return ''	
						}
					};
					if(typeMessage){
						typeMessage = 'alert alert-'+typeMessage;	
					}else{
						typeMessage = '';
					}
					
					$.magnificPopup.open({
						type: 'inline',
						midClick: true,
						removalDelay: 100,
						fixedContentPos: false,
						fixedBgPos: false,
						mainClass: 'my-mfp-slide-bottom mfp-rb',
						items: [{
							type: 'inline',
							src: 
							
							'<div class="zoom-anim-dialog modal fade"><div class="modal-dialog"><div class="modal-content">'+
							headerContentFunc(headerCOntent)
							+'<div class="modal-body '+ typeMessage +'">'+message+'</div>'+  
							footerContentFunc(footerContent)
							+'</div></div></div>'		
						}],
						callbacks: {
							open: function() {	
								//this.currItem.el
								//this.content
								openCallback()
							},
							close: function() {
								//this.currItem.el
								//this.content
								closeCallback()
							}
						}
					});	
				
				})
			
		}};
		$.fn.alert2 = function (method) {
			if (methods[method]) {
				return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
			} else if (typeof method === 'object' || !method) {
				return methods.init.apply(this, arguments);
			} else {
				$.error('Метод ' + method + ' в jQuery.alert2 не существует');
			}
		};
	})(jQuery);	
	
	
	//end popup
	
	//start scroll
	if($('.mCustomScrollbar').length){
		$('.mCustomScrollbar').mCustomScrollbar({
			scrollInertia:50,
			scrollbarPosition:"outside",
			mouseWheel:{
				enable:true,
				scrollAmount:20,
				axis:"y",
				preventDefault:false,
				deltaFactor:"auto",
				normalizeDelta:false,
				invert:false
			}
		});
	}
	//end scroll
	
	
	//start nav left
	var navLeft = $('.navLeft');
	var subMenuCur = $('.cur').eq(0);
	$('.subMenu',navLeft).each(function(){
		$(this).prev('a').addClass('subToggle');
	});
	
	
	
	
	$(document).on('click','.subToggle',function(){
		var subToggle = $(this);
		var subMenu = subToggle.next('ul');
		var subParent = subToggle.closest('li');
		var h = 0;
		for(var i=0; i < subMenu.children().length; i++){
			h += subMenu.children().eq(i).outerHeight();	
		}
		if(subParent.is('.subMenuOpen')){
			subMenu.css({height:0});
			subParent.removeClass('subMenuOpen');
		}else{
			subMenu.css({height:h});
			
			$('.subMenuOpen').not(subParent).removeClass('subMenuOpen').find('.subMenu').css({height:0});
			
			subParent.addClass('subMenuOpen');
		}
		setTimeout(function(){
			//api.reinitialise();
		},300);
		return false	
	});
	
	if($('.subMenu').find(subMenuCur).length){
		subMenuCur.closest('.subMenu').prev('.subToggle').addClass('cur').trigger('click');
	}
	
	//end nav left
	
	
	
	//start resizeable
	if($('.sideBar').length){
		var sideBar = $('.sideBar');
		var content = $('.contentWrap');
		var resizeable = $('.sideBar .resizeable');
		var sideBarSize = 250;
		
		var sideBarSizeFunc = function(){
			sideBar.css({width:sideBarSize});
			
			content.css({marginLeft:sideBarSize});
			resizeable.css({left:sideBarSize});
			//api.reinitialise();
		};
		
		if($.cookie('sidebar_size')){
			sideBarSize = parseFloat($.cookie('sidebar_size'));
		}
		
		sideBarSizeFunc();
		
		resizeable.draggable({ 
			axis: 'x',
			drag: function( event, ui ) {
				sideBarSize = ui.position.left;		
				sideBarSizeFunc();
				$.cookie('sidebar_size', sideBarSize);
				$(window).trigger('resize');
			}
		});
	}
	//end resizeable
	
	
	//start toggleSidebar
	var sideBar = $('.sideBar');
	if($('.toggleSidebar').length){
		/*
		if($.cookie('sidebar_close') == 1){
			$('.container').addClass('sideBarClose');	
		}
		*/
		$('.toggleSidebar').on('click',function(){
			if($('.container').is('.sideBarClose')){
				$('.container').removeClass('sideBarClose');
				sideBar.css({left:0});
				//$.cookie('sidebar_close', 0);
			}else{
				$('.container').addClass('sideBarClose');
				sideBar.css({left:-sideBar.width()});
				//$.cookie('sidebar_close', 1);
			}	
			setTimeout(function(){
				$(window).trigger('resize')
			},300);
			
			//api.reinitialise();
			return false;
		})
	}
	//end toggleSidebar
	
	/////////////////////////////////////////////////////////////////////////////
	
	// MY NESTABLE SCRIPT START
	var depth = parseInt($('#myNest').data('depth'));
	if(!depth) {
		depth = 5;
	}
	var updateOutput = function(e){
		var list   = e.length ? e : $(e.target),
			output = list.data('output');
		if($(e.target).length){
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
			} else {
				output.val('JSON browser support required for this demo.');
			}
		}
	};

	//Кнопки для сворачивания/разворачивания всех списков
	$("#nestable_list_menu").on("click", function (e) {
		var target = $(e.target),
			action = target.data('action');
		if (action === 'expand-all') {
			$('.dd').nestable('expandAll');
		}
		if (action === 'collapse-all') {
			$('.dd').nestable('collapseAll');
		}
		return false;
	});
	//Кнопки для сворачивания/разворачивания всех списков
	$("[data-action=expand-all]").on("click", function (e) {
		$('.dd').nestable('expandAll');
		return false;
	});
	$("[data-action=collapse-all]").on("click", function (e) {
		$('.dd').nestable('collapseAll');
		return false;
	});


	var depth = parseInt($('#myNest').data('depth'));
	if(!depth) {
		depth = 5;
	}
	var myUpdateOutput = function(e){
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
		if($(e.target).length){
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
			} else {
				output.val('JSON browser support required for this demo.');
			}
		}
    };
    var mySortable = function(e){
    	if( e.target.outerHTML == '<input type="checkbox">' ) {
    		return;
    	}
    	myUpdateOutput(e);
    	var json = $("#myNestJson").val();
    	var table = $('#parameters').data('table');
    	$.ajax({
    		url: '/wezom/ajax/sortable',
    		type: 'POST',
    		dataType: 'JSON',
    		data: {
    			json: json,
    			table: table
    		},
    		success: function(data) {
    			// console.log(data);
    		}
    	});
    };

    $("#myNest").not('.pageList-del').nestable({
		dragClass: 'pageList dd-dragel',
		itemClass: 'dd-item',
        group: 1,
		maxDepth: depth
    }).on("change", mySortable);
    myUpdateOutput($("#myNest").data("output", $("#myNestJson")));
    // MY NESTABLE SCRIPT END
	
});