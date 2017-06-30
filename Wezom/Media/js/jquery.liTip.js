/*
* jQuery liTip v 2.0
* http://
*
* Copyright 2012, Linnik Yura
* Free to use
* 
* August 2012
*/
/*
thams:
liTipBlack
liTipWhite
liTipRed
liTipGreen
liTipBlue
liTipOrange
liTipRose
liTipBorderBlack
*/
(function($){
	$.fn.liTip = function(params){
		var params = $.extend({
			themClass: 'liTipBlack',
			timehide: 0,
			posY: 'top',
			radius: '0px',
			maxWidth: '400px',
			content: false,
			tipEvent: 'mouseenter'
		}, params);
		return this.each(function(){
			var tipTag = $(this).addClass('liTipLink').css({whiteSpace:'nowrap'}),
				wW = $(window).width(),
				wH = $(window).height(),
				themClass = params.themClass,
				timehide = params.timehide,
				maxWidth = params.maxWidth,
				posY = params.posY,
				tipFuncId = false,
				tipF = false,
				radius = params.radius,
				liTipContent = $('<div>').css({borderRadius:radius,maxWidth:maxWidth}).addClass('liTipContent liTipHide '+themClass).appendTo('body'),
				content = params.content,
				liTipClass = 'liTipPos'+posY,
				tipContent = '',
				tipTagLeft = tipTag.offset().left,
				tipTagTop = tipTag.offset().top,
				tipTagWidth = tipTag.outerWidth(),
				tipTagHeight = tipTag.outerHeight(),
				tipTagCenter = tipTagLeft + tipTagWidth/2,
				liTipInner = $('<div>').addClass('liTipInner').html(tipContentFunc()).appendTo(liTipContent),
				liTipCone = $('<div>').addClass('liTipCone').appendTo(liTipContent),
				liTipContentWidth = liTipContent.outerWidth(),
				liTipContentHeight = liTipContent.outerHeight(),
				liTipContentCenter = liTipContentWidth/2,
				coneLeft = 0;
				
			var tipEvent = params.tipEvent;
			var tipEventBack = 'mouseleave';
			if(tipEvent == 'mouseenter') tipEventBack = 'mouseleave';
			if(tipEvent == 'focus') tipEventBack = 'blur';
			
			function tipContentFunc(){
                if(tipTag.attr('title')) {
                    tipContent = tipTag.attr('title');
                    tipTag.attr('title','');
                    tipTag.attr('data-title',tipContent);
                } else if(tipTag.hasClass('btn-danger')) {
                    tipContent = tipTag.data('unpub');
                } else if(tipTag.hasClass('btn-success')) {
                    tipContent = tipTag.data('pub');
                } else {
                    tipContent = tipTag.attr('data-title');
                }
				//if(content == false){
				//	tipContent = tipTag.attr('title');
					//tipTag.attr('title','');
				//}else{
					//tipTag.attr('title','');
					//tipContent = content;
				//}
				return tipContent;
			}
			tipTag.on(tipEvent,function(e){
				reval();
				var eX = e.pageX;
				var eY = e.pageY;
				tipLeft = tipTagCenter - liTipContentCenter;
				coneLeft = 0;
				if(tipLeft < 0){
					tipLeft = 5;
					coneLeft = (tipTagCenter - liTipContentCenter) - 5;
				}
				if(tipLeft > (wW - liTipContentWidth)){
					tipLeft = (wW - (liTipContentWidth + 5));
					coneLeft = (tipTagCenter - liTipContentCenter) - (wW - (liTipContentWidth + 5));
				}
				liTipCone.css({marginLeft:coneLeft - 6 + 'px'});
				if(posY == 'top'){
					tipTop = tipTagTop - (liTipContentHeight+5);
					if(tipTop < $(window).scrollTop()){
						tipTop = (tipTagTop + tipTagHeight +5);	
						liTipClass = 'liTipPosbottom';
					}
				}
				if(posY == 'bottom'){
					tipTop = (tipTagTop + tipTagHeight +5);	
					if((tipTop + liTipContentHeight) > $(window).scrollTop() + wH){
						tipTop = tipTagTop - (liTipContentHeight+5);
						liTipClass = 'liTipPostop';
					}
				}
				liTipContent.removeClass('liTipPostop').removeClass('liTipPosbottom').addClass(liTipClass).css({left:tipLeft, top:tipTop});
				clearTimeout(tipFuncId);
				if(tipEvent == 'click'){
					return false;
				}
			}).on(tipEventBack,function(){
				tipF = function tipFunc(){
					liTipContent.css({left:'-99999px', top:'-99999px'})	;
				};
				clearTimeout(tipFuncId);
				tipFuncId = setTimeout(tipF,timehide);
			});
			/*
			liTipContent.on('mouseenter',function(){
				clearTimeout(tipFuncId);
			}).on('mouseleave',function(){
				clearTimeout(tipFuncId);
				tipFuncId = setTimeout(tipF,timehide);
			});
			*/
			
			var reval = function(){
				wW = $(window).width();
				wH = $(window).height();
				tipTagLeft = tipTag.offset().left;
				tipTagTop = tipTag.offset().top;
				tipTagWidth = tipTag.outerWidth();
				tipTagHeight = tipTag.outerHeight();
				tipTagCenter = tipTagLeft + tipTagWidth/2;
				liTipContentWidth = liTipContent.outerWidth();
				liTipContentHeight = liTipContent.outerHeight();
				liTipContentCenter = liTipContentWidth/2;
				liTipClass = 'liTipPos'+posY;	
			};
			
			$(window).on('resize scroll',function(){
				reval();
			})
		});
	};
})(jQuery);