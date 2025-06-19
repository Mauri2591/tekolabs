<?php
class Desafios extends Conexion
{

    public function get_chellenges_pagina_x_desafio_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT desafios.id AS id_desafio, desafios.imagen, desafios.leyenda, paginas.nombre, paginas.personaje_principal, paginas.personaje_secundario, estados.estado, niveles.nivel FROM paginas LEFT JOIN desafios ON paginas.id = desafios.id_pagina LEFT JOIN rutas_paginas ON paginas.id = rutas_paginas.id_pagina LEFT JOIN estados ON desafios.id_estado = estados.id LEFT JOIN niveles ON desafios.id_nivel = niveles.id WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function get_vulnerabilidad_challenge_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT vulnerabilidades_o_tematicas.id,
                   vulnerabilidades_o_tematicas.nombre,
                   vulnerabilidades_o_tematicas.descripcion,
                   vulnerabilidades_o_tematicas.archivo_php,
                    vulnerabilidades_o_tematicas.ayuda,
                   vulnerabilidades_o_tematicas.solucion,
                   vulnerabilidades_o_tematicas.cve,
                   vulnerabilidades_o_tematicas.archivo_php
            FROM vulnerabilidades_o_tematicas
            INNER JOIN desafios ON vulnerabilidades_o_tematicas.id = desafios.id_vulnerabilidad_o_tematica
            WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function get_desafio_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT paginas.nombre,niveles.nivel FROM desafios LEFT JOIN paginas ON desafios.id_pagina=paginas.id LEFT JOIN niveles ON desafios.id_nivel=niveles.id
            WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, pdo::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function get_solucion_vulnerabilidad_challenge_x_id($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT vulnerabilidades_o_tematicas.solucion,
                   vulnerabilidades_o_tematicas.cve,
                   vulnerabilidades_o_tematicas.archivo_php
            FROM vulnerabilidades_o_tematicas
            INNER JOIN desafios ON vulnerabilidades_o_tematicas.id = desafios.id_vulnerabilidad_o_tematica
            WHERE desafios.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function get_nivel_desafio($id)
    {
        $conn = parent::get_conexion();
        $sql = "SELECT desafios.id_nivel AS id_nivel, desafios.id_estado, estados.estado FROM desafios LEFT JOIN estados ON desafios.id_estado=estados.id WHERE desafios.id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update_estado_esafio($id, $id_estado)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE desafios SET id_estado = :id_estado WHERE desafios.id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':id_estado', $id_estado, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function update_estados_desafios($id, $id_evento)
    {
        $conn = parent::get_conexion();
        $sql = "UPDATE desafios 
            SET id_estado = 1 
            WHERE id_estado = 0 
              AND id != :id 
              AND id_pagina IN (
                  SELECT id FROM paginas WHERE id_evento = :id_evento
              )";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function get_ultimo_desafio_activo($id)
    {
        $conn = parent::get_conexion();

        // 1. Obtener id_evento desde el id del desafío
        $sql = "SELECT p.id_evento
            FROM desafios d
            JOIN paginas p ON d.id_pagina = p.id
            WHERE d.id = :id
            LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evento) return null;

        $id_evento = $evento['id_evento'];

        // 2. Contar todos los desafíos activos del evento
        $sql = "SELECT 
                COUNT(d.id) AS total_activos,
                MAX(p.id) AS id_pagina, 
                :id_evento AS id_evento
            FROM desafios d
            JOIN paginas p ON d.id_pagina = p.id
            WHERE d.id_estado IN (1,2) AND p.id_evento = :id_evento";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function get_ultimo_valor_version_paginas_eventos()
    {
        $conn = parent::get_conexion();
        $sql = "SELECT id FROM version_paginas_eventos ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function inserar_usuario_desafio($usuario, $id_desafio, $id_version_paginas_eventos, $resolvio)
    {
        $conn = parent::get_conexion();
        $sql = "INSERT INTO usuarios_desafios (usuario, id_desafio, id_version_paginas_eventos,resolvio) 
            VALUES(:usuario, :id_desafio, :id_version_paginas_eventos,:resolvio)";
        $stmt = $conn->prepare($sql);

        if ($usuario === null || trim($usuario) === '') {
            $stmt->bindValue(':usuario', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':usuario', htmlspecialchars($usuario, ENT_QUOTES), PDO::PARAM_STR);
        }

        $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
        $stmt->bindValue(':id_version_paginas_eventos', $id_version_paginas_eventos, PDO::PARAM_INT);
        $stmt->bindValue(':resolvio', $resolvio, PDO::PARAM_STR);
        $stmt->execute();
    }


    public function reiniciar_desafios_y_version($id_desafio)
    {
        $conn = parent::get_conexion();

        // 1. Obtener id_evento e id_pagina del desafío actual
        $sql = "SELECT p.id_evento, p.id AS id_pagina
            FROM desafios d
            JOIN paginas p ON d.id_pagina = p.id
            WHERE d.id = :id_desafio
            LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evento) return false;

        $id_evento = $evento['id_evento'];
        $id_pagina_actual = $evento['id_pagina'];

        // 2. Ver cuántos desafíos hay con estado 2 (jugando) en todo el evento
        $sql = "SELECT COUNT(*) AS jugando
            FROM desafios d
            JOIN paginas p ON d.id_pagina = p.id
            WHERE p.id_evento = :id_evento AND d.id_estado = 2";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
        $stmt->execute();
        $estado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Solo si queda 1 en estado JUGANDO, hacemos reinicio
        if ((int)$estado['jugando'] === 1) {
            // 3. Insertar nueva versión
            $sqlPagina = "SELECT id FROM paginas WHERE id_evento = :id_evento LIMIT 1";
            $stmt = $conn->prepare($sqlPagina);
            $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
            $stmt->execute();
            $pagina = $stmt->fetch(PDO::FETCH_ASSOC);

            $sqlInsert = "INSERT INTO version_paginas_eventos (fk_pagina, fk_evento, fecha_version_evento)
                      VALUES (:fk_pagina, :fk_evento, NOW())";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bindValue(':fk_pagina', $pagina['id'], PDO::PARAM_INT);
            $stmt->bindValue(':fk_evento', $id_evento, PDO::PARAM_INT);
            $stmt->execute();

            // 4. Actualizar todos los desafíos a estado 1 excepto el actual (que sigue en 2)
            $sqlUpdate = "UPDATE desafios 
                      SET id_estado = 1 
                      WHERE id_estado = 0
                        AND id != :id_desafio
                        AND id_pagina IN (
                            SELECT id FROM paginas WHERE id_evento = :id_evento
                        )";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bindValue(':id_desafio', $id_desafio, PDO::PARAM_INT);
            $stmt->bindValue(':id_evento', $id_evento, PDO::PARAM_INT);
            $stmt->execute();
        }

        return true;
    }
}
