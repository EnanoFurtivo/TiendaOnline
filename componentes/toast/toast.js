let anteriorToast = 0;

const notificarToast = function (opciones) {
  
  if (typeof opciones === 'undefined' || opciones === null || opciones.contenido == '')
    return;

  //toast settings//
  let toastId = 'body-toast-'+(++anteriorToast);
  console.log(opciones);
  opciones = getParametrosToast(opciones);
  console.log(opciones);

  //clone original toast//
  let original = document.getElementById('body-toast-original');
  let toast = original.cloneNode(true)
  toast.setAttribute('id', toastId);
  document.getElementById('body-toast-wrapper').appendChild(toast);

  //set toast settings to toast//
  $("#"+toastId+" > div.toast-header > span")[0].classList.add("bg-"+opciones.tipo);
  $("#"+toastId+" > div.toast-header > strong")[0].innerHTML = opciones.header;
  $("#"+toastId+" > div.toast-header > small")[0].innerHTML = opciones.headerSmall;
  $("#"+toastId+" > div.toast-body")[0].innerHTML = opciones.contenido;

  //show toast//
  let toastInstance = new bootstrap.Toast(toast, { delay: opciones.delay });
  toastInstance.show();
  $("#"+toastId).on("hidden.bs.toast", function(){ disposeToast(toastId); });
}

const getParametrosToast = function(opcionesParam) {
  
  let opciones = {
    tipo: 'secondary',
    header: '',
    headerSmall: 'ahora',
    contenido: '',
    delay: null
  };

  let defaultParametros = { 
    secondary: {
      header: "Informcaion"
    },
    success: {
      header: "Operacion exitosa"
    },
    danger: {
      header: "Error del sistema"
    },
    warning: {
      header: "Alerta"
    }, 
  };

  Object.assign(opciones, opcionesParam);

  //count words in toast for delay//
  if (opciones.delay == null)
  {
    let promedioWpm = 285;
    let str = opciones.header+' '+opciones.headerSmall+' '+opciones.contenido;
    str.replace(/(^\s*)|(\s*$)/gi,"");
    str.replace(/[ ]{2,}/gi," ");
    str.replace(/\n /,"\n");
    opciones.delay = ((str.split(' ').length+10)/promedioWpm)*60*1000; //delay en ms que lleva leer la notificacion//
  }

  if (opciones.header == '')
    opciones.header = defaultParametros[opciones.tipo].header;

  return opciones;
}

const disposeToast = function(toastId) {
  document.getElementById(toastId).remove();
}
