/*
 * jQuery liHarmonica v 2.0
 *
 * Copyright 2013, Linnik Yura | LI MASS CODE | http://masscode.ru
 * http://masscode.ru
 * Free to use
 * 
 * Last update 21.06.2014
 */
(function($){
	$.fn.liHarmonica = function(params){
		var p = $.extend({
			currentClass:'cur',	//Класс для выделенного пункта меню
			currentFirst:true,	//Если true и не стоит класс активности "cur", то активной будет первая ссылка, если false, меню будет акрыто
			onlyOne:true,		//true - открытым может быть только один пункт, 
								//false - число открытых одновременно пунктов не ограничено
			speed:500			//Скорость анимации
		}, params);
		return this.each(function(){
			var 
			el = $(this).addClass('harmonica'),
			linkItem = $('ul',el).prev('a').addClass('harClose').removeClass('harOpen');
			el.children(':last').addClass('last');
			$('ul',el).addClass('harContentClose').prev('a').addClass('harFull');
			
			
			
			
			
			var curEl = $();
			if(el.find('.'+p.currentClass).length){
				curEl = el.find('.'+p.currentClass);
			}else{
				
				
				
				if(p.currentFirst){
					curEl = $('a:first',el);
				}	
			}
			
			
			if(curEl.length){
				curEl.addClass(p.currentClass).addClass('harOpen').removeClass('harClose').next('ul').removeClass('harContentClose');
				curEl.parents('ul').removeClass('harContentClose').prev('a').addClass(p.currentClass).addClass('harOpen').removeClass('harClose');
			}
			
			linkItem.on('click',function(){
				var linkItemEl = $(this);
				if(linkItemEl.next('ul').is('.harContentClose')){
					linkItemEl.addClass('harOpen').removeClass('harClose').next('ul').removeClass('harContentClose');
					if(p.onlyOne){
						var contEl = linkItemEl.closest('ul').closest('ul').find('ul').not(linkItemEl.next('ul')).addClass('harContentClose');
						setTimeout(function(){
							contEl.prev('a').addClass('harClose').removeClass('harOpen');	
						},p.speed);
					}
				}else{
					linkItemEl.next('ul').addClass('harContentClose');
					setTimeout(function(){
						linkItemEl.addClass('harClose').removeClass('harOpen');
					},p.speed);
				}
				return false;	
			});
		});
	};
})(jQuery);