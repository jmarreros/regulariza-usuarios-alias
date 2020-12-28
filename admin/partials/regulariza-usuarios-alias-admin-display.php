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

 include_once PLUGIN_DIR .'/includes/class-proceso-correcion-usuarios.php';

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

// Batch Process
$per_batch = 500;
$step    = isset( $_GET['step'] )  ? absint( $_GET['step'] )  : 1;
$total   = isset( $_GET['total'] ) ? absint( $_GET['total'] ) : false;
$passed = round( ( ($step - 1) * $per_batch ), 0 );

if ( isset($_GET['paso']) && $_GET['paso'] == '5' ):
?>
<div class="wrap">
		<div id="dcms-processing">
			<p>El proceso a comenzado</p>
			<?php if( ! empty( $total ) ) : ?>
				<p><strong>Estamos en el step batch : <?php echo $step ?> y total <?php echo $total?></strong></p>
			<?php endif; ?>
		</div>
		<script type="text/javascript">

            <?php
                if ( ! $total ){
                    $regulariza = new Proceso_Correccion_Usuarios();
                    $total = $regulariza->batch_get_total_tmp_table();
                    // $total = $regulariza->batch_get_total();
                    error_log(print_r("El Total: ".$total,true));
                }
            ?>

            setTimeout(() => {
                    document.location.href = "/wp-admin/admin.php?page=regulariza-usuarios&action=processing&step<?php echo $step; ?>&total=<?php echo $total; ?>";
            }, 1000);
		</script>
	</div>

<?php
endif;

if( isset( $_GET['action'] ) && 'processing' == $_GET['action'] ) {
    echo "<h2>Procesando...".$step."</h2>";

    if ( $passed <= $total ){

        $regulariza = new Proceso_Correccion_Usuarios();
        $regulariza->batch_regulariza_crear_usuarios($step, $per_batch);
        // $regulariza->batch_regulariza_entrada_usuario($step, $per_batch);

        error_log("Estamos en el paso ".$step);
        $step++;
        wp_redirect( admin_url( 'admin.php?page=regulariza-usuarios&action=processing&step='.$step.'&total='.$total) ); exit;
    } else {
        wp_redirect( admin_url( 'admin.php?page=regulariza-usuarios&mgs=completado') ); exit;
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
<h2>Batch Process</h2>
<form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" >
    <label for="">Proceso final de actualización por batch</label>
    <br>
    <br>
	<input type="hidden" name="action" value="process_form_5">
	<input class="button button-primary" type="submit" name="submit" value="Paso 5">
</form>

