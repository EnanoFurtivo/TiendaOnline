let paginaActual = 'home';
let paginas = {};

const registrarPagina = function(nombrePagina, objPagina)
{
  paginas[nombrePagina] = objPagina;
};

//implementa lazy loading//
const abrirPagina = function(nombrePagina)
{
  if (nombrePagina != paginaActual)
  {
    //cerrar pagina actual//
    try 
    {
      console.log('Cerrando ('+paginaActual+')');
      paginas[paginaActual].cerrarPagina();
    } 
    catch (error) 
    {
      console.log('[ERROR] - La pagina '+paginaActual+' no contiene el metodo cerrarPagina()');
    }

    //abrir nueva pagina//
    try 
    {
      console.log('Abriendo ('+nombrePagina+')');
      paginas[nombrePagina].abrirPagina();
    } 
    catch (error) 
    {
      console.log('[ERROR] - La pagina '+paginaActual+' no contiene el metodo abrirPagina()');
    }
  }

  paginaActual = nombrePagina;
};