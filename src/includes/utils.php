<?php

function generaErroresGlobalesFormulario($errores)
{
	$html = '';
	$keys = array_filter(array_keys($errores), function($v) {
		return is_numeric($v);
	});
	if (count($keys) > 0) {
		$html = '<ul class="errores">';
		foreach($keys as $key) {
			$html .= "<li class='errorForm'>{$errores[$key]}</li>";
		}
		$html .= '</ul>';
	}
	return $html;
}

function generarError($campo, $errores)
{
    return isset($errores[$campo]) ? "<span class=\"form-field-error\">{$errores[$campo]}</span>": '';
}

function generaErroresCampos($campos, $errores) {
    $erroresCampos = [];
    foreach($campos as $campo) {
        $erroresCampos[$campo] = generarError($campo, $errores);
    }
    return $erroresCampos;
}