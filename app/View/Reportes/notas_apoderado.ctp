<!-- File: /app/View/Reportes/notas_apoderado.ctp -->
<?php 
    $this->extend("/Common/view");
    $this->assign("titulo", "Boleta de Notas");  
    
    $this->Html->addCrumb("Boleta de Notas", "/Reportes/notas");
?>
<?php
    echo $this->Form->create("Reporte", array("class" => "form-horizontal", "action" => "notas_apoderado_post"));
    echo $this->Form->input("idalumno", array(
        "label" => "Alumno",
        "options" => $alumnos,
        "empty" => "Selecciona uno"
    ));
    echo $this->Form->input("idaniolectivo", array(
        "label" => "Año Lectivo",
        "options" => $aniolectivos,
        "empty" => "Selecciona uno"
    ));
    echo $this->Form->input("idbimestre", array(
        "label" => "Bimestre",
        "options" => $bimestres,
        "empty" => "Selecciona uno"
    ));
    echo $this->Form->button("Reporte", array("class" => "btn btn-primary btn-large"));
    echo $this->Form->end();
?>