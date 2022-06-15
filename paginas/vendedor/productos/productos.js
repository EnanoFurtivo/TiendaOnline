var vendedor_productos = {
  
    //VARIABLES DE LA PAGINA//
    pageName:       'vendedor-productos',
    sidebarNavId:   'nav-productos',
    cur_id:         0,

    //MODAL//
    id_modal_titulo:    'modal-productos-title',
    id_modal_body:      'modal-productos-body',
    id_modal_success:   'modal-productos-success',
    id_modal_danger:    'modal-productos-danger',
    id_modal_warning:   'modal-productos-warning',
    id_modal_eliminar:  'modal-productos-eliminar',
    
    //DATOS MODAL//
    id_sku:         'modal-productos-SKU',
    id_titulo:      'modal-productos-TITULO',
    id_precio:      'modal-productos-PRECIO',
    id_stock:       'modal-productos-STOCK',
    id_escala:      'modal-productos-ESCALA',
    id_obj:         'modal-productos-OBJ',
    id_mtl:         'modal-productos-MTL',
    id_img:         'modal-productos-IMG',
    id_obj_file:    'modal-productos-OBJ-file',
    id_mtl_file:    'modal-productos-MTL-file',
    id_img_file:    'modal-productos-IMG-file',

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
    reiniciar_modal: function()
    {
        document.getElementById(this.id_sku).value = "Ingresse un sku";
        document.getElementById(this.id_titulo).value = "Ingrese un titulo";
        document.getElementById(this.id_stock).value = "0";
        document.getElementById(this.id_escala).value = "1.000000";
        document.getElementById(this.id_precio).value = "0.00";

        document.getElementById(this.id_modal_body).hidden = false;

        document.getElementById(this.id_sku).disabled = true;
        document.getElementById(this.id_titulo).disabled = true;
        document.getElementById(this.id_obj).hidden = true;
        document.getElementById(this.id_mtl).hidden = true;
        document.getElementById(this.id_img).hidden = true;

        document.getElementById(this.id_modal_eliminar).hidden = true;
        document.getElementById(this.id_modal_success).hidden = true;
        document.getElementById(this.id_modal_warning).hidden = true;
        document.getElementById(this.id_modal_danger).hidden = true;
    },
    abrir_modal: function (accion, id = null, sku = null) 
    {
        this.cur_id = id;

        this.reiniciar_modal();
        switch (accion) 
        {
            case "agregar":
                document.getElementById(this.id_modal_titulo).innerHTML = "Agregar producto";
                document.getElementById(this.id_sku).disabled = false;
                document.getElementById(this.id_titulo).disabled = false;
                document.getElementById(this.id_obj).hidden = false;
                document.getElementById(this.id_mtl).hidden = false;
                document.getElementById(this.id_img).hidden = false;
                document.getElementById(this.id_modal_success).hidden = false;
                break;

            case "eliminar":
                document.getElementById(this.id_modal_titulo).innerHTML = "Eliminar producto";
                document.getElementById(this.id_modal_eliminar).innerHTML = "Seguro que desea eliminar el producto "+sku+"?";
                document.getElementById(this.id_modal_body).hidden = true;
                document.getElementById(this.id_modal_eliminar).hidden = false;
                document.getElementById(this.id_modal_danger).hidden = false;
                break;

            case "modificar":
                document.getElementById(this.id_modal_titulo).innerHTML = "Modificar producto";
                document.getElementById(this.id_modal_warning).hidden = false;   
                this.get_producto(id);
                break;
        }
    },

    get_producto: function(id)
    {
        page = this;
        $.ajax({
            url: 'paginas/vendedor/productos/get-producto.php',
            type: 'POST',
            async: true,
            data: {id_producto: id},
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
    modificar_valores_modal: function(producto)
    {
        document.getElementById(this.id_sku).value = producto.sku;
        document.getElementById(this.id_titulo).value = producto.titulo;
        document.getElementById(this.id_stock).value = producto.stock;
        document.getElementById(this.id_escala).value = producto.scale;
        document.getElementById(this.id_precio).value = producto.precio;
    },

    agregar_producto: function()
    {      
        var formData = new FormData($("#modal-productos-form")[0]);
        page = this;
        $.ajax({
            url: 'paginas/vendedor/productos/agregar-producto.php',
            type: 'POST',
            mimeType: "multipart/form-data",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response)
            {
                bootstrap.Modal.getInstance(document.getElementById('modal-productos')).hide();
                notificarToast({ tipo: 'success', contenido: response });
            },
            error: function(response)
            {
                notificarToast({ tipo: 'danger', contenido: response.responseText });
            }
        });
    },
    modificar_producto: function()
    {
        stock = document.getElementById(this.id_stock).value;
        precio = document.getElementById(this.id_precio).value;
        scale = document.getElementById(this.id_escala).value;

        page = this;
        $.ajax({
            url: 'paginas/vendedor/productos/modificar-producto.php',
            type: 'POST',
            async: true,
            data: {id: this.cur_id, precio: precio, stock: stock, scale: scale},
            success: function(response)
            {
                bootstrap.Modal.getInstance(document.getElementById('modal-productos')).hide();
                notificarToast({ tipo: 'success', contenido: response });
            },
            error: function(response)
            {
                notificarToast({ tipo: 'danger', contenido: response.responseText });
            }
        });
    },
    eliminar_producto: function()
    {
        page = this;
        $.ajax({
            url: 'paginas/vendedor/productos/eliminar-producto.php',
            type: 'POST',
            async: true,
            data: {id: this.cur_id},
            success: function(response)
            {
                bootstrap.Modal.getInstance(document.getElementById('modal-productos')).hide();
                notificarToast({ tipo: 'success', contenido: response });
            },
            error: function(response)
            {
                notificarToast({ tipo: 'danger', contenido: response.responseText });
            }
        });
    },
};

//Cuando se carga el archivo .js se registra la pagina en body.js para implementar lazy loading//
$(window).on('load', vendedor_productos.inicializar());