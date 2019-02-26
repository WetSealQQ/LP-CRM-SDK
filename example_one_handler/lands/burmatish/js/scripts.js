$(document).ready(function() {
	
	/* scroll */
	
	$("a[href^='#']").click(function(){
		var targer = $(this).attr("href");
		var target_offset = $(targer).offset().top-$(".fixed_section").innerHeight();
		$("html, body").animate({scrollTop: target_offset+"px"});
		return false;
	});

	/* validate form */

	$(".order_form").submit(function(){
		if ($(this).find("input[name='name']").val() == "" && $(this).find("input[name='phone']").val() == "") {
			alert("Введите Ваши имя и телефон");
			$(this).find("input[name='name']").focus();
			return false;
		}
		else if ($(this).find("input[name='name']").val() == "") {
			alert("Введите Ваше имя");
			$(this).find("input[name='name']").focus();
			return false;
		}
		else if ($(this).find("input[name='phone']").val() == "") {
			alert("Введите Ваш телефон");
			$(this).find("input[name='phone']").focus();
			return false;
		}
		return true;
	});

});