var vendedor_ordenes = {
  
    //VARIABLES DE LA PAGINA//
    pageName: 'vendedor-ordenes',
    sidebarNavId: 'nav-ordenes',
    cur_id: null,

    //FUNCIONES PARA IMPLEMENTAR LAZY LOADING//
    inicializar: function()
    {
        registrarPagina(this.pageName, this);
        document.getElementById(this.sidebarNavId).ontransitionend = () => { abrirPagina(this.pageName); };
    },
    abrirPagina : function()
    {
        //Si se requiere rellenar datos dinamicos cuando se abre la pagina se utiiliza esta funcion//
    },
    cerrarPagina: function() 
    {
        //Limpiar variables y tablas para no ocupar memoria que no se seguira utilizando//
    },

    //FUNCIONES DE LA PAGINA//
    abrir_modal: function(id)
    {
        page = this;
        $.ajax({
            url: 'paginas/vendedor/ordenes/get-orden.php',
            type: 'POST',
            async: true,
            data: {id_orden: id},
            success: function(response)
            {
                page.modificar_valores_modal(JSON.parse(response)); //callback
            },
            error: function(response)
            {
                notificarToast({ tipo: 'warning', contenido: response.responseText });
            }
        });
    },

    finalizar_orden: function () 
    {
        id = cur_id;
        page = this;
        $.ajax({
            url: 'paginas/vendedor/ordenes/finalizar-orden.php',
            type: 'POST',
            async: true,
            data: {id_orden: id},
            success: function(response)
            {
                bootstrap.Modal.getInstance(document.getElementById('modal-ordenes')).hide();
                notificarToast({ tipo: 'success', contenido: "estado de orden actualizado a finalizada" });
            },
            error: function(response)
            {
                notificarToast({ tipo: 'warning', contenido: response.responseText });
            }
        });
    },

    modificar_valores_modal: function(orden)
    {
        cur_id = orden.id;

        document.getElementById("modal-ordenes-NOMBRE").value = orden.comprador.username;
        document.getElementById("modal-ordenes-EMAIL").value = orden.comprador.mail;
        document.getElementById("modal-ordenes-TELEFONO").value = orden.comprador.telefono;
        document.getElementById("modal-ordenes-ID").value = orden.id;
        document.getElementById("modal-ordenes-CANT_PRODUCTOS").value = orden.cant_items;
        document.getElementById("modal-ordenes-FECHA").value = orden.fecha;
        document.getElementById("modal-ordenes-ESTADO").value = orden.estado;

        let result = "";
        let items = orden.items;
        Object.keys(items).forEach( key => {
            result += '<tr>';
            result += '<td>' + items[key].producto.sku + '</td>';
            result += '<td>' + items[key].cantidad + '</td>';
            result += '</tr>';
        });

        document.getElementById("modal-ordenes-table-content").innerHTML = result;
        document.getElementById("productos-modal-footer-submit").disabled = (orden.estado == "finalizada");
    }
};

//Cuando se carga el archivo .js se registra la pagina en body.js para implementar lazy loading//
$(window).on('load', vendedor_ordenes.inicializar());