<?php require_once(PROJECT_ROOT_PATH.'/login.php'); ?>

<script src="paginas/vendedor/productos/productos.js"></script>

<div class="vh-100 d-flex flex-column overflow-hidden">

  <!-- NAV BAR -->
  <nav class="productos-navbar navbar navbar-expand-lg navbar-dark bg-dark text-white p-2">
    <div class="d-flex flex-row flex-grow-1 justify-content-center">
        <i class="navbar-title-icon bi-box-seam"></i>
        <div class="d-flex flex-column justify-content-center">
            <p class="navbar-title-text text-nowrap h4" id="navbar-productos-title">Productos</p>
        </div>
        <div class="d-flex flex-grow-1"></div>
        
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#productos-navbarSupportedContent" aria-controls="productos-navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse flex-row-reverse" id="productos-navbarSupportedContent">
      <form onsubmit="return false;">
        <ul class="navbar-nav">
          <li class="nav-item ms-lg-2 mb-lg-0 mt-lg-0 ms-md-0 mb-md-1 mt-md-0">
            <input type="search" id="productos-busqueda" class="form-control form-control-dark" placeholder="Busqueda..." aria-label="Busqueda" oninput="vendedor_productos.filter_table(this.value);" disabled>
          </li>
          
            <li class="nav-item ms-lg-2 mb-lg-0 mt-lg-0 ms-md-0 mb-md-1 mt-md-0">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-productos" onclick="vendedor_productos.abrir_modal('agregar');">
                    + Agregar producto
                </button>
            </li>
        </ul>
      </form>
    </div>
  </nav>

  <!-- TABLA -->
  <table class="table table-hover table-bordered d-flex flex-column flex-grow-1 overflow-hidden align-middle scrollable-table" id='ordenes-table'>
    
    <thead>
      <tr class="table-secondary">
        <th>
          <b>SKU Producto</b>
        </th>
        <th>
          <b>Titulo</b>
        </th>
        <th>
          <b>Acciones</b>
        </th>
        <th class="header-column scrollbar-spacer" id="scrollbar-spacer-productos"></th>
      </tr>
    </thead>

    <tbody class="d-flex flex-column flex-grow-1" id="productos-table-content">
      <?php

        require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');

        $vendedor = new Vendedor($idUsr);
        $productos = $vendedor->getProductos();

        foreach ($productos as $key => $producto) {
          $id = $producto->id;
          $sku = $producto->sku;
          $titulo = $producto->titulo;
          echo '<tr>';

          //DATOS//
          echo '<td>'.$sku.'</td>';
          echo '<td>'.$titulo.'</td>';

          //ACCIONES//
          echo '<td class="td-centered">';
          echo '<div class="btn-group">';
          echo '<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal-productos" onclick="vendedor_productos.abrir_modal('."'modificar', ".$id.');" >Modificar</button>';
          echo '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-productos" onclick="vendedor_productos.abrir_modal('."'eliminar', ".$id.",'".$sku."'".');" >Eliminar</button>';
          echo '</div>';
          echo '</td>';

          echo '</tr>';
        }
      ?>
    </tbody> 

  </table>

  <!-- MODAL ORDEN -->
  <div class="modal fade" id="modal-productos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-productos-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="modal-productos-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal-productos-eliminar"></div>
        <div class="modal-body" id="modal-productos-body">

          <form class="form-window form-frame" action="paginas/vendedor/productos/agregar-producto.php" method="post" enctype="multipart/form-data">
            <input value="<?php echo $idUsr ?>" name="id_vendedor" hidden>
            <div class="input-group mb-3">
              <span class="input-group-text">Sku</span>
              <input type="text" class="form-control" id="modal-productos-SKU" name="sku">
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">Titulo</span>
              <input type="text" class="form-control" id="modal-productos-TITULO" name="titulo">
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">Precio</span>
              <input type="text" class="form-control" id="modal-productos-PRECIO" name="precio">
              <span class="input-group-text">$</span>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">Stock</span>
              <input type="number" min="0" class="form-control" value="0" id="modal-productos-STOCK" name="stock">
            </div>

            <div class="input-group ">
              <span class="input-group-text">Escala</span>
              <input type="text" class="form-control" id="modal-productos-ESCALA" value="1" name="scale">
            </div>
            <div class="input-group mb-3 mt-3" id="modal-productos-OBJ">
              <input type="file" class="form-control" accept=".obj" name="obj">
              <span class="input-group-text">obj</span>
            </div>
            <div class="input-group mb-3" id="modal-productos-MTL">
              <input type="file" class="form-control" accept=".mtl" name="mtl">
              <span class="input-group-text">mtl</span>
            </div>

            <button type="submit" class="btn btn-success" id="modal-productos-success">Agregar</button>
          </form>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-warning" id="modal-productos-warning" onclick="vendedor_productos.modificar_producto();">Modificar</button>
          <button type="button" class="btn btn-danger" id="modal-productos-danger" onclick="vendedor_productos.eliminar_producto();">Confirmar</button>
        </div>
          
      </div>
    </div>
  </div>

</div>
