<?php
class AbmCompraItem
{
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto


    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return object
     */
    private function cargarObjeto($param)
    {
        $objCompraItem = null;

        if (array_key_exists('idCompraItem', $param) and array_key_exists('idProducto', $param) and array_key_exists('idCompra', $param) and array_key_exists('ciCantidad', $param)) {
            $objCompraItem = new CompraItem();
            $objCompraItem->setear($param['idCompraItem'], $param['idProducto'], $param['idCompra'], $param['ciCantidad']);
        }
        return $objCompraItem;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idCompraItem'])) {
            $obj = new CompraItem();
            $obj->setear($param['idCompraItem'], null, null, null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idCompraItem'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     *
     * @param array $param
     * @return boolean
     */
    public function alta($param)
    {
        $resp = false;
        $param['idCompraItem'] = null;
        $objCompraItem = $this->cargarObjeto($param);
        if ($objCompraItem != null) {
            if ($objCompraItem->insertar()) {
                $resp = true;
            }
        }
        return $resp;
    }
    /**
     * permite eliminar un objeto
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objCompraItem = $this->cargarObjetoConClave($param);
            if ($objCompraItem != null and $objCompraItem->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objCompraItem = $this->cargarObjeto($param);
            if ($objCompraItem != null and $objCompraItem->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param = "")
    {
        $where = " true ";
        if ($param <> null) {
            if (isset($param["idCompraItem"])) {
                $where .= " and idCompraItem =" . $param["idCompraItem"];
            }
            if (isset($param["idCompra"])) {
                $where .= " and idCompra =" . $param["idCompra"];
            }
            if (isset($param["idProducto"])) {
                $where .= " and idProducto =" . $param["idProducto"];
            }
        }
        $objCompraItem = new CompraItem();
        $arregloCompraItem = $objCompraItem->listar($where);
        return $arregloCompraItem;
    }

    public function eliminarProductoCarrito($datos) {
        $arrayCompraItem = $this->buscar($datos);
        if ($arrayCompraItem[0]->getCantidad() == $datos["ciCantidad"]) {
            if ($this->baja($datos)) {
                echo json_encode(array('success' => 1));
            } else {
                echo json_encode(array('success' => 0));
            }
        } else {
            $cantStockTot = $arrayCompraItem[0]->getCantidad() - $datos["ciCantidad"];
            $datos["ciCantidad"] = $cantStockTot;
            $datos["idProducto"] = $arrayCompraItem[0]->getObjProducto()->getIdProducto();
            $datos["idCompra"] = $arrayCompraItem[0]->getObjCompra()->getIdCompra();
            if ($this->modificacion($datos)) {
                echo json_encode(array('success' => 1));
            } else {
                echo json_encode(array('success' => 0));
            }
        }
    }

    public function obtenerProductosMisCompras($datos) {
        $arrayCompraItem = $this->buscar($datos);
        if ($arrayCompraItem != null) {
            return $this->arrayArmadoJS($arrayCompraItem);
        }
        return null;
    }

    private function arrayArmadoJS($arrayCompraItem) {
        $arrayJS = [];
        foreach ($arrayCompraItem as $compraItem) {
            $param = [
                "Nombre" => $compraItem->getObjProducto()->getNombre(),
                "Descripcion" => $compraItem->getObjProducto()->getDetalle(),
                "Precio" => $compraItem->getObjProducto()->getProPrecio(),
                "Cantidad" => $compraItem->getCantidad(),
                "UrlImagen" => $compraItem->getObjProducto()->getUrlImagen(),
            ];
            array_push($arrayJS, $param);
        }
        return $arrayJS;
    }
}
