<?php
class Paginas extends Conexion
{
    public function get_generos()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT paginas.nombre, generos.genero, generos.id as id_genero FROM paginas LEFT JOIN generos ON paginas.id_genero=generos.id WHERE paginas.id_estado=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function get_generos_por_evento_y_desafio($id_evento, $id_desafio)
    {
        $conn = parent::get_conexion();

        // Obtener el género actual de ese desafío
        $sql = "SELECT g.id, g.genero
        FROM generos g
        WHERE g.id_estado = 1
        AND (
            g.id NOT IN (
                SELECT p.id_genero
                FROM paginas p
                WHERE p.id_evento = :id_evento
            )
            OR g.id = (
                SELECT p.id_genero
                FROM desafios d
                JOIN paginas p ON d.id_pagina = p.id
                WHERE d.id = :id_desafio
                LIMIT 1
            )
        )
        ORDER BY g.genero ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function update_genero_challenge($id_desafio, $id_genero, $nombre)
    {
        $conn = parent::get_conexion();
        // Primero buscamos el id_pagina de ese desafío
        $sql = "UPDATE paginas 
            SET id_genero = :id_genero, nombre=:nombre 
            WHERE id = (SELECT id_pagina FROM desafios WHERE id = :id_desafio LIMIT 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_genero', $id_genero, PDO::PARAM_INT);
        $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
        $stmt->bindValue(':nombre', htmlspecialchars(strtoupper($nombre)), PDO::PARAM_STR);
        $stmt->execute();
    }

    public function get_audio_pagina()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT paginas.* FROM paginas INNER JOIN eventos ON paginas.id_evento = eventos.id WHERE eventos.id_estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function get_datos_combo_generos_evento_pagina_inicio()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT DISTINCT 
    g.id AS id_genero, 
    g.genero, 
    p.id AS id_pagina 
FROM 
    generos g 
JOIN 
    paginas p ON g.id = p.id_genero 
JOIN 
    desafios d ON p.id = d.id_pagina 
JOIN 
    eventos e ON p.id_evento = e.id 
WHERE 
    d.id_estado IN (0, 1, 2) 
    AND e.id_estado = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_chellenges_pagina($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT desafios.id AS id_desafio, desafios.imagen, paginas.nombre,paginas.id, paginas.personaje_principal, paginas.personaje_secundario, estados.estado, estados.id AS id_estado, niveles.nivel FROM paginas LEFT JOIN desafios ON paginas.id = desafios.id_pagina LEFT JOIN rutas_paginas ON paginas.id = rutas_paginas.id_pagina LEFT JOIN estados ON desafios.id_estado = estados.id LEFT JOIN niveles ON desafios.id_nivel = niveles.id WHERE paginas.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hay_desafio_jugando_en_evento($id_pagina)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT COUNT(*) AS jugando
            FROM desafios d
            JOIN paginas p ON d.id_pagina = p.id
            WHERE p.id_evento = (SELECT id_evento FROM paginas WHERE id = :id_pagina)
              AND d.id_estado = 2";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_pagina', $id_pagina, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['jugando'] > 0;
    }

    public function get_desafios_tabla_gestion()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
    usuarios_desafios.usuario,
    COUNT(*) AS cantidad_participaciones,
    SUM(CASE WHEN usuarios_desafios.resolvio = 'si' THEN 1 ELSE 0 END) AS cantidad_resueltos,
    MAX(usuarios_desafios.fecha_creacion) AS ultima_participacion
FROM 
    usuarios_desafios
LEFT JOIN 
    desafios ON usuarios_desafios.id_desafio = desafios.id
LEFT JOIN 
    paginas ON desafios.id_pagina = paginas.id
LEFT JOIN 
    eventos ON paginas.id_evento = eventos.id
WHERE 
    eventos.id_estado = 1
GROUP BY 
    usuarios_desafios.usuario
ORDER BY 
    cantidad_participaciones DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_cantidad_participantes_sorteo_principal()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
	usuarios_desafios.id,
    usuarios_desafios.usuario,
    COUNT(*) AS cantidad_participaciones,
    SUM(CASE WHEN usuarios_desafios.resolvio = 'si' THEN 1 ELSE 0 END) AS cantidad_resueltos,
    MAX(usuarios_desafios.fecha_creacion) AS ultima_participacion,
    CASE 
        WHEN SUM(CASE WHEN usuarios_desafios.resolvio = 'si' AND niveles.nivel = 'DIFICIL' THEN 1 ELSE 0 END) > 0 
        THEN 'DIFICIL' 
        ELSE NULL 
    END AS nivel
FROM 
    usuarios_desafios
LEFT JOIN 
    desafios ON usuarios_desafios.id_desafio = desafios.id
LEFT JOIN 
    paginas ON desafios.id_pagina = paginas.id
LEFT JOIN 
    eventos ON paginas.id_evento = eventos.id
LEFT JOIN 
    niveles ON desafios.id_nivel = niveles.id
WHERE 
	niveles.nivel = 'DIFICIL'
    AND eventos.id_estado = 1
    AND usuarios_desafios.usuario != 'ANONIMUS'
GROUP BY 
    usuarios_desafios.usuario
HAVING 
    SUM(CASE WHEN usuarios_desafios.resolvio = 'si' THEN 1 ELSE 0 END) > 0
ORDER BY 
    cantidad_participaciones DESC;";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_cantidad_participantes_sorteo_secundario()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
	usuarios_desafios.id,
    usuarios_desafios.usuario,
    COUNT(*) AS cantidad_participaciones,
    SUM(CASE WHEN usuarios_desafios.resolvio = 'si' THEN 1 ELSE 0 END) AS cantidad_resueltos,
    MAX(usuarios_desafios.fecha_creacion) AS ultima_participacion,
    CASE 
        WHEN SUM(CASE WHEN usuarios_desafios.resolvio = 'si' AND niveles.nivel = 'DIFICIL' THEN 1 ELSE 0 END) > 0 
        THEN 'DIFICIL' 
        ELSE NULL 
    END AS nivel
FROM 
    usuarios_desafios
LEFT JOIN 
    desafios ON usuarios_desafios.id_desafio = desafios.id
LEFT JOIN 
    paginas ON desafios.id_pagina = paginas.id
LEFT JOIN 
    eventos ON paginas.id_evento = eventos.id
LEFT JOIN 
    niveles ON desafios.id_nivel = niveles.id
WHERE 
    eventos.id_estado = 1
    AND usuarios_desafios.usuario != 'ANONIMUS'
GROUP BY 
    usuarios_desafios.usuario
HAVING 
    SUM(CASE WHEN usuarios_desafios.resolvio = 'si' THEN 1 ELSE 0 END) > 0
ORDER BY 
    cantidad_participaciones DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_nombre_usuario_ganador_sorteo($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT usuario FROM usuarios_desafios WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_desafios_grafico_gestion()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT COUNT(*) AS total, paginas.nombre 
        FROM usuarios_desafios LEFT JOIN desafios 
        ON usuarios_desafios.id_desafio = desafios.id 
        LEFT JOIN paginas ON desafios.id_pagina = paginas.id
        LEFT JOIN eventos ON paginas.id_evento=eventos.id
        WHERE usuarios_desafios.resolvio = 'si' AND eventos.id_estado = 1
        GROUP BY paginas.nombre";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_nombre_evento_activo()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT eventos.evento FROM eventos WHERE eventos.id_estado=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function get_cantidad_version_paginas_eventos($id_estado)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT COUNT(version_paginas_eventos.id) AS total, eventos.evento FROM version_paginas_eventos LEFT JOIN eventos ON version_paginas_eventos.fk_evento=eventos.id WHERE eventos.id_estado=:id_estado";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_estado', $id_estado, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function insert_imagen_challenge($imagen, $id_desafio)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE desafios SET imagen = :imagen WHERE id = :id_desafio";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insert_pagina($nombre, $personaje_principal, $personaje_secundario, $id_genero)
    {
        $conn = parent::get_conexion();
        $sql = "INSERT INTO paginas (nombre, personaje_principal, personaje_secundario,id_genero) VALUES (:nombre, :personaje_principal, :personaje_secundario,:id_genero)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nombre', htmlspecialchars(strtoupper($nombre), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue('personaje_principal', htmlspecialchars(strtoupper($personaje_principal), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue('personaje_secundario', htmlspecialchars(strtoupper($personaje_secundario), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue('id_genero', $id_genero, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function insert_evento($evento, $carpeta_imagen, $imagen, $fecha_evento)
    {
        $conn = parent::get_conexion();
        $sql = "INSERT INTO eventos (evento, carpeta_imagen, imagen, fecha_evento, id_estado) 
            VALUES (:evento, :carpeta_imagen, :imagen, :fecha_evento, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':evento', htmlspecialchars(strtoupper($evento), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue(':carpeta_imagen', $carpeta_imagen, PDO::PARAM_STR);
        $stmt->bindValue(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindValue(':fecha_evento', htmlspecialchars($fecha_evento, ENT_QUOTES), PDO::PARAM_STR);
        $stmt->execute();
    }

    public function get_count_total_id_estados_eventos()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT count(id_estado) as total FROM eventos WHERE id_estado=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function select_combo_generos_pagina()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT * FROM generos WHERE id_estado=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    public function get_eventos()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT * FROM eventos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_combo_categorias()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT generos.id,generos.genero FROM generos WHERE id_estado=1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_generos_usados_por_evento($id_evento)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT id_genero FROM paginas WHERE id_evento = :id_evento";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // devuelve array de id_genero
    }


    public function delete_evento($id)
    {
        $conn = parent::get_conexion();
        $sql = "DELETE FROM eventos WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function update_evento_activo_inactivo($id, $id_estado)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE eventos SET id_estado=:id_estado WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function update_leyenda_challenge($id, $leyenda)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE desafios SET leyenda=:leyenda WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':leyenda', htmlspecialchars($leyenda, ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insert_pagina_challenge($nombre, $personaje_principal, $personaje_secundario, $id_evento, $id_genero, $soundtrack, $id_estado)
    {
        $conn = parent::get_conexion();
        $sql = "INSERT INTO paginas (nombre, personaje_principal, personaje_secundario,id_evento, id_genero, soundtrack, id_estado) 
            VALUES (:nombre, :personaje_principal, :personaje_secundario,:id_evento, :id_genero, :soundtrack, :id_estado)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', htmlspecialchars(strtoupper($nombre), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue(':personaje_principal', htmlspecialchars(strtoupper($personaje_principal), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue(':personaje_secundario', htmlspecialchars(strtoupper($personaje_secundario), ENT_QUOTES), PDO::PARAM_STR);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->bindValue(':id_genero', $id_genero, PDO::PARAM_INT);
        $stmt->bindValue(':soundtrack', $soundtrack, PDO::PARAM_STR);
        $stmt->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function get_datos_icono_evento_pagina_inicio()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
        e.id,
        e.evento, 
        e.carpeta_imagen,
        e.imagen, 
        e.fecha_evento
        FROM eventos e
        WHERE e.id_estado = 1
        GROUP BY e.id, e.evento, e.imagen, e.fecha_evento
        LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function get_desafios_y_paginas($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
                 /*
                desafios.id_estado,
                desafios.id_usuario as desafio_usuario,
                desafios.imagen as desafio_imagen,
                desafios.id_estado as desafio_estado,
                paginas.id as pagina_id,
                paginas.id_estado as pagina_estado,
                paginas.fecha_creacion as pagina_fecha_creacion,
                paginas.fecha_finalizacion as pagina_fecha_finalizacion,
                paginas.id_evento as paginas_id_evento,
                paginas.id_genero as pagina_id_genero,
                eventos.id as id_evento
                */
                paginas.id as id_pagina,
                desafios.id_nivel as desafio_nivel,
                desafios.leyenda,
                paginas.id_genero,
                paginas.nombre as pagina_nombre,
                paginas.personaje_principal as pagina_personaje_principal,
                paginas.personaje_secundario as pagina_personaje_secundario,
                niveles.nivel,
                niveles.id as id_nivel,
                generos.genero,
                desafios.imagen as desafio_imagen,
				desafios.id as id_desafio,
                desafios.id_estado AS id_estado_desafio,
                vulnerabilidades_o_tematicas.nombre AS nombre_vulnerabilidad,
                modalidades.modalidad
            FROM desafios
            LEFT JOIN paginas ON desafios.id_pagina = paginas.id
            LEFT JOIN generos ON paginas.id_genero=generos.id
            LEFT JOIN eventos ON paginas.id_evento = eventos.id
            LEFT JOIN vulnerabilidades_o_tematicas ON desafios.id_vulnerabilidad_o_tematica=vulnerabilidades_o_tematicas.id
            /*LEFT JOIN eventos ON paginas.id_evento = eventos.id*/
            LEFT JOIN niveles ON desafios.id_nivel=niveles.id
            LEFT JOIN modalidades ON vulnerabilidades_o_tematicas.id_modalidad=modalidades.id
            WHERE paginas.id_evento = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // ✅ esta es la clave
    }
    public function get_modalidades()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT id,modalidad FROM modalidades";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_datos_desafio_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT 
    paginas.nombre,
    paginas.personaje_principal,
    paginas.personaje_secundario,
    generos.genero,
    desafios.id AS id_desafio,
    niveles.nivel,
    vulnerabilidades_o_tematicas.nombre AS nombre_vulnerabilidad,
    modalidades.modalidad
FROM paginas
LEFT JOIN generos ON paginas.id_genero = generos.id
LEFT JOIN desafios ON desafios.id_pagina = paginas.id
LEFT JOIN niveles ON desafios.id_nivel = niveles.id
LEFT JOIN vulnerabilidades_o_tematicas ON desafios.id_vulnerabilidad_o_tematica=vulnerabilidades_o_tematicas.id
LEFT JOIN modalidades ON vulnerabilidades_o_tematicas.id_modalidad=modalidades.id
WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }



    public function get_vulnerabilidad($id_modalidad, $id_nivel)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT  modalidades.modalidad,vulnerabilidades_o_tematicas.nombre, vulnerabilidades_o_tematicas.id as id_vulnerabilidad FROM vulnerabilidades_o_tematicas LEFT JOIN modalidades ON vulnerabilidades_o_tematicas.id_modalidad=modalidades.id WHERE id_modalidad=:id_modalidad AND vulnerabilidades_o_tematicas.id_nivel=:id_nivel";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id_modalidad', $id_modalidad, PDO::PARAM_INT);
        $stmt->bindValue('id_nivel', $id_nivel, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update_insertar_vulnerabilidad_challenge($id_vulnerabilidad_o_tematica, $id)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE desafios SET id_vulnerabilidad_o_tematica =  :id_vulnerabilidad_o_tematica  WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_vulnerabilidad_o_tematica', $id_vulnerabilidad_o_tematica, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function get_nombre_pagina($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT nombre FROM paginas WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_leyenda_desafio($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT desafios.leyenda
            FROM desafios
            WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(); // ✅ esta es la clave
    }
}
