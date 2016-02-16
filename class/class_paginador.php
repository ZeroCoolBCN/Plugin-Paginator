<?php

class paginador {

    /**
     *
     * @var type 
     */
    private $_datos;
    private $_paginacion;
    private $_cuerpo;
    private $_encabezado;
    private $_table;
    private $_paginador;

    public function __construct() {
        $this->_datos = array();
        $this->_paginacion = array();
    }


    /**
     * 
     * @param string $query
     * @param type $pagina
     * @param type $limite
     * @return type
     */
    public function paginar($query, $pagina = false, $limite = false) {

        if ($limite && is_numeric($limite)) {
            $limite = $limite;
        } else {
            $limite = 20;
        }

        if ($pagina && is_numeric($pagina)) {
            $pagina = $pagina;
            $inicio = ($pagina - 1) * $limite;
        } else {
            $pagina = 1;
            $inicio = 0;
        }

        $consulta = Mysql::consulta($query);
        $registros = $consulta->num_rows;

        $total = ceil($registros / $limite);
        $query = $query . " LIMIT $inicio, $limite";
        $consulta = Mysql::consulta($query);
        $total_paginado = $consulta->num_rows;
        $total_campos = $consulta->field_count;

        $fields = mysqli_fetch_fields($consulta);
        /* encabezado de la tabla */
        while ($finfo = $consulta->fetch_field()) {

            $this->_encabezado .= "<th>".$finfo->name."</th>";

        }

        /* cuerpo de la tabla */
        for ($j = 0; $j < $total_paginado; $j++) {
            $this->_cuerpo .= "<tr>";
            for ($k = 0; $k < $total_campos; $k++) {
                $this->_cuerpo .= "<td>" . utf8_encode(mysqli_result($consulta, $j, $k)) . "</td>";
            }
            $this->_cuerpo .= "</tr>";
        }

        $paginacion = array();
        $paginacion['actual'] = $pagina;
        $paginacion['total'] = $total;

        if ($pagina > 1) {
            $paginacion['primero'] = 1;
            $paginacion['anterior'] = $pagina - 1;
        } else {
            $paginacion['primero'] = '';
            $paginacion['anterior'] = '';
        }

        if ($pagina < $total) {
            $paginacion['ultimo'] = $total;
            $paginacion['siguiente'] = $pagina + 1;
        } else {
            $paginacion['ultimo'] = '';
            $paginacion['siguiente'] = '';
        }

        $this->_paginacion = $paginacion;
        return $this->_encabezado;
    }

    /**
     * 
     * @param type $limite
     * @return type
     */
    public function getRangoPaginacion($limite = false) {
        if ($limite && is_numeric($limite)) {
            $limite = $limite;
        } else {
            $limite = 16;
        }

        $total_paginas = $this->_paginacion['total'];
        $pagina_seleccionada = $this->_paginacion['actual'];
        $rango = ceil($limite / 2);
        $paginas = array();

        $rango_derecho = $total_paginas - $pagina_seleccionada;

        if ($rango_derecho < $rango) {
            $resto = $rango - $rango_derecho;
        } else {
            $resto = 0;
        }

        $rango_izquierdo = $pagina_seleccionada - ($rango + $resto);

        for ($i = $pagina_seleccionada; $i > $rango_izquierdo; $i--) {
            if ($i == 0) {
                break;
            }
            $paginas[] = $i;
        }

        sort($paginas);

        if ($pagina_seleccionada < $rango) {
            $rango_derecho = $limite;
        } else {
            $rango_derecho = $pagina_seleccionada + $rango;
        }

        for ($i = $pagina_seleccionada + 1; $i < $rango_derecho; $i++) {
            if ($i > $total_paginas) {
                break;
            }

            $paginas[] = $i;
        }
        $this->_paginacion['rango'] = $paginas;

        return $this->_paginacion['rango'];
    }

