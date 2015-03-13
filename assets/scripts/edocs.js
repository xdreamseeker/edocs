jQuery(document).ready(function($) {

	$('table').wrap('<div class="table-responsive"></div>');
	$('table').addClass('table table-bordered');
  	$('[data-toggle="tooltip"]').tooltip();
  	$('img').addClass("img-responsive");
 
	$("html.touch body").swipe({
		fingers: 1,
		allowPageScroll: 'auto',
		swipe:function(event, direction, distance, duration, fingerCount){
			if(direction == 'right' && $('a.next-page').length) {
				window.location.href = $('a.next-page').attr('href');
			}
			if(direction == 'left' && $('a.prev-page').length) {
				$("a.prev-page").trigger("click");
				window.location.href = $('a.prev-page').attr('href');
			}
		}
	});
 
	// WEB APP LINK BEHAVIOUR
	(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone") 
  
    if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
        var msViewportStyle = document.createElement('style')
        msViewportStyle.appendChild(document.createTextNode('@-ms-viewport{width:auto!important}'))
        document.querySelector('head').appendChild(msViewportStyle)
    }
    
    // CONTRAST COOKIE
    if ($.cookie("edocs_contrast")) {
        $("link#contrast").attr("href", $.cookie("edocs_contrast"));
    }
    
    $("td.contrast a").click(function() {
        $("link#contrast").attr("href", $(this).attr('rel'));
        $.cookie("edocs_contrast", $(this).attr('rel'), {
            expires: 365,
            path: '/'
        });
        return false;
    });
    
    // FONT COOKIE
    if ($.cookie('edocs_font')) {
        $('body').addClass($.cookie('edocs_font'));
    }
    $('td.font a').click(function() {
        $('body').removeClass('font_small font_medium font_large font_xlarge').addClass($(this).attr('class'));
        $.cookie('edocs_font', $(this).attr('class'), {
            expires: 365,
            path: '/'
        });
        return false;
    });
    
    // FONT COOKIE
    if ($.cookie('edocs_spacing')) {
        $('body').addClass($.cookie('edocs_spacing'));
    }
    $('td.spacing a').click(function() {
        $('body').removeClass('spacing_small spacing_medium spacing_large spacing_xlarge').addClass($(this).attr('class'));
        $.cookie('edocs_spacing', $(this).attr('class'), {
            expires: 365,
            path: '/'
        });
        return false;
    });

	// SMOOTH SCROLL
	$(function() {
	  $('a[href*=#]:not([href=#])').click(function() {
	    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
	      if (target.length) {
	        $('html,body').animate({
	          scrollTop: target.offset().top
	        }, 500);
	        return false;
	      }
	    }
	  });
	});

});