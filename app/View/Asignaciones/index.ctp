<!-- File: /app/View/Asignaciones/index.ctp -->
<?php 
    $this->extend("/Common/view");
    $this->assign("titulo", "Administrar Asignaciones");
    
    $this->Html->addCrumb('Asignaciones', '/Asignaciones');
    $this->Html->addCrumb('Adiministrar', '/Asignaciones/index');
?>
<?php
    echo $this->Form->create("Asignacion");
    
    echo $this->Form->input("Aniolectivo.idaniolectivo", array(
        "label" => "Año Lectivo",
        "options" => $aniolectivos,
        "empty" => "Selecciona uno",
        "value" => $idaniolectivo
    ));
    echo $this->Form->input("Nivel.idnivel", array(
        "label" => "Nivel",
        "options" => $niveles,
        "empty" => "Selecciona uno"
    ));
    echo $this->Form->input("Grado.idgrado", array(
        "label" => "Grado",
        "type" => "select",
        "empty" => "Selecciona uno"
    ));
    echo $this->Form->input("idseccion", array(
        "label" => "Sección",
        "type" => "select",
        "empty" => "Selecciona uno"
    ));
    
    echo $this->Form->end();
?>
<table id="tbl-asignaciones" class="items table table-striped table-bordered table-condensed">
    <thead>
        <tr>
            <th id="user-grid_c0">Curso</th>
            <th id="user-grid_c1">Área</th>
            <th id="user-grid_c2">Docente</th>
            <th id="user-grid_c3">Nivel</th>
            <th id="user-grid_c4">Grado</th>
            <th id="user-grid_c5">Seccion</th>
            <th id="user-grid_c6">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($asignaciones as $asignacion) { ?>
        <tr>
            <td><?php echo $asignacion["Curso"]["descripcion"]; ?></td>
            <td><?php echo $asignacion["Curso"]["Area"]["descripcion"]; ?></td>
            <td><?php echo $asignacion["Docente"]["nombreCompleto"]; ?></td>
            <td><?php echo $asignacion["Seccion"]["Grado"]["Nivel"]["descripcion"]; ?></td>
            <td><?php echo $asignacion["Seccion"]["Grado"]["descripcion"]; ?></td>
            <td><?php echo $asignacion["Seccion"]["descripcion"]; ?></td>
            <td><?php echo $this->Html->link("<i class='icon-eye-open'></i>", array(
                "action" => "view", 
                $asignacion["Curso"]["idcurso"], $asignacion["Seccion"]["idseccion"]
            ), array(
                "escape" => false, 
                "title" => "Detalle", 
                "rel" => "tooltip"
            )) ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php 
    // Nivel
    $this->Js->get('#NivelIdnivel')->event('change', 
        "$('#AsignacionIdseccion').html('<option value>Selecciona uno</option>');"
    );
    $this->Js->get('#NivelIdnivel')->event('change', 
        "$('#GradoIdgrado').val('');"
    );
    
    $this->Js->get('#NivelIdnivel')->event('change', 
        $this->Js->request(array(
            "controller" => "Grados",
            "action" => "getByIdnivel"
        ), array(
            "update" => "#GradoIdgrado",
            "async" => true,
            "method" => 'post',
            "dataExpression" => true,
            "data" => $this->Js->serializeForm(array(
                "isForm" => true,
                "inline" => true
            ))
        ))
    );
?>
<?php
    // Secciones
    $getByIdgrado = $this->Js->request(array(
        "controller" => "Secciones",
        "action" => "getByIdgrado"
    ), array(
        "update" => "#AsignacionIdseccion",
        "async" => true,
        "method" => 'post',
        "dataExpression" => true,
        "data" => $this->Js->serializeForm(array(
            "isForm" => false,
            "inline" => true
        ))
    ));
    
    $this->Js->get('#GradoIdgrado')->event('change', 
        $getByIdgrado
    );
    $this->Js->get('#AniolectivoIdaniolectivo')->event('change', 
        $getByIdgrado
    );
    
    $this->Js->get('#AsignacionIdseccion')->event('change', 
        $this->Js->request(array(
            "controller" => "Asignaciones",
            "action" => "getAsignaciones"
        ), array(
            "update" => "#tbl-asignaciones",
            "async" => true,
            "method" => 'post',
            "dataExpression" => true,
            "data" => $this->Js->serializeForm(array(
                "isForm" => false,
                "inline" => true
            ))
        ))
    );
?>