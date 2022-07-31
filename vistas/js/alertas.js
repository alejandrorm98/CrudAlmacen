
const formularios_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
	e.preventDefault();

	let data = new FormData(this);
	let method = this.getAttribute("method");
	let action = this.getAttribute("action");
	let value = this.getAttribute("value");
	let tipo = this.getAttribute("data-form");
	let ide = this.getAttribute("id");

	let encabezados = new Headers();

	let config = {
		method: method,
		headers: encabezados,
		mode: 'cors',
		cache: 'no-cache',
		body: data
	}

	let texto_alerta;

	if(tipo==="save"){
		texto_alerta="Los datos quedaran guardados en el sistema";
	}else if(tipo==="delete"){
		texto_alerta="Los datos serán eliminados completamente del sistema (" +value+")";
	}else if(tipo==="update"){
		texto_alerta="Los datos del sistema serán actualizados";
	}else if(tipo==="search"){
		texto_alerta="Se eliminará el término de búsqueda y tendrás que escribir uno nuevo";
	}else if(tipo==="loans"){
		texto_alerta="Desea agregar el movimiento";
	}else{
		texto_alerta="Quieres realizar la operación solicitada";
	}

	if (tipo==="delete") {

		Swal.fire({
			title: '¿Estás seguro?',
			text: texto_alerta,
			input: 'text',
			inputAttributes: {
				   autocapitalize: 'off'
							  },
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Aceptar',
			cancelButtonText: 'Cancelar',
			preConfirm: (login) => {
				var obs= new FormData();
				obs.append('obs',login);
				fetch(action,{
								method:'POST', 
								body: obs
							})
				.then(datos => datos.text())
				.then(datos => 
					{
					console.log(datos); })
			}
		}).then((result) => {
			if (result.value) {
				fetch(action,config)
				.then(response => response.json())
				.then(json  => 
					{	console.log(json);
						return alertas_ajax(json);
						
					}
				)
				.catch(error => console.error('Error:', error));
				/*document.getElementById(ide).reset();*/
			}
		});
	}else{
		Swal.fire({
			title: '¿Estás seguro?',
			text: texto_alerta,
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Aceptar',
			cancelButtonText: 'Cancelar',
		}).then((result) => {
			if (result.value) {
				fetch(action,config)
				.then(response => response.json())
				.then(json  => 
					{	console.log(json);
						return alertas_ajax(json);
						
					}
				)
				.catch(error => console.error('Error:', error));
				/*document.getElementById(ide).reset();*/
			}
		});
	}
	

}

formularios_ajax.forEach(formularios => {
	formularios.addEventListener("submit", enviar_formulario_ajax);
});


function alertas_ajax(alerta){
	if(alerta.Alerta==="simple"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		});
	}else if(alerta.Alerta==="recargar"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		}).then((result) => {
			if(result.value){
				location.reload();
			}
		});
	}else if(alerta.Alerta==="limpiar"){
		Swal.fire({
			title: alerta.Titulo,
			text: alerta.Texto,
			type: alerta.Tipo,
			confirmButtonText: 'Aceptar'
		}).then((result) => {
			if(result.value){
				document.querySelector(".FormularioAjax").reset();
			}
		});
	}else if(alerta.Alerta==="redireccionar"){
		window.location.href=alerta.URL;
	}
}

