<?php

function mysqli_result($res,$row=0,$col=0){
	$numrows = mysqli_num_rows($res);
	if ($numrows && $row <= ($numrows-1) && $row >=0){
		mysqli_data_seek($res,$row);
		$resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
		if (isset($resrow[$col])){
			return $resrow[$col];
		}
	}
	return false;
}

class Mysql
{
	public static function Consulta($query){

		$cn = new mysqli(SERVER, USER, PASS, BD);


		if ($cn->connect_errno){
			printf("Falló la conexión: %s\n", $cn->connect_error);
			exit();
		}

        $sql = mysqli_query($cn,$query);

        return $sql;

}


}

class Mysqli_Query {

	public static function limpiarCadena($valor) {
		$valor = str_ireplace("SELECT", "", $valor);
		$valor = str_ireplace("COPY", "", $valor);
		$valor = str_ireplace("DELETE", "", $valor);
		$valor = str_ireplace("DROP", "", $valor);
		$valor = str_ireplace("DUMP", "", $valor);
		$valor = str_ireplace(" OR ", "", $valor);
		$valor = str_ireplace("%", "", $valor);
		$valor = str_ireplace("LIKE", "", $valor);
		$valor = str_ireplace("--", "", $valor);
		$valor = str_ireplace("^", "", $valor);
		$valor = str_ireplace("[", "", $valor);
		$valor = str_ireplace("]", "", $valor);
		$valor = str_ireplace("\\", "", $valor);
		$valor = str_ireplace("!", "", $valor);
		$valor = str_ireplace("¡", "", $valor);
		$valor = str_ireplace("?", "", $valor);
		$valor = str_ireplace("&", "", $valor);
		$valor = str_ireplace("'", "", $valor);
		return $valor;
	}

	public static function RequestGet($val) {
		$data = addslashes($_GET[$val]);
		$var = utf8_decode($data);
		$datos = Mysqli_Query::limpiarCadena($var);
		return $datos;
	}

	public static function RequestPost($val) {
		$data = addslashes($_POST[$val]);
		$var = utf8_decode($data);
		$datos = Mysqli_Query::limpiarCadena($var);
		return $datos;
	}

	public static function RequestPostClave($val) {
		$data = addslashes($_POST[$val]);
		$var = utf8_decode($data);
		$datos = Mysqli_Query::limpiarCadena($var);
		$encriptar = md5($datos);
		return $encriptar;
	}

	public static function Guardar($tabla, $campos, $valores) {
		if (!$sql =Mysql::Consulta("insert into $tabla ($campos) VALUES($valores)")) {
		die("Error al insertar los datos en la tabla $tabla");
	}

		return $sql;
	}

	public static function Eliminar($tabla, $condicion) {
		if (!$sql = Mysql::consulta("DELETE FROM $tabla WHERE $condicion")) {
			die("Error al eliminar registros en la tabla $tabla");
		}

		return $sql;
	}

	public static function Actualizar($tabla, $campos, $condicion) {
		if (!$sql =Mysql::consulta("update $tabla set $campos where $condicion")) {
			die("Error al actualizar datos en la tabla $tabla");
		}
		return $sql;
	}

	public static function Session($tabla, $user, $fechahora, $condicion) {
		$sql = Mysql::consulta("select * from $tabla where $condicion");
		$num_reg = $sql->num_rows;
		if ($num_reg > 0) {
			session_start();
			$_SESSION['usuario'] = mysqli_result($sql, 0, $user);
			$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");
			$_SESSION['auntenticado'] = "si";
			echo "1";
		} else {

			die("Error en los datos de auntenticacion");
		}
	}

	public static function Comprobar_Session($url) {
		@session_start();
		if (empty($_SESSION['auntenticado'])) {
			echo '<script>' . 'window.location=' . "'$url'" . '</script>';
		} else {
			return $_SESSION['usuario'];
		}
	}

	public static function Session_inactiva($url) {
		if ($_SESSION["auntenticado"] != "si") {
			//si no está logueado lo envío a la página de autentificación
			header("Location: $url");
		} else {
			//sino, calculamos el tiempo transcurrido
			$fechaGuardada = $_SESSION["ultimoAcceso"];
			$ahora = date("Y-n-j H:i:s");
			$tiempo_transcurrido = (strtotime($ahora) - strtotime($fechaGuardada));

			//comparamos el tiempo transcurrido
			if ($tiempo_transcurrido >= 600) {
				//si pasaron 10 minutos o más
				session_destroy();
				// destruyo la sesión
				header("Location: $url");
				//envío al usuario a la pag. de autenticación
				//sino, actualizo la fecha de la sesión
			} else {
				$_SESSION["ultimoAcceso"] = $ahora;
			}
		}
	}

	public static function GenerarSelect($tabla, $campo, $id) {
		$select = "<select name='$campo' class=\"form-control\" required>" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . mysqli_result($sql, $i, $campo) . "</option>";
		}