    /**
     * 
     * @return boolean
     */
    public function getPaginacion() {
        if ($this->_paginacion) {

            /* PRIMERO */
            if ($this->_paginacion['primero']) {
                $this->_paginador .= '<li><a href="#" class="primero" data-id="' . $this->_paginacion['primero'] . '" id="primero">Primero</a></li>';
            } else {
                
            }

            /* Anterior */

            if ($this->_paginacion['anterior']) {
                $this->_paginador .= '<li><a href="#" class="anterior" id="anterior" data-id="' . $this->_paginacion['anterior'] . '">Anterior</a></li>';
            } else {
                
            }

            /* For para las paginas */
            for ($i = 0; $i < count($this->getRangoPaginacion()); $i++) {
                if ($this->_paginacion['actual'] != $this->_paginacion['rango'][$i]) {
                    $this->_paginador .= '<li><a id="pagina" class="pagina"  href="#" data-id="' . $this->_paginacion['rango'][$i] . '">' . $this->_paginacion['rango'][$i] . '</a></li>';
                } else {
                    $this->_paginador .= '<li class="active"><a href="#">' . $this->_paginacion['rango'][$i] . '</a></li>';
                }
            }

            /* Siguiente */

            if ($this->_paginacion['siguiente']) {
                $this->_paginador .= '<li><a  href="#" class="siguiente" data-id="' . $this->_paginacion['siguiente'] . '" id="siguiente">Siguiente</a></li>';
            } else {
                
            }



            /* Ultimo */

            if ($this->_paginacion['ultimo']) {
                $this->_paginador .= '<li><a href="#" class="ultimo" data-id="' . $this->_paginacion['ultimo'] . '" id="ultimo">Ultimo</a></li>';
            } else {
                
            }

            return $this->_paginador;
        } else {
            return false;
        }
    }

    public function tablaBootstrap() {

        $this->_table .= '<table class="table table-bordered">
            <thead><tr>' . $this->_encabezado . '</tr></thead>
                <tbody>' . $this->_cuerpo . '</tbody>
                </table>
                
                <ul class="pagination">
                ' . $this->getPaginacion() . '
                    </ul>
               ';
        return $this->_table;
    }

}

class paginadorDelete {

    /**
     *
     * @var type 
     */
    private $_datos;
    private $_paginacion;
    private $_cuerpo;
    private $_encabezado;
    private $_table;
    private $_paginador;

    public function __construct() {
        $this->_datos = array();
        $this->_paginacion = array();
    }


    /**
     * 
     * @param string $query
     * @param type $pagina
     * @param type $limite
     * @return type
     */
    public function paginar($query,$id, $pagina = false, $limite = false) {

        if ($limite && is_numeric($limite)) {
            $limite = $limite;
        } else {
            $limite = 20;
        }

        if ($pagina && is_numeric($pagina)) {
            $pagina = $pagina;
            $inicio = ($pagina - 1) * $limite;
        } else {
            $pagina = 1;
            $inicio = 0;
        }

        $consulta = Mysql::consulta($query);
        $registros = $consulta->num_rows;

        $total = ceil($registros / $limite);
        $query = $query . " LIMIT $inicio, $limite";
        $consulta = Mysql::consulta($query);
        $total_paginado = $consulta->num_rows;
        $total_campos = $consulta->field_count;

        $fields = mysqli_fetch_fields($consulta);
        /* encabezado de la tabla */
        while ($finfo = $consulta->fetch_field()) {

            $this->_encabezado .= "<th>".$finfo->name."</th>";

        }

        /* cuerpo de la tabla */
        for ($j = 0; $j < $total_paginado; $j++) {
            $this->_cuerpo .= "<tr>";
            for ($k = 0; $k < $total_campos; $k++) {
                $this->_cuerpo .= "<td>" . utf8_encode(mysqli_result($consulta, $j, $k)) . "</td>";
            }
            $this->_cuerpo .='<td><a href="#" data-id="'.  mysqli_result($consulta, $j, $id).'"  data-pagina="'.$pagina.'" class="eliminar btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $this->_cuerpo .= "</tr>";
        }

        $paginacion = array();
        $paginacion['actual'] = $pagina;
        $paginacion['total'] = $total;

        if ($pagina > 1) {
            $paginacion['primero'] = 1;
            $paginacion['anterior'] = $pagina - 1;
        } else {
            $paginacion['primero'] = '';
            $paginacion['anterior'] = '';
        }

        if ($pagina < $total) {
            $paginacion['ultimo'] = $total;
            $paginacion['siguiente'] = $pagina + 1;
        } else {
            $paginacion['ultimo'] = '';
            $paginacion['siguiente'] = '';
        }

        $this->_paginacion = $paginacion;
        return $this->_encabezado;
    }

