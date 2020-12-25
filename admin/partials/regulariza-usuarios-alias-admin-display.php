<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://decodecms.com
 * @since      1.0.0
 *
 * @package    Regulariza_Usuarios_Alias
 * @subpackage Regulariza_Usuarios_Alias/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<h2>Regulariza los usuarios</h2>

<?php
if ( isset($_GET['paso']) ){
	if ( $_GET['paso'] != '0'){
		echo "<p><strong> ✅ Paso " . $_GET['paso']  ." completado </strong></p><br>";
	}
	else {
		echo "<p><strong> ⛔ Hubo un error </strong></p><br>";
	}
}
?>

<!-- Paso 1 -- Limpieza de tablas  -->
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Corrección de tablas wp_options fuente</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_1">
	<input class="button button-primary" type="submit" name="submit" value="Paso 1">
</form>
<br>
<hr>

<!-- Paso 2 -- Creación de tabla temporal  y llenado de datos complementarios -->
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Creación y llenado de tabla temporal</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_2">
	<input class="button button-primary" type="submit" name="submit" value="Paso 2">
</form>

<br>
<hr>

<!-- Paso 3 -- Creación de usuarios  -->
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Creación usuarios WordPress</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_3">
	<input class="button button-primary" type="submit" name="submit" value="Paso 3">
</form>
<br>
<hr>


<!-- Paso 3_1 -- Eliminacion de usuarios
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Eliminación de Usuarios</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_3_1">
	<input class="button button-secondary" type="submit" name="submit" value="Paso 3_1">
</form>
<br>
<hr>
-->

<!-- Paso 3 -- Creación de usuarios  -->
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Relacionar usuarios con entradas</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_4">
	<input class="button button-primary" type="submit" name="submit" value="Paso 4">
</form>


<br>
<hr>
<!-- <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Actualización de las entradas</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_5">
	<input class="button button-primary" type="submit" name="submit" value="Paso 5">
</form> -->

