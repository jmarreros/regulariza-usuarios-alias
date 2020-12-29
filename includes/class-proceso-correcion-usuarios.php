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
    // Cantidad de entradas que escribió el usuario como mínimo para filtrar y crear usuario
    private $cantidad_entradas = 10;



    // Paso 1 --- Función para limpiar datos iniciales
    public function limpia_datos_iniciales(){
        global $wpdb;
        $table_meta = $wpdb->prefix.'postmeta';

        $sql = "update $table_meta set meta_value = TRIM(meta_value)
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);

        $sql = "update $table_meta set meta_value = REPLACE(meta_value, '\n','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);

        $sql = "update $table_meta set meta_value = REPLACE(meta_value, '\r','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);


        $sql = "update $table_meta set meta_value = REPLACE(meta_value, '\t','')
        where meta_key = 'author_alias'";
        $res = $wpdb->query($sql);
        error_log($res);
    }


    // Paso 2 --- Creamos la tabla temporal
    public function crear_tabla_temporal(){
        global $wpdb;
        $table_tmp = $wpdb->prefix."tmp_alias";
        $table_meta = $wpdb->prefix.'postmeta';

        $sql = "DROP TABLE IF EXISTS $table_tmp";
        $res = $wpdb->query($sql);
        error_log($res);

        // Creación tabla temporal
        $sql = "CREATE TABLE $table_tmp(
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
        $sql = "INSERT INTO $table_tmp (alias, conteo)
                select distinct meta_value as nombre, count(meta_value) as conteo
                from $table_meta
                where meta_key = 'author_alias'
                group by meta_value
                order by conteo asc, nombre asc";

        $res = $wpdb->query($sql);
        error_log($res);

    }
    // Paso 2 --- Actualizamos los datos de username y correo en tabla temporal
    public function completar_datos_tabla_temporal(){
        global $wpdb;
        $table_name = $wpdb->prefix."tmp_alias";

        $items = $wpdb->get_results("SELECT * FROM `$table_name`");

        // Llenamos el nombre de usuario y el correo
        foreach($items as $item){
            if ( ! $item->alias ) continue;

            $username = sanitize_user($item->alias, true);
            $username = substr($username, 0, 42);
            $username = preg_replace('/[\s\t\n]/', ' ', $username);

            $username = str_replace('. Diana', 'Diana', $username);

            $email = 'a'.md5(substr($username,0, 12).random_int(1,10000)).'@asturbullatmp.org';

            $sql = $wpdb->prepare("UPDATE $table_name SET
                                    username = '%s',
                                    email = '%s'
                                    WHERE id = '%s'",
                                    $username, $email, $item->id);

            $result = $wpdb->query($sql);

            if ( ! $result ) {
                error_log("No se ejecutó: ". $item->id);
            }
        }
    }



    // Paso 3 ---  Creación de usuarios WordPress
    public function creacion_usuarios(){
        // global $wpdb;
        // $table_name = $wpdb->prefix.'tmp_alias';

        // $items = $wpdb->get_results("select id, alias, username, email from `$table_name` where username <>'' and conteo >= $this->cantidad_entradas");

        // foreach( $items as $item ){
        //     $username = $item->username;
        //     $id_user = username_exists( $username ) ;

        //     if ( ! $id_user ){

        //         $pass = md5(substr($username,0, 6).random_int(1,10));

        //         $user_data = [
        //             'user_pass'             => $pass,
        //             'user_login'            => $item->username,
        //             'user_nicename'         => sanitize_title($username),
        //             'user_email'            => $item->email,
        //             'display_name'          => $item->alias,
        //             'nickname'              => $item->alias,
        //             'role'                  => 'author',
        //         ];

        //         $id_user = wp_insert_user($user_data);

        //         if ( $id_user ){
        //             error_log("✅ Usuario insertado: ". $id_user);
        //             // Actualizamos la tabla temporal
        //             $this->update_tmp_table($id_user, $item->id);

        //         } else {
        //             error_log("⛔ No se pudo insertar ". $item->username);
        //         }

        //     } else {

        //         error_log("El usuario $username ya existe");
        //         // Actualizamos la tabla temporal
        //         $this->update_tmp_table($id_user, $item->id);

        //     }
        // }

        // Usuarios con el mismo username en la tabla temporal
        $this->regulariza_usuarios_iguales();
    }

    // Paso 4 --- Relaciona entrada con usuario
    public function regulariza_entrada_usuario(){
        global $wpdb;
        $table_name = $wpdb->prefix.'tmp_alias';
        $table_post = $wpdb->prefix.'posts';
        $table_meta = $wpdb->prefix.'postmeta';

        // Recuperamos los usuarios que tienen
        $sql = "SELECT alias, id_user FROM $table_name WHERE id_user > 0";
        $items = $wpdb->get_results($sql);

        foreach( $items as $item ){

            // $sql = $wpdb->prepare("UPDATE wp_posts SET post_author = %d WHERE id in
            //         (SELECT post_id FROM wp_postmeta WHERE  meta_key = 'author_alias' AND meta_value = '%s')", $item->id_user ,$item->alias);

            $sql = $wpdb->prepare("UPDATE $table_post SET post_author = %d WHERE id in
                    (SELECT post_id FROM $table_meta WHERE  meta_key = 'author_alias' AND meta_value = '%s')", $item->id_user, $item->alias);

            $res = $wpdb->query($sql);

            error_log("Se actualizaron $res en " . $item->id_user . " - " . $item->alias);
        }

    }

    // Función auxiliar de creación de usuarios
    private function update_tmp_table($id_user, $id_tmp){
        global $wpdb;
        $table_name = $wpdb->prefix."tmp_alias";;

        // Actualizamos el ID en la tabla wp_tmp_alias
        $sql = $wpdb->prepare("UPDATE $table_name SET
        id_user = %d
        WHERE id = %d",
        $id_user, $id_tmp);

        $result = $wpdb->query($sql);
        if ( ! $result ) error_log("🤦‍♂️ hubo un error al actualizar $table_name !!");
    }

    // Completamos para los usernames iguales el mismo id de usuario de WordPress en la tabla temporal
    private function regulariza_usuarios_iguales(){
        global $wpdb;
        $table_users = $wpdb->prefix.'users';
        $table_name = $wpdb->prefix."tmp_alias";

        $items = $wpdb->get_results("
            SELECT id as id_user, user_login FROM $table_users WHERE user_login IN (
            SELECT username from $table_name GROUP BY username HAVING count(username) > 1 and sum(id_user) > 0
            )");

        foreach( $items as $item ){
            $sql = $wpdb->prepare("UPDATE $table_name
                                    SET id_user = %d
                                    WHERE username = '%s'",
                                    $item->id_user, $item->user_login);
            $result = $wpdb->query($sql);
            if ( ! $result ) error_log("cantidad de filas: $result, al actualizar $table_name !! con: $item->user_login");
        }
    }

    // Proceso en Batch crear usuarios
    public function batch_regulariza_crear_usuarios($step, $number){
        global $wpdb;
        $table_name = $wpdb->prefix.'tmp_alias';

        $limit = ($step-1)*$number;

        $sql = "SELECT id, alias, username, email FROM `$table_name` WHERE username <>'' LIMIT $limit, $number";
        $items = $wpdb->get_results($sql);

        foreach( $items as $item ){
            $username = $item->username;
            $id_user = username_exists( $username ) ;

            if ( ! $id_user ){

                $pass = md5(substr($username,0, 6).random_int(1,10));

                $user_data = [
                    'user_pass'             => $pass,
                    'user_login'            => $item->username,
                    'user_nicename'         => sanitize_title($username),
                    'user_email'            => $item->email,
                    'display_name'          => $item->alias,
                    'nickname'              => $item->alias,
                    'role'                  => 'author',
                ];

                $id_user = wp_insert_user($user_data);

                if ( $id_user ){
                    if ( is_int($id_user) ){
                        error_log("✅ Usuario insertado: ". $id_user);
                        // Actualizamos la tabla temporal

                        $this->update_tmp_table($id_user, $item->id);
                    } else {
                        error_log("🔥 Error al crear usuario : ". $item->username);
                        error_log(print_r($id_user,true));
                        break;
                    }

                } else {
                    error_log("⛔ No se pudo insertarS". $item->username);
                }

            } else {

                error_log("El usuario $username ya existe");
                // Actualizamos la tabla temporal
                $this->update_tmp_table($id_user, $item->id);

            }
        }
    }

    // Proceso en Batch
    public function batch_regulariza_entrada_usuario($step, $number){
        global $wpdb;

        $table_users = $wpdb->prefix.'users';
        $table_name = $wpdb->prefix.'tmp_alias';
        $table_post = $wpdb->prefix.'posts';
        $table_meta = $wpdb->prefix.'postmeta';

        $limit = ($step-1)*$number;

        // En dos partes los mayores o iguales a 10 y luego el resto
        $sql = "SELECT alias, id_user FROM $table_name WHERE conteo < 10 and alias <> '' LIMIT $limit, $number";
        $items = $wpdb->get_results($sql);

        foreach( $items as $item ){

            $sql = $wpdb->prepare("UPDATE $table_post SET post_author = %d WHERE id in
                    (SELECT post_id FROM $table_meta WHERE  meta_key = 'author_alias' AND meta_value = '%s')",  $item->id_user, $item->alias);

            $res = $wpdb->query($sql);

            error_log("Se actualizaron $res en " . $item->id_user . " - " . $item->alias);
        }

    }

    // Funcion auxiliar para obtener el total
    public function batch_get_total_tmp_table(){
        global $wpdb;
        $table_name = $wpdb->prefix.'tmp_alias';

        $sql = "SELECT COUNT(*) as total FROM $table_name";
        $count = $wpdb->get_var($sql);

        return $count;
    }

    // Funcion auxiliar para obtener el total
    public function batch_get_total(){
        global $wpdb;
        $table_name = $wpdb->prefix.'tmp_alias';

        $sql = "SELECT COUNT(*) as total FROM $table_name WHERE id_user > 0";
        $count = $wpdb->get_var($sql);

        return $count;
    }

}









    // // Paso 3-1 Eliminación de usuarios, proceso opcional
    // public function eliminar_usuarios(){
    //     global $wpdb;
    //     $table_name = $wpdb->prefix.'tmp_alias';

    //     $items = $wpdb->get_results("select id, alias, username, email from `$table_name` where username <>'' and conteo >= $this->cantidad_entradas");

    //     error_log("⛔ NO ESTA HABILITADO Eliminación de usuarios 🙍");

    //     // foreach( $items as $item ){
    //     //     $username = $item->username;
    //     //     $id_user = username_exists( $username ) ;

    //     //     if (wp_delete_user($id_user) ){
    //     //         error_log('✅ Eliminado '. $id_user);
    //     //     } else{
    //     //         error_log('⚠️ Error '.$id_user);
    //     //     }
    //     // }

    // }
