<?php

/**
 * Class for manage relation marca / modelo
 *
 * Gestiona los datos de marcas y modelos de la tabla wp_illantas_relations
 *
 * @since 			1.0.0
 * @package 		Illantas_Woo
 * @subpackage 		Illantas_Woo/includes
 * @author 			jmarreros
 */
class Proceso_Correccion_Usuarios {


    // Función para limpiar datos iniciales
    public function limpia_datos_iniciales(){
        global $wpdb;

        $sql = "update wp_postmeta set meta_value = TRIM(meta_value)
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);

        $sql = "update wp_postmeta set meta_value = REPLACE(meta_value, '\n','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);

        $sql = "update wp_postmeta set meta_value = REPLACE(meta_value, '\r','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);


        $sql = "update wp_postmeta set meta_value = REPLACE(meta_value, '\t','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);
    }

    // Creamos la tabla temporal
    public function crear_tabla_temporal(){
        global $wpdb;

        $sql = "DROP TABLE IF EXISTS wp_tmp_alias";
        $res = $wpdb->query($sql);
        error_log($res);

        // Creación tabla temporal
        $sql = "CREATE TABLE wp_tmp_alias(
          id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
          conteo int unsigned NOT NULL DEFAULT '0',
          alias varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
          username varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
          email varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
          id_user bigint(10) NOT NULL DEFAULT '0',
          PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $res =dbDelta( $sql );

        error_log(print_r($res,true));

        // Llenado incial de datos alias en tabla temporal
        $sql = "INSERT INTO wp_tmp_alias (alias, conteo)
                select distinct meta_value as nombre, count(meta_value) as conteo
                from wp_postmeta
                where meta_key = 'author_alias'
                group by meta_value
                order by conteo asc, nombre asc";

        $res = $wpdb->query($sql);
        error_log($res);

    }



}