<?php

/**
 * Interface para la clase FormSearch.
 */
interface Form
{

    /**
     * Genera el formulario HTML de búsqueda.
     * 
     * @return string El código HTML del formulario de búsqueda.
     */
    public function generateForm();

    /**
     * Procesa los datos enviados por el formulario.
     * 
     * @return void
     */
    public function processForm();
}
