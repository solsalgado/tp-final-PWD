<?php
class AbmCompraEstado
{

    /**
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (
            array_key_exists('idCompraEstado', $param) and array_key_exists('idCompra', $param)
            and array_key_exists('idCompraEstadoTipo', $param) and array_key_exists('ceFechaIni', $param)
            and array_key_exists('ceFechaFin', $param)
        ) {

            $objCompra = new Compra();
            $objCompra->setIdCompra($param['idCompra']);
            $objCompra->cargar();

            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setIdCompraEstadoTipo($param['idCompraEstadoTipo']);
            $objCompraEstadoTipo->cargar();

            $obj = new CompraEstado();
            $obj->setear($param['idCompraEstado'], $objCompra, $objCompraEstadoTipo, $param['ceFechaIni'], $param['ceFechaFin']);
        }
        return $obj;
    }

    /**
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], null, null, null, null);
        }
        return $obj;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idCompraEstado']))
            $resp = true;
        return $resp;
    }

    /**
     * Inserta en la base de datos
     * @param array $param
     * @return boolean
     */
    public function alta($param)
    {
        $resp = false;
        $param['idCompraEstado'] = null;
        $obj = $this->cargarObjeto($param);
        if ($obj != null and $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }


    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null and $obj->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null and $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['idCompraEstado']))
                $where .= " and idCompraEstado =" . $param['idCompraEstado'];
            if (isset($param['idCompra']))
                $where .= " and idCompra =" . $param['idCompra'];
            if (isset($param['idCompraEstadoTipo']))
                $where .= " and idcompraestadotipo ='" . $param['idcompraestadotipo'] . "'";
        }

        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

    public function buscarCompraBorrador($arrayCompra)
    {
        $objCompraEstadoInciada = null;
        $i = 0;
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        while (($objCompraEstadoInciada == null) && ($i < count($arrayCompra))) {
            $idCompra["idCompra"] = $arrayCompra[$i]->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getCetDescripcion() == "borrador") {
                $objCompraEstadoInciada = $arrayCompraEstado[0];
            } else {
                $i++;
            }
        }
        return $objCompraEstadoInciada;
    }

    public function buscarCompras($arrayCompra)
    {
        $arrayCompraIniciadas = [];
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        foreach ($arrayCompra as $compra) {
            $idCompra["idCompra"] = $compra->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if (count($arrayCompraEstado) > 1) {
                foreach ($arrayCompraEstado as $compraEstado) {
                    if ($compraEstado->getCeFechaFin() == "0000-00-00 00:00:00") {
                        array_push($arrayCompraIniciadas, $compraEstado);
                    }
                }
            } else {
                if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getIdCompraEstadoTipo() >= 2) {
                    array_push($arrayCompraIniciadas, $arrayCompraEstado[0]);
                }
            }
        }
        return $arrayCompraIniciadas;
    }


    public function realizarCompraCarrito($datos)
{
    $arrayCompraEstado = $this->buscar($datos);
    $idCompra["idCompra"] = $arrayCompraEstado[0]->getCompra()->getIdCompra();
    $arrayObjProductoCarrito = $this->obtenerProductos($idCompra);

    if ($arrayObjProductoCarrito != null) {
        if ($this->modificarEstadoCompra($datos, $arrayCompraEstado[0])) {
            foreach ($arrayObjProductoCarrito as $objProductoCarrito) {
                $this->modificarStockProducto($objProductoCarrito);
            }
            return 1;
        } else {
            return 0;
        }
    } else {
        return 2;
    }
}

private function obtenerProductos($idCompra)
{
    $objCompraItem = new AbmCompraItem;
    $arrayCompraItem = $objCompraItem->buscar($idCompra);
    return $arrayCompraItem;
}

private function modificarEstadoCompra($datos, $compraEstado)
{
    $resp = false;
    $fecha = new DateTime();
    $fechaStamp = $fecha->format('Y-m-d H:i:s');
    $paramCompraEstado = [
        "idCompraEstado" => $datos["idCompraEstado"],
        "idCompra" => $compraEstado->getCompra()->getIdCompra(),
        "idCompraEstadoTipo" => 2,
        "ceFechaIni" => $fechaStamp,
        "ceFechaFin" => null,
    ];
    if ($this->modificacion($paramCompraEstado)) {
        $resp = true;
    }
    return $resp;
}

