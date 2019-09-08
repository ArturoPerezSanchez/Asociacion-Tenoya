// $(document).ready(function(){
// 	$(".desliza").click(function(){
// 	$("#myTopnav").slideToggle("slow");
// 	});
// 	$("#myTopnav").css({ display: 'none' });
// });

$(document).ready(function(){
	$(".mNoche").click(function(){
		$('body').toggleClass("fondoNocturno");
		$('h1').toggleClass('tituloNocturno');
		$('h2').toggleClass('tituloNocturno2');
		$('main').toggleClass('mainNocturno');
		$('footer').toggleClass("pieNocturno");
		$('input').toggleClass("inputNocturno");
	});
}); 