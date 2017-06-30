/*
* jQuery liValidForm v 11.3 for jquery-1.9.0
* http://
*
* Copyright 2013, Linnik Yura
* Free to use
* 
* Last Update 02.07.2014
*/
//<form class="validat">
//<input class="valid email">
//.valid [.email, .url, .digits, data-error=""]
//.costumCaptcha (если есть елемент с таким классом, програмно каптча не создается)
//jquery 1.9.0
//jquery ui 1.10.1

(function ($) {
	var methods = {
		init: function (options) {
			var o = {
				valid: 'valid', //valid element selector 
				r: 'r', //mandatory element selector 
				row: 'row', //form str selector 
				label: 'label', //form str selector 
				noValid: 'noValid',  //noValid element selector 
				captcha: false,	//'slider', 'code' or false
				capLabel: '',
				capError: ''
			};
			if (options) {
				$.extend(o, options);
			}

			return this.each(function () {
				var form = $(this);
				var but = $('.submit',form);
				var butWrap = but.closest('.'+o.row+'');
				var capLabel;
				var capError;
				var fError = true;
				var otherBtn = $('.btn',form).not(but).addClass('otherBtn');
				//var firstFlag = true;
				
				
				$(this).data({
					'rowel':'o.row'	
				});
				
				var createCpatcha = function(){
					if(o.captcha == 'code'){
						capLabel = 'Введите код';
						capError = 'Неправильно введен проверочный код';
						if(o.capLabel){
							capLabel = o.capLabel	
						}
						if(o.capError){
							capError = o.capError	
						}
						captchaHtml = 
						'<div class="'+o.row+'">'+
							'<label class="'+o.label+'">'+capLabel+'</label>'+
							'<div class="controls">'+
								'<input class="captchaInput valid" data-error="'+capError+'" type="text" name="hislo" maxlength="4" size="4"/>'+
								'<img class="captchaPic" src="pic/securimage_show.png" id="image" />'+
								'<a class="refreshCptcha" title="Обновить изображение" href="#">update image</a>'+
							'</div>'+
						'</div>'
					}
					if(o.captcha == 'slider'){
						
						capLabel = 'Передвиньте ползунок в правый край';
						capError = 'Позиция ползунка неправильна';
						if(o.capLabel){
							capLabel = o.capLabel	
						}
						if(o.capError){
							capError = o.capError	
						}
						captchaHtml =
						'<div class="'+o.row+'">'+
							'<label class="'+o.label+'">'+capLabel+'</label>'+
							'<div class="controls">'+
								'<input type="hidden" class="amount" />'+
								'<input type="text"  class="valid captchaView" data-error="'+capError+'">'+
								'<div class="slWrap">'+
									'<div class="slider"></div>'+
								'</div>'+
							'</div>'+
						'</div>	'
					}	
					butWrap.before(captchaHtml)
				};
				if(!$('.costumCaptcha',form).length){
					createCpatcha()
				}
				
				var captchaView = $('.captchaView',form).val('');
				var amount = $('.amount',form);
				var capSlider = $('.slider',form);
				var slWrap = $('.slWrap',form).css({width:(captchaView.width()-29)+'px'});
				var fLabel = $('.'+o.label+'',form);
				var valid_form = $('.'+o.valid,form);
				var mand_el = valid_form.length; //Кол-во обязательных эл-тов
				
				var btnParent = but.parent().css({position:'relative'});
				var psevdo_but = $('<div>').addClass('psevdo_but');
				

				var servCaptcha = 9668; //offline test value
				var data = 1;
				var captchaHtml = '';
				
				var addErrorFunc = function(elError){
					if(!elError.closest('.controls').find('.errorBox').length){
						
						if(elError.is('[data-error]')){
							
							var errorText = 'Неправильно заполнено поле!';
							if(elError.data('error') != ''){
								errorText = elError.data('error')
							}
							
							
							
							var errorBox = $('<div>').addClass('errorBox').hide().html(errorText).appendTo(elError.closest('.controls'));
							errorBox.slideDown();
							
						}
					}
				};
				var removeErrorFunc = function(elError){
					elError.closest('.controls').find('.errorBox').slideUp(function(){
						$(this).remove();	
					});	
				};
			
				//email
				function ValidEmail(emailAddress) {
					var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
					return pattern.test(emailAddress);
				}
		
				//url
				function ValidUrl(urlAddress) {
					var pattern = new RegExp(/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i);
					return pattern.test(urlAddress);
				}
				
				//digits
				function ValidDigits(dig) {
					var pattern = new RegExp(/^\d+$/);
					return pattern.test(dig);
				}	
				
				//radio
				function ValidRadio(el_r) {
					var el_name = el_r.attr('name');
					var check = el_r.closest("form").find('[name='+el_name+']').filter(':checked').length;
					if(check>0){
						check_el = true	
					}else{
						check_el = false	
					}
					return check_el
				}	
				
				
				var add_w = function (el_f){
					el_f.addClass('wrong');
					if (el_f.closest('.t_content').length) {
						validTab(el_f, el_f.closest('.t_content'), true);
					}
				};
				var remove_w = function (el_f){
					el_f.removeClass('wrong');
					if (el_f.closest('.t_content').length) {
						if (el_f.data('check')) {
							console.log('ok');
							validTabCheck(el_f, false);
						}
						validTab(el_f, el_f.closest('.t_content'), false);
					}
				};
				var add_c = function (el_f){
					if(fError){
						if(el_f.is('.fieldSuccess')){
							el_f.removeClass('fieldSuccess');
							el_f.closest('.'+o.row).removeClass('rowSuccess');
						}
						el_f.addClass('fieldError');	
						el_f.closest('.'+o.row).addClass('rowError');
						addErrorFunc(el_f);
						if (el_f.data('check')) {
							validTabCheck(el_f, true);
						}
					}
				};
				var remove_c = function (el_f){
					if(el_f.data('highlight')){
						el_f.addClass('fieldSuccess');
						el_f.closest('.'+o.row).addClass('rowSuccess');
					}
					el_f.removeClass('fieldError');
					el_f.closest('.'+o.row).removeClass('rowError');
					removeErrorFunc(el_f);
				};
				
				
				var wrong = function(){
					var wrong_el = $('.wrong',form).length; //Кол-во незаполненых эл-тов
		
					if(wrong_el > 0){
						but.addClass('disabled').css({opacity:'0.5'});
						if(!$('.psevdo_but',form).length){
							but.after(psevdo_but);
							
							psevdo_but.on('click',function(){
								valid_form.not('.'+o.noValid).each(function(){
									valider($(this),$(this).val())
								});
								return false;
							})
						}
						
					}else{
						but.removeClass('disabled').css({opacity:'1'});
						$('.psevdo_but',form).remove()
					}
				};
				
				var valider = function (z_el_form,z_el_val){
					
					var z_el_val = $.trim(z_el_form.val());
				
					if(z_el_val != ''){
						
						
						
						if(z_el_form.is(':not(".email")') && z_el_form.is(':not(".url")') && z_el_form.is(':not(".digits")') && z_el_form.is(':not("[name=hislo]")') && z_el_form.is(':not("[type=radio]")') && z_el_form.is(':not("[type=checkbox]")') && z_el_form.is(':not("select")' )){
							
							if(z_el_form.next('.fakefile').length){
								remove_w(z_el_form.next('.fakefile').find('input'));	
								remove_c(z_el_form.next('.fakefile').find('input'));
							}else{
								remove_w(z_el_form);
								remove_c(z_el_form);
							}
						}else{

							//if select
							if(z_el_form.is('select')){
								if(!z_el_form.find('option:selected').is('.placeholder')){
									remove_w(z_el_form);
									remove_c(z_el_form);
								}else {
									
									if(!z_el_form.data('highlight')){
										z_el_form.data({'highlight':true});
										add_w(z_el_form);
									}else{
										add_w(z_el_form);
										add_c(z_el_form);
									}
								}	
							}
							
							//if email
							if(z_el_form.is('.email')){
								if(ValidEmail(z_el_val)){
									
									remove_w(z_el_form);
									remove_c(z_el_form);
								}else {
									add_w(z_el_form);
									add_c(z_el_form);
								}	
							}
							//if url
							if(z_el_form.is('.url')){
								if(ValidUrl(z_el_val)){
									remove_w(z_el_form);
									remove_c(z_el_form);
								}else {
									add_w(z_el_form);
									add_c(z_el_form);
								}	
							}
							//if digits
							if(z_el_form.is('.digits')){
								
								
								
								if(ValidDigits(z_el_val)){
									remove_w(z_el_form);
									remove_c(z_el_form);
								}else {
									add_w(z_el_form);
									add_c(z_el_form);
								}	
							}
							
							//if radio
							if(z_el_form.is('[type=radio]') || z_el_form.is('[type=checkbox]')){
								if(ValidRadio(z_el_form)){
									$('[name='+z_el_form.attr('name')+']',form).each(function(){
										//$(this).removeClass('wrong').removeClass('fieldError');
										remove_w($(this));
										remove_c($(this));
										removeErrorFunc($(this));
									})
								}else {
									if(!z_el_form.data('highlight')){
										z_el_form.data({'highlight':true});
										$('[name='+z_el_form.attr('name')+']',form).each(function(){
											//$(this).addClass('wrong');
											add_w($(this));
										})
									}else{
										$('[name='+z_el_form.attr('name')+']',form).each(function(){
											//$(this).addClass('wrong').addClass('fieldError');
											add_w($(this));
											add_c($(this));
											addErrorFunc($(this));
										})
									}
									
								}	
							}
							
							
							//if captcha
							if(z_el_form.is('.captchaInput')){
								if(o.captcha){
		
										if (o.captcha == 'code' && z_el_val.length==4) {
											
											/*
											$.get("/ajax/check_captcha.php?num="+z_el_val, function(data){
											*/	
											
												if(data == 1){
													z_el_form.removeClass('wrong');	
													z_el_form.removeClass('fieldError');
													removeErrorFunc(z_el_form);
												}else{
													z_el_form.addClass('wrong');	
													z_el_form.addClass('fieldError');
													addErrorFunc(z_el_form);
												}
												wrong();
											/*
											});	
											*/		
										}else {
											add_w(z_el_form);
											add_c(z_el_form);
										}
		
								}
							}
							
							
						}
					}else {
						
						
						
						if(!z_el_form.data('highlight')){
							z_el_form.data({
								highlight:true	
							});
							if(z_el_form.next('.fakefile').length){
								add_w(z_el_form.next('.fakefile').find('input'));
							}else{
								add_w(z_el_form);
							}
						}else{
							if(z_el_form.next('.fakefile').length){
								add_w(z_el_form.next('.fakefile').find('input'));
								add_c(z_el_form.next('.fakefile').find('input'));
							}else{
								add_w(z_el_form);
								add_c(z_el_form);
							}	
						}
						

					}
					z_el_form.data({
						highlight:true	
					});
					wrong()
				};
		
				var validMain = function(){
		
					fLabel.each(function(){
						$('<span>').addClass(o.r).html('&nbsp;').appendTo($(this));
					});
					$('.'+o.noValid).each(function(){
						$(this).closest('.'+o.row).find('.'+o.r).html('&nbsp;').removeClass('mand');	
					});
					
					
					valid_form.not('.'+o.noValid).on('blur',function(){
						fError = true;
						valider($(this),$(this).val())
					});
					valid_form.not('.'+o.noValid).on('keyup',function(){
						fError = false;
						valider($(this),$(this).val())
					});
					valid_form.not('.'+o.noValid).on('change',function(){
						fError = false;
						valider($(this),$(this).val())
					});
					
					
					
					
					var mySubmit = function(){
						valid_form.not('.'+o.noValid).each(function(){
							valider($(this),$(this).val());
						});
						if (form.find('.valid.wrong').size()==0){
							form.trigger('submit');
						}	
					};
					
		
					but.on('click',function(){
						mySubmit();
						return false;
					});
					
					$('*',form).on('keydown',function(e){
						if(e.keyCode == 13 && !$(e.target).is('textarea')){
							fError = true;
							//but.trigger('click');
							return false;
						}
					});
					
					
					
					valid_form.not('.'+o.noValid).each(function(){
						var el_form = $(this);
						el_form.closest('.'+o.row).find('.r').html('&nbsp;*').addClass('mand');
						
						
						//firstFlag = true;
						el_form.data({
							highlight:false	
						});
						
						valider(el_form,el_form.val());
						
					});
					
					
					
					
					wrong()
					
					
				};
				
				var captchaTest = function(ui){
					var uiHandleLeft = $(ui.handle).attr('style').split('%')[0].split(' ')[1];
					var hideVal = amount.val();
					if(uiHandleLeft == 100 && hideVal == servCaptcha){
						remove_w(captchaView);
						remove_c(captchaView);
						captchaView.val('Вы прошли проверку');
						valider(captchaView,captchaView.val());			
					}else{
						add_w(captchaView);
						add_c(captchaView);
						captchaView.val('');
						valider(captchaView,captchaView.val());
					}	
				};
				var sliderIni = function(){
					capSlider.slider({
						value:0,
						min:0,
						max:servCaptcha,
						step:0.1,
						slide:function(event,ui){
							amount.val(ui.value);
						},
						stop:function(event,ui){
							captchaTest(ui);
						}
					});
					amount.val(capSlider.slider('value'));	
				};
				if(o.captcha){
					if(o.captcha == 'slider'){
						/*
						$.ajax({
							url: "testCaptcha.html", //Здесь надо генерировать число от 1000 до 9999 и передавать его обратно
							cache: false,
							success: function(data){
								servCaptcha = data;
						*/		
								sliderIni();
								validMain();
						/*		
							}
						})
						*/
					}
					if(o.captcha == 'code'){
						validMain()
					}
				}else{
					validMain()
				}
			});
		},
		destroy: function () {
			var el = $(this);
			
			
			$('.submit',el).off('click');
			$('.psevdo_but',el).off('click').remove();
			$('*',el).off('keydown');
			$(window).off('myresize');
			$('.valid',el).off('blur');
			$('.valid',el).off('keyup');
			$('.valid',el).off('change');
			$('.r',el).remove();
			$('.captchaInput').closest('.'+el.data("rowel")+'').remove();
			$('.captchaView').closest('.'+el.data("rowel")+'').remove();
			$('.valid',el).removeClass('wrong').removeClass('fieldError');
			$('.errorBox',el).remove();
			
			
			
		}
	};

	var fillStr = 'Заполните важные поля';

	function validTab(vt_el, vt_content, vt_bool) {
		if (typeof vt_el.data('vt_c') === 'undefined') {
			vt_el.data('vt_c', vt_content[0]);
		}
		if (typeof vt_el.data('vt_i') === 'undefined') {
			vt_el.data('vt_i', vt_content.index());
		}
		if (typeof vt_el.data('vt_t') === 'undefined') {
			vt_el.data('vt_t', $(vt_content.closest('.liTabs').children('.t_item')[vt_el.data('vt_i')]));
		}
		if (typeof vt_el.data('vt_t').data('timer') === 'undefined') {
			vt_el.data('vt_t').data('timer', true);
		}
		vt_el.data('check', vt_bool);
	}

	function validTabCheck(vtc_el, vtc_bool) {
        vtc_el.data('vt_t').removeClass('vtc_anim').children('.t_link').removeAttr('data-toolt');
        if (vtc_bool) {
        	setTimeout(function() {
        		vtc_el.data('vt_t').addClass('vtc_anim').children('.t_link').attr('data-toolt', fillStr);
        	}, 10);
        }
	}

	$.fn.liValidForm = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Метод ' + method + ' в jQuery.liValidForm не существует');
		}
	};
})(jQuery);