private function modificarStockProducto($objProductoCarrito)
{
    $objProducto = new AbmProducto();
    $idProducto["idProducto"] = $objProductoCarrito->getObjProducto()->getIdProducto();
    $arrayProducto = $objProducto->buscar($idProducto);
    $stockTot = $arrayProducto[0]->getCantStock() - $objProductoCarrito->getCantidad();
    $paramProducto = [
        "idProducto" => $arrayProducto[0]->getIdProducto(),
        "proNombre" => $arrayProducto[0]->getNombre(),
        "proDetalle" => $arrayProducto[0]->getDetalle(),
        "proPrecio" => $arrayProducto[0]->getProPrecio(),
        "urlImagen" => $arrayProducto[0]->getUrlImagen(),
        "proCantStock" => $stockTot
    ];
    $objProducto->modificacion($paramProducto);
}

public function modificarEstados($datos) {
    $resp = false;
    $paramCompraEstadoAnterior = null;
    $paramCompraEstadoNuevo = null;
    $fecha = new DateTime();
    $fechaHoy = $fecha->format('Y-m-d H:i:s');

    $paramCompraEstadoAnterior = [
        "idCompraEstado" => $datos["idCompraEstado"],
        "idCompra" => $datos["idCompra"],
        "idCompraEstadoTipo" => $datos["idCompraEstadoTipoAnterior"],
        "ceFechaIni" => $datos["ceFechaIni"],
        "ceFechaFin" => $fechaHoy, // Cierra el estado actual
    ];

    $paramCompraEstadoNuevo = [
        "idCompraEstado" => $datos["idCompraEstado"],
        "idCompra" => $datos["idCompra"],
        "idCompraEstadoTipo" => $datos["idCompraEstadoTipoActualizado"],
        "ceFechaIni" => $fechaHoy, // Inicia ahora
        "ceFechaFin" => null, // Sin finalizar
    ];

    if ($this->modificacion($paramCompraEstadoAnterior) && $this->alta($paramCompraEstadoNuevo)) {
        $resp = true;
    }

    if ($datos["idCompraEstadoTipoActualizado"] == 5) {
        $objCompraItem = new AbmCompraItem();
        $arrayCompraItem = $objCompraItem->buscar($datos);
        foreach ($arrayCompraItem as $compraItem) {
            $paramProducto = [
                "idProducto" => $compraItem->getObjProducto()->getIdProducto(),
                "proNombre" => $compraItem->getObjProducto()->getNombre(),
                "proDetalle" => $compraItem->getObjProducto()->getDetalle(),
                "proCantStock" => $compraItem->getObjProducto()->getCantStock() + $compraItem->getCantidad(),
                "proPrecio" => $compraItem->getObjProducto()->getProPrecio(),
                "urlImagen" => $compraItem->getObjProducto()->getUrlImagen(),
            ];
            $objProducto = new AbmProducto();
            $objProducto->modificacion($paramProducto);
        }
    }

    return $resp;
}

public function obtenerHistorial($datos) {
    $arrayCompraEstado = $this->buscar($datos);
    if ($arrayCompraEstado !== null) {
        return $this->arrayArmadoJS($arrayCompraEstado);
    }
    return null;
}

private function arrayArmadoJS($arrayCompraEstado) {
    $arrayJS = [];
    foreach ($arrayCompraEstado as $compraEstado) {
        $param = [
            "idCompra" => $compraEstado->getIdCompraEstado(),
            "NombreUsuario" => $compraEstado->getCompra()->getObjUsuario()->getUsNombre(),
            "Estado" => $compraEstado->getCompraEstadoTipo()->getCetDescripcion(),
            "FechaInicio" => $compraEstado->getCeFechaIni(),
            "FechaFin" => $compraEstado->getCeFechaFin(),
        ];
        array_push($arrayJS, $param);
    }
    return $arrayJS;
}

}