		$select .= "</select>";
		return $select;
	}

	public static function ListGroupSelect($tabla, $campo, $id) {
		$sql = Mysql::consulta("select $id,$campo  from $tabla");
		$list_group = '<div class="list-group" data-toggle="items">';
		if ($sql->num_rows > 0) {
			for ($i = 0; $i <$sql->num_rows; $i++) {
				$list_group .= '<a href="#" class="list-group-item " data-id="' . mysqli_result($sql, $i, $id) . '">' . mysqli_result($sql, $i, $campo) . '</a>';
			}
			$list_group .= '</div>';
			return $list_group;
		}
	}

	public static function ListGroupSelectJson($tabla, $campo, $id, $indice) {
		$sql = Mysql::consulta("select $id,$campo  from $tabla");
		$list_group = '<div class="list-group" data-toggle="items">';

		if ($sql->num_rows > 0) {
			for ($i = 0; $i < $sql->num_rows; $i++) {
				$decifrado = json_decode(mysqli_result($sql, $i, $campo), true);
				$list_group .= '<a href="#" class="list-group-item " data-id="' . mysqli_result($sql, $i, $id) . '">' . utf8_decode($decifrado[$indice]) . '</a>';
			}
			$list_group .= '</div>';
			return $list_group;
		}
	}

	public static function GenerarSelectJson($tabla, $campo, $id, $indice) {
		$select = "<select name='$campo' id='$campo'  multiple=\"multiple\" class=\"form-control\" required>" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(mysqli_result($sql, $i, $campo), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice] . "</option>";
		}

		$select .= "</select>";
		return $select;
	}

	public static function GenerarSelectJson_n($tabla, $campo, $id, $indice) {
		$select = "<select name='$campo' id='$campo'   class=\"form-control\" required>" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(utf8_decode(mysqli_result($sql, $i, $campo)), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice] . "</option>";
			// echo json_last_error();
		}

		$select .= "</select>";
		return $select;
	}

	public static function GenerarSelectJson_p($tabla, $campo, $id_select, $id, $indice) {
		$select = "<select name='$campo' id='$id_select'  multiple=\"multiple\" required>" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(mysqli_result($sql, $i, $campo), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice] . "</option>";
		}

		$select .= "</select>";
		return $select;
	}

	public static function GenerarSelectJson_js_BootStrap($tabla, $campo, $id_select, $id, $indice) {
		$select = "<div class=\"col-md-5\"><select name='$campo' size=\"13\" id=\"$id_select\" class=\"form-control\" multiple=\"multiple\" >";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(utf8_encode(mysqli_result($sql, $i, $campo)), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice] . "</option>";
		}

		$select .= "</select>" . "</div>" . "<div class=\"col-md-2\">
					<button class=\"btn btn-default btn-block\" id=\"$id_select" . "_rightSelected\" type=\"button\"><i class=\"glyphicon glyphicon-chevron-right\"></i></button>
					<button class=\"btn btn-default btn-block\" id=\"$id_select" . "_leftSelected\" type=\"button\"><i class=\"glyphicon glyphicon-chevron-left\"></i></button>
	</div>" . "<div class=\"col-md-5\">
		<select name=\"to\" id=\"select_to\" class=\"form-control\" size=\"13\" multiple=\"multiple\"></select>
	</div>";

		return $select;
	}

	public static function GenerarSelectJson_nomultiple($tabla, $campo, $id, $indice) {
		$select = "<select name='$campo' id=\"$campo\" class=\"form-control\" required>" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(utf8_encode(mysqli_result($sql, $i, $campo)), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice] . "</option>";
		}

		$select .= "</select>";
		return $select;
	}

	public static function GenerarSelectJson_nomultiples($tabla, $campo, $id, $indice, $subidice) {
		$select = "<select name='$campo' id='$campo' class=\"form-control\" >" . "<option value=''>Selecciona:</option>";

		$sql = Mysql::consulta("select $campo,$id from $tabla");

		for ($i = 0; $i < $sql->num_rows; $i++) {
			$reg = json_decode(mysqli_result($sql, $i, $campo), true);
			$select .= "<option value='" . mysqli_result($sql, $i, $id) . "'>" . $reg[$indice][$subidice] . "</option>";
		}

		$select .= "</select>";
		return $select;
	}

	public static function __json_encode($data) {
		if (is_array($data) || is_object($data)) {
			$islist = is_array($data) && (empty($data) || array_keys($data) === range(0, count($data) - 1));

			if ($islist) {
				$json = '[' . implode(',', array_map('__json_encode', $data)) . ']';
			} else {
				$items = Array();
				foreach ($data as $key => $value) {
					$items[] = __json_encode("$key") . ':' . __json_encode($value);
				}
				$json = '{' . implode(',', $items) . '}';
			}
		} elseif (is_string($data)) {
			# Escape non-printable or Non-ASCII characters.
			# I also put the \\ character first, as suggested in comments on the 'addclashes' page.
			$string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"';
			$json = '';
			$len = strlen($string);
			# Convert UTF-8 to Hexadecimal Codepoints.
			for ($i = 0; $i < $len; $i++) {

				$char = $string[$i];
				$c1 = ord($char);

				# Single byte;
				if ($c1 < 128) {
					$json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1);
					continue;
				}

				# Double byte
				$c2 = ord($string[++$i]);
				if (($c1 & 32) === 0) {
					$json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128);
					continue;
				}

				# Triple
				$c3 = ord($string[++$i]);
				if (($c1 & 16) === 0) {
					$json .= sprintf("\\u%04x", (($c1 - 224)<<12) + (($c2 - 128)<<6) + ($c3 - 128));
					continue;
				}

				# Quadruple
				$c4 = ord($string[++$i]);
				if (($c1 & 8) === 0) {
					$u = (($c1 & 15)<<2) + (($c2>>4) & 3) - 1;

					$w1 = (54<<10) + ($u<<6) + (($c2 & 15)<<2) + (($c3>>4) & 3);
					$w2 = (55<<10) + (($c3 & 15)<<6) + ($c4 - 128);
					$json .= sprintf("\\u%04x\\u%04x", $w1, $w2);
				}
			}
		} else {
			# int, floats, bools, null
			$json = strtolower(var_export($data, true));
		}
		return $json;
	}

}

