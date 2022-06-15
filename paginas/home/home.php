<?php require_once(PROJECT_ROOT_PATH.'/login.php'); ?>

<script src="paginas/home/home.js"></script>
<link rel="stylesheet" href="paginas/home/home.css">
<div class="vh-100 d-flex flex-column overflow-hidden p-3 mx-auto text-center text-white bg-dark background">
    
    <header class="mb-auto">
        <h3 class="float-md-start mb-0 ms-3">Cover</h3>
        <ul class="nav bg-transparent flex-shrink-0 bg-dark nav-masthead justify-content-center float-md-end" id="home-nav" role="tablist">
    
            <li class="nav-item">
                <a class="nav-link active" id="nav-home-cover-tab" data-bs-toggle="pill" data-bs-target="#nav-home-cover" type="button" role="tab" aria-controls="nav-home-cover" aria-selected="true">
                Home
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="nav-home-caracteristicas-tab" data-bs-toggle="pill" data-bs-target="#nav-home-caracteristicas" type="button" role="tab" aria-controls="nav-home-caracteristicas" aria-selected="true">
                Caracteristicas
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="nav-home-contacto-tab" data-bs-toggle="pill" data-bs-target="#nav-home-contacto" type="button" role="tab" aria-controls="nav-home-contacto" aria-selected="true">
                Contacto
                </a>
            </li>

        </ul>
    </header>

    <div class="d-flex tab-content align-self-center" id="home-content">

        <main class="px-3">
            <div class="tab-pane fade active show" id="nav-home-cover" role="tabpanel" aria-labelledby="nav-home-cover-tab">
                <?php require("paginas/home/home-cover.php"); ?>
            </div>
            
            <div class="tab-pane fade" id="nav-home-caracteristicas" role="tabpanel" aria-labelledby="nav-home-caracteristicas-tab">
                <?php require("paginas/home/home-caracteristicas.php"); ?>
            </div>

            <div class="tab-pane fade" id="nav-home-contacto" role="tabpanel" aria-labelledby="nav-home-contacto-tab">
                <?php require("paginas/home/home-contacto.php"); ?>
            </div>
        </main>

    </div>
    
    <footer class="mt-auto text-white-50">
        <p>Construido con 
        <a href="https://unity.com/" class="text-white">Unity</a>, 
        <a href="https://docs.microsoft.com/en-us/dotnet/csharp/" class="text-white">C#</a>, 
        <a href="https://assetstore.unity.com/packages/tools/modeling/runtime-obj-importer-49547" class="text-white">Runtime OBJ Importer</a> | 
        <a href="https://en.wikipedia.org/wiki/HTML" class="text-white">HTML 5</a>, 
        <a href="https://en.wikipedia.org/wiki/JavaScript" class="text-white">Java Script</a>, 
        <a href="https://en.wikipedia.org/wiki/CSS" class="text-white">CSS</a>, 
        <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a> | 
        <a href="https://www.php.net/" class="text-white">PHP</a>, 
        <a href="https://github.com/jedisct1/libsodium" class="text-white">Sodium</a>, 
        <a href="https://www.php.net/" class="text-white">Apache</a>, 
        <a href="https://mariadb.org/" class="text-white">MariaDB</a>
    </p>
    </footer>

</div>