    /**
     * 
     * @param type $limite
     * @return type
     */
    public function getRangoPaginacion($limite = false) {
        if ($limite && is_numeric($limite)) {
            $limite = $limite;
        } else {
            $limite = 16;
        }

        $total_paginas = $this->_paginacion['total'];
        $pagina_seleccionada = $this->_paginacion['actual'];
        $rango = ceil($limite / 2);
        $paginas = array();

        $rango_derecho = $total_paginas - $pagina_seleccionada;

        if ($rango_derecho < $rango) {
            $resto = $rango - $rango_derecho;
        } else {
            $resto = 0;
        }

        $rango_izquierdo = $pagina_seleccionada - ($rango + $resto);

        for ($i = $pagina_seleccionada; $i > $rango_izquierdo; $i--) {
            if ($i == 0) {
                break;
            }
            $paginas[] = $i;
        }

        sort($paginas);

        if ($pagina_seleccionada < $rango) {
            $rango_derecho = $limite;
        } else {
            $rango_derecho = $pagina_seleccionada + $rango;
        }

        for ($i = $pagina_seleccionada + 1; $i < $rango_derecho; $i++) {
            if ($i > $total_paginas) {
                break;
            }

            $paginas[] = $i;
        }
        $this->_paginacion['rango'] = $paginas;

        return $this->_paginacion['rango'];
    }

    /**
     * 
     * @return boolean
     */
    public function getPaginacion() {
        if ($this->_paginacion) {

            /* PRIMERO */
            if ($this->_paginacion['primero']) {
                $this->_paginador .= '<li><a href="#" class="primero" data-id="' . $this->_paginacion['primero'] . '" id="primero">Primero</a></li>';
            } else {
                
            }

            /* Anterior */

            if ($this->_paginacion['anterior']) {
                $this->_paginador .= '<li><a href="#" class="anterior" id="anterior" data-id="' . $this->_paginacion['anterior'] . '">Anterior</a></li>';
            } else {
                
            }

            /* For para las paginas */
            for ($i = 0; $i < count($this->getRangoPaginacion()); $i++) {
                if ($this->_paginacion['actual'] != $this->_paginacion['rango'][$i]) {
                    $this->_paginador .= '<li><a id="pagina" class="pagina"  href="#" data-id="' . $this->_paginacion['rango'][$i] . '">' . $this->_paginacion['rango'][$i] . '</a></li>';
                } else {
                    $this->_paginador .= '<li class="active"><a href="#">' . $this->_paginacion['rango'][$i] . '</a></li>';
                }
            }

            /* Siguiente */

            if ($this->_paginacion['siguiente']) {
                $this->_paginador .= '<li><a  href="#" class="siguiente" data-id="' . $this->_paginacion['siguiente'] . '" id="siguiente">Siguiente</a></li>';
            } else {
                
            }



            /* Ultimo */

            if ($this->_paginacion['ultimo']) {
                $this->_paginador .= '<li><a href="#" class="ultimo" data-id="' . $this->_paginacion['ultimo'] . '" id="ultimo">Ultimo</a></li>';
            } else {
                
            }

            return $this->_paginador;
        } else {
            return false;
        }
    }

    public function tablaBootstrap() {

        $this->_table .= '<table class="table table-bordered">
            <thead><tr>' . $this->_encabezado . '</tr></thead>
                <tbody>' . $this->_cuerpo . '</tbody>
                </table>
                
                <ul class="pagination">
                ' . $this->getPaginacion() . '
                    </ul>
               ';
        return $this->_table;
    }

}



