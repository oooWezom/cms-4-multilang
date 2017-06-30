/*
 * jQuery liTranslit v 1.2
 * http://masscode.ru/index.php/k2/item/28-litranslit
 *
 * Copyright 2013, Linnik Yura
 * Free to use
 * 
 * Last Update 02.03.2014
 */

(function ($) {
    var methods = {
        init: function (options) {
			var o = {
				qeOpen: function (el, text) {},   
				qeClose: function (el, text) {} 
			};
			if (options) {
				$.extend(o, options); 
			}
			
			return this.each(function(){			
				
				var qeEl = $(this);
		
				qeEl.wrapInner('<span id="qeContent" class="qeContent"></span>');
				var qeElWidth = qeEl.outerWidth();
				var qeElHeight = qeEl.outerHeight();
				
				var qeContent = $('.qeContent',qeEl);
				
				
				var eqCloseEvent = function(closeEl){
					var newText = closeEl.val();
					closeEl.remove();
					qeContent.text(newText);
					
					if (o.qeClose !== undefined) {
						o.qeClose(qeEl, newText);
					}	
				};
				
				
				qeEl.on('mouseenter',function(){
					$('<i>').addClass('qeIcon fa-pencil').appendTo(qeContent);	
				}).on('mouseleave',function(){
					$('qeIcon').remove();	
				}).on('click',function(){
					
					
		
					
					var qeText = $.trim(qeContent.text());
					
					if (o.qeOpen !== undefined) {
						o.qeOpen(qeEl, qeText);
					}
					
					var qeInput = $('<textarea>').attr({
						'type' : 'text'
					})
						.val(qeText)
						.addClass('qeInput form-control')
						.css({left:qeContent.offset().left, top:qeContent.offset().top, width:qeContent.width()+22, minHeight:qeContent.height()+16, fontSize:qeEl.css('font-size'), linrHeight:qeEl.css('line-height')/* height:qeElHeight*/})
						.appendTo('body');
					
					qeInput.focus();
					qeInput.select();
					qeInput.on('blur',function(){
						eqCloseEvent(qeInput)
					}).on('keydown',function(e){
						if(e.keyCode == 13){
							eqCloseEvent(qeInput);
							return false;
						}
					})
		
				})
			
		
			})
		},disable: function () {
			$(this).data({
				status:false	
			})
		},enable: function () {
			$(this).data({
				status:true	
			})
		}
	};
    $.fn.liQuickEdit = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Метод ' + method + ' в jQuery.liQuickEdit не существует');
        }
    };
})(jQuery); 