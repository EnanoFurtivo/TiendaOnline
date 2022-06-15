<?php
    define("PROJECT_ROOT_PATH", "../../..");
    require_once(PROJECT_ROOT_PATH.'/modelo/load-modelo.php');
    
    if (!isset($_POST["sku"]))
    {
        http_response_code(400);
        echo "No se envio sku con el llamado ajax";
        die();
    }

    if (!isset($_POST["titulo"]))
    {
        http_response_code(400);
        echo "No se envio titulo con el llamado ajax";
        die();
    }

    if (!isset($_POST["stock"]))
    {
        http_response_code(400);
        echo "No se envio stock con el llamado ajax";
        die();
    }

    if (!isset($_POST["scale"]))
    {
        http_response_code(400);
        echo "No se envio scale con el llamado ajax";
        die();
    }

    if (!isset($_POST["precio"]))
    {
        http_response_code(400);
        echo "No se envio precio con el llamado ajax";
        die();
    }

    if (!isset($_POST["id_vendedor"]))
    {
        http_response_code(400);
        echo "No se envio id_vendedor con el llamado ajax";
        die();
    }

    $id_vendedor = $_POST["id_vendedor"];
    $sku = $_POST["sku"];
    $titulo = $_POST["titulo"];
    $stock = $_POST["stock"];
    $scale = $_POST["scale"];
    $precio = $_POST["precio"];

    $producto = Producto::createProducto($id_vendedor, $sku, $titulo, $stock, $scale, $precio);
    if (empty($producto))
    {
        http_response_code(400);
        echo "No se pudo agregar el producto...";
        die();
    }

    $dir = PROJECT_ROOT_PATH."/datos/productos/".$producto->id;
    $imageFileType = strtolower(pathinfo($dir.basename($_FILES["img"]["name"]), PATHINFO_EXTENSION));

    mkdir($dir, 0700, true);
    move_uploaded_file($_FILES["obj"]["tmp_name"], $dir."/modelo.obj");
    move_uploaded_file($_FILES["mtl"]["tmp_name"], $dir."/modelo.mtl");
    move_uploaded_file($_FILES["img"]["tmp_name"], $dir."/preview.".$imageFileType);

    echo "Producto agregado con exito";
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/tiendaonline');
    http_response_code(200);
?>