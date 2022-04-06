<?php /* require(dirname(__FILE__,2).'/login.php'); */ $tipoUsr = 'vendedor'; ?>
<link rel="stylesheet" href="paginas/body.css">
<script src="paginas/body.js"></script>

<div class="d-flex flex-row">

    <!-- SIDEBAR -->
    <ul class="sidebar-pills nav flex-column flex-shrink-0 nav-pills bg-dark" id="sidebar" role="tablist">
      
        <!-- SIDEBAR TITLE -->

        <a class="sidebar-title d-flex flex-shrink-0 text-white text-decoration-none">
          <image width="38" height="38" x="0" y="0" src="assets/logo-tiendaonline.png" />
          <p class="sidebar-title-text h3">TiendaOnline</p>
        </a>

        <!-- SIDEBAR PILLS -->

        <li class="nav-item">
            <a class="sidebar-pill nav-link active" id="nav-home-tab" data-bs-toggle="pill" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
            <i class="sidebar-pill-icon bi-house"></i>
            Home
            </a>
        </li>

        <li class="nav-item">
            <a class="sidebar-pill nav-link" id="nav-ordenes-tab" data-bs-toggle="pill" data-bs-target="#nav-ordenes" type="button" role="tab" aria-controls="nav-ordenes" aria-selected="true">
            <i class="sidebar-pill-icon bi-cart"></i>
            Ordenes
            </a>
        </li>

        <?php if($tipoUsr == 'vendedor') { ?>
            <li class="nav-item">
                <a class="sidebar-pill nav-link" id="nav-productos-tab" data-bs-toggle="pill" data-bs-target="#nav-productos" type="button" role="tab" aria-controls="nav-productos" aria-selected="true">
                <i class="sidebar-pill-icon bi-box-seam"></i>
                Productos
                </a>
            </li>
        <?php } ?>

    </ul>

    <!-- SIDEBAR DIVIDER -->
    <div class="sidebar-divider d-flex flex-shrink-0"></div>

    <!-- SIDEBAR CONTENT -->
    <div class="d-flex tab-content flex-grow-1" id="sidebar-content">

        <div class="tab-pane flex-grow-1 fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <?php require("paginas/home/home.php"); ?>
        </div>

        <div class="tab-pane flex-grow-1 fade" id="nav-ordenes" role="tabpanel" aria-labelledby="nav-ordenes-tab">
            <?php require("paginas/".$tipoUsr."/ordenes/ordenes.php"); ?>
        </div>

        <?php if($tipoUsr == 'vendedor') {?>
            <div class="tab-pane flex-grow-1 fade" id="nav-productos" role="tabpanel" aria-labelledby="nav-productos-tab">
                <?php require("paginas/vendedor/productos/productos.php"); ?>
            </div> 
        <?php } ?>

    </div>

    <?php require_once("componentes/toast/toast.php") ?>

</div>
