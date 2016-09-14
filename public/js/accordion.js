/**
 * @package Analytical Center 2
 * @see for EN http://hack4sec.pro/wiki/index.php/Analytical_Center_2_en
 * @see for RU http://hack4sec.pro/wiki/index.php/Analytical_Center_2
 * @license MIT
 * @copyright (c) Anton Kuzmin <http://anton-kuzmin.ru> (ru) <http://anton-kuzmin.pro> (en)
 * @author Anton Kuzmin
 */

(function($){
$(document).ready(function(){

$('#report-modal li.active').addClass('open').children('ul').show();
	$('#report-modal li.has-sub>a').on('click', function(){
		$(this).removeAttr('href');
		var element = $(this).parent('li');
		if (element.hasClass('open')) {
			element.removeClass('open');
			element.find('li').removeClass('open');
			element.find('ul').slideUp(200);
		}
		else {
			element.addClass('open');
			element.children('ul').slideDown(200);
			element.siblings('li').children('ul').slideUp(200);
			element.siblings('li').removeClass('open');
			element.siblings('li').find('li').removeClass('open');
			element.siblings('li').find('ul').slideUp(200);
		}
	});

});
})(jQuery);
jQuery(document).ready(function() {
		jQuery(".notes").attr('status','open')
		jQuery(".notes").click(function(){
			jQuery('.notes-active').slideToggle('normal');
			if (jQuery(".notes").attr('status') == 'open') {
				jQuery(".notes span#notesLogo").css({'background': 'url(/images/ico-2.png) no-repeat left'});
				jQuery(".notes").css({'background': '#1f242a'});
				jQuery(".notes").attr('status','close')
			} else {
			jQuery(".notes span#notesLogo").css({'background': 'url(/images/ico-1.png) no-repeat left'});
			jQuery(".notes").css({'background': '#3d464d'});
			jQuery(".notes").attr('status','open')
			}					
		});
	});