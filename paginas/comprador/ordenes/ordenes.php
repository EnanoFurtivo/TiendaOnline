<?php require_once(PROJECT_ROOT_PATH.'/login.php'); ?>

<script src="paginas/comprador/ordenes/ordenes.js"></script>

<div class="vh-100 d-flex flex-column overflow-hidden">

  <!-- NAV BAR -->
  <nav class="ordenes-navbar navbar navbar-expand-lg navbar-dark bg-dark text-white p-2">
    <div class="d-flex flex-row flex-grow-1 justify-content-center">
        <i class="navbar-title-icon bi-cart"></i>
        <div class="d-flex flex-column justify-content-center">
            <p class="navbar-title-text text-nowrap h4" id="navbar-ordenes-title">Ordenes</p>
        </div>
        <div class="d-flex flex-grow-1"></div>
        
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#ordenes-navbarSupportedContent" aria-controls="ordenes-navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse flex-row-reverse" id="productos-navbarSupportedContent">
      <form onsubmit="return false;">
        <ul class="navbar-nav">
          <li class="nav-item ms-lg-2 mb-lg-0 mt-lg-0 ms-md-0 mb-md-1 mt-md-0">
            <input type="search" id="ordenes-busqueda" class="form-control form-control-dark" placeholder="Busqueda..." aria-label="Busqueda" oninput="comprador_ordenes.filter_table(this.value);" disabled>
          </li>
        </ul>
      </form>
    </div>
  </nav>

  <!-- TABLA -->
  <table class="table table-hover table-bordered d-flex flex-column flex-grow-1 overflow-auto align-middle scrollable-table" id='ordenes-table'>
    
    <thead>
      <tr class="table-secondary">
        <th>
          <b>ID Orden</b>
        </th>
        <th>
          <b># Productos</b>
        </th>
        <th>
          <b>Estado</b>
        </th>
        <th>
          <b>Fecha</b>
        </th>
        <th>
          <b>Acciones</b>
        </th>
        <th class="header-column scrollbar-spacer" id="scrollbar-spacer-ordenes"></th>
      </tr>
    </thead>

    <tbody class="d-flex flex-column flex-grow-1" id="ordenes-table-content">
      <?php

        require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');

        $comprador = new Comprador($idUsr);
        $ordenes = $comprador->getOrdenes();

        foreach ($ordenes as $key => $orden) {
          
          $id = $orden->id;
          $nro_productos = $orden->cant_items;
          $estado = $orden->estado;
          $fecha = $orden->fecha;

          echo '<tr>';

          //DATOS//
          echo '<td class="td-centered">'.$id.'</td>';
          echo '<td class="td-centered">'.$nro_productos.'</td>';
          echo '<td class="td-centered">'.$estado.'</td>';
          echo '<td class="td-centered">'.$fecha.'</td>';

          //ACCIONES//
          echo '<td class="td-centered">';
          echo '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-ordenes" onclick="comprador_ordenes.abrir_modal('.$id.');">Ver Detalle</button>';
          echo '</td>';

          echo '</tr>';
        }
      ?>
    </tbody> 

  </table>

  <!-- MODAL ORDEN -->
  <div class="modal fade" id="modal-ordenes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-ordenes-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
      <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title" id="modal-ordenes-title">Detalle de la orden</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <form class="form-window form-frame" onsubmit="return false;">
            <label for="basic-url" class="form-label">Datos de la orden</label>
            <div class="input-group mb-3">
              <span class="input-group-text">Id
              </span>
              <input type="text" class="form-control" id="modal-ordenes-ID" disabled>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text"># de productos
              </span>
              <input type="text" class="form-control" id="modal-ordenes-CANT_PRODUCTOS" disabled>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">Fecha
              </span>
              <input type="text" class="form-control" id="modal-ordenes-FECHA" disabled>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">Estado
              </span>
              <input type="text" class="form-control" id="modal-ordenes-ESTADO" disabled>
            </div>
          </form>

          <table class="table table-hover table-bordered align-middle form-frame" id='modal-ordenes-table'>
            <thead>
              <tr class="table-secondary">
                <th rowspan="1">
                  <b>SKU Producto</b>
                </th>
                <th rowspan="1">
                  <b>Cantidad</b>
                </th>
              </tr>
            </thead>
            <tbody id="modal-ordenes-table-content">

            </tbody> 
          </table>

        </div>

        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> -->
          <button type="button" class="btn btn-danger" id="productos-modal-footer-cancel" onclick="comprador_ordenes.cancelar_orden();">Cancelar Orden</button>
        </div>
          
      </div>
    </div>
  </div>


</div>
