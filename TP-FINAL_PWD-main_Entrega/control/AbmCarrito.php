<?php
class AbmCarrito {
    public function cargarProductoCarrito($datos) {
        $objCompraEstadoBorrador = null;
        $arrayCompras = null;
        $objSesion = new Session();
        $objCompraEstado = new AbmCompraEstado();
        $objUsuario = $objSesion->getUsuario();
        $idUsuario["idUsuario"] = $objUsuario->getIdUsuario();
        $arrayCompras = $this->buscarComprasUsuario($idUsuario);

        if ($arrayCompras != null) {
            $objCompraEstadoBorrador = $objCompraEstado->buscarCompraBorrador($arrayCompras);
            if ($objCompraEstadoBorrador != null) {
                return $this->cargarProducto($objCompraEstadoBorrador, $datos);
            }
        }

        if (($arrayCompras == null) || ($objCompraEstadoBorrador == null)) {
            $objCompraEstadoBorrador = $this->crearCompra($idUsuario);
            return $this->cargarProducto($objCompraEstadoBorrador, $datos);
        }
    }

    private function buscarComprasUsuario($idUsuario) {
        $objCompra = new AbmCompra();
        return $objCompra->buscar($idUsuario);
    }

    private function cargarProducto($objCompraEstadoBorrador, $datos) {
        $objCompraItem = new AbmCompraItem();
        $arrayCompraItem = $objCompraItem->buscar($datos);
        $datos["idCompra"] = $objCompraEstadoBorrador->getCompra()->getIdCompra();
        $objCompraItemRepetido = $this->productoRepetido($arrayCompraItem, $datos["idCompra"]);

        if ($objCompraItemRepetido == null) {
            if ($objCompraItem->alta($datos)) {
                return json_encode(array('success' => 1));
            } else {
                return json_encode(array('success' => 0));
            }
        } else {
            $cantStockDisp = $objCompraItemRepetido->getObjProducto()->getCantStock();
            $cantTot = $datos["ciCantidad"] + $objCompraItemRepetido->getCantidad();
            if ($cantTot > $cantStockDisp) {
                return json_encode(array('success' => 0));
            } else {
                $param = [
                    "idCompraItem" => $objCompraItemRepetido->getIdCompraItem(),
                    "idProducto" => $objCompraItemRepetido->getObjProducto()->getIdProducto(),
                    "idCompra" => $objCompraItemRepetido->getObjCompra()->getIdCompra(),
                    "ciCantidad" => $cantTot
                ];
                $objCompraItem->modificacion($param);
                return json_encode(array('success' => 1));
            }
        }
    }

    private function productoRepetido($arrayCompraItem, $idCompra) {
        $resp = null;
        if ($arrayCompraItem != null) {
            foreach ($arrayCompraItem as $compraItem) {
                if ($compraItem->getObjCompra()->getIdCompra() == $idCompra) {
                    $resp = $compraItem;
                }
            }
        }
        return $resp;
    }

    private function crearCompra($idUsuario) {
        $objCompra = new AbmCompra();
        $objCompraEstado = new AbmCompraEstado();
        $arrayObjCompraEstado = null;

        if ($objCompra->alta($idUsuario)) {
            $arrayCompra = $objCompra->buscar($idUsuario);
            $fecha = new DateTime();
            $fechaStamp = $fecha->format('Y-m-d H:i:s');
            $paramCompraEstado = [
                "idCompra" => end($arrayCompra)->getIdCompra(),
                "idCompraEstadoTipo" => 1,
                "ceFechaIni" => $fechaStamp,
                "ceFechaFin" => null
            ];
            if ($objCompraEstado->alta($paramCompraEstado)) {
                $idCompra["idCompra"] = end($arrayCompra)->getIdCompra();
                $arrayObjCompraEstado = $objCompraEstado->buscar($idCompra);
            }
        }
        return $arrayObjCompraEstado[0];
    }
}
