<html>
    <table>
        <tr>
            <th>Matricula</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Delegación</th>
            <th>Categoria</th>
            <th>Adscripcion</th>
            <th>Nombre de la sesion</th>
            <th>Folio para obtener constancia</th>
            <th>Cumplio con asistencias</th>
            
        </tr>
        <?php
        foreach ($students as $student) {
            ?>
        <tr>
            <td><?php echo $student["usr_matricula"]; ?></td>
            <td><?php echo $student["fullname"]; ?></td>
            <td><?php echo $student["usr_correo"]; ?></td>
            <td><?php echo $student["nom_delegacion"]; ?></td>
            <td><?php echo $student["nom_categoria"]; ?></td>
            <td><?php echo $student["cve_depto_adscripcion"]." - " . $student["nom_depto_adscripcion"]; ?></td>
            <td><?php echo $sesiones['a_nombre'].' '.$sesiones['fecha']; ?></td>
            <td><?php echo $student["t_hash_constancia"]; ?></td>
            <td><?php echo isset($asistenciasCompletas[$student["usr_matricula"]])? 'Si': 'No'; ?></td>
        </tr>
            <?php
        }
        ?>
    </table>    
</html>
