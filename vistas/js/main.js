const select_ajax = document.querySelectorAll(".selectAjax");

function recargarLista(el){

	let action = $(el).attr("action");
	let value = $('option:selected', el).attr("value");
	let tipo = $(el).attr("name");


	$.ajax({
		type:"POST",
		url: action,
		data: "c=" + value,
		success:function(r){
			//$('#select2lista').html(r);
			/*if(tipo=='cantidad'){
				
				location.reload();
			}*/
			console.log(r);
			$('.proveerdor').html(r);
			
		}
	});
}


/*select_ajax.forEach(formularios => {
	formularios.addEventListener("change", recargarLista);
});*/
$(document).ready(function(){

	$('.selectAjax').change( function(){
		recargarLista(this);
	});

	

	/*  Show/Hidden Submenus */
	$('.nav-btn-submenu').on('click', function(e){
		e.preventDefault();
		var SubMenu=$(this).next('ul');
		var iconBtn=$(this).children('.fa-chevron-down');
		if(SubMenu.hasClass('show-nav-lateral-submenu')){
			$(this).removeClass('active');
			iconBtn.removeClass('fa-rotate-180');
			SubMenu.removeClass('show-nav-lateral-submenu');
		}else{
			$(this).addClass('active');
			iconBtn.addClass('fa-rotate-180');
			SubMenu.addClass('show-nav-lateral-submenu');
		}
	}); 

	/*  Show/Hidden Nav Lateral */
	$('.show-nav-lateral').on('click', function(e){
		e.preventDefault();
		var NavLateral=$('.nav-lateral');
		var PageConten=$('.page-content');
		if(NavLateral.hasClass('active')){
			NavLateral.removeClass('active');
			PageConten.removeClass('active');
		}else{
			NavLateral.addClass('active');
			PageConten.addClass('active');
		}
	});

	/*  Exit system buttom */
	$('.btn-exit-system').on('click', function(e){
		e.preventDefault();
		Swal.fire({
			title: 'Seguro quiere cerrar la sesion?',
			text: "Vas a cerrar sesion y saldras del sistema",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, exit!',
			cancelButtonText: 'No, cancel'
		}).then((result) => {
			if (result.value) {;
				window.location.href = 'http://10.21.61.44/almacen/';
				//var h= "<?php SERVERURL ?>";
				//console.log(h);
                
			}
		});
	});

	

    
});



(function($){
    $(window).on("load",function(){
        $(".nav-lateral-content").mCustomScrollbar({
        	theme:"light-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
        $(".page-content").mCustomScrollbar({
        	theme:"dark-thin",
        	scrollbarPosition: "inside",
        	autoHideScrollbar: true,
        	scrollButtons: {enable: true}
        });
    });
})(jQuery);

$(function(){
  $('[data-toggle="popover"]').popover()
});
