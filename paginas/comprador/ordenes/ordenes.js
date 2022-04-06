var comprador_ordenes = {
  
    //VARIABLES DE LA PAGINA//
    pageName: 'comprador-ordenes',
    sidebarNavId: 'nav-ordenes',

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
    }

    //FUNCIONES DE LA PAGINA//
};

//Cuando se carga el archivo .js se registra la pagina en body.js para implementar lazy loading//
$(window).on('load', comprador_ordenes.inicializar());