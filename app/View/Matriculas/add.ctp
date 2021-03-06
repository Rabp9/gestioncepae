<!-- File: /app/View/Maatriculas/add.ctp -->
<?php 
    $this->extend("/Common/add");
    $this->assign("titulo", "Matricular Alumno");
    $this->assign("accion", "Administrar Matriculas");
    
    $this->Html->addCrumb('Matriculas', '/Matriculas');
    $this->Html->addCrumb('Crear', '/Matriculas/add');
?>

<div class="inpanel tabs-above" id="yw0">
    <ul id="yw1" class="nav nav-tabs">
        <li>
            <a id="tabPagos" data-toggle="tab" href="#yw0_tab_4"><span class="modernpics">#</span> Pagos</a>
        </li>
        <li class="active">
            <a id="tabDatosMatricular" data-toggle="tab" href="#yw0_tab_5"><span class="modernpics">Z</span> Datos de Matrícula</a>
        </li>
    </ul>
    <div class="tab-content">
        <?php 
            echo $this->Form->create("Matricula", array("class" => "form-vertical"));
            $this->Form->inputDefaults(array("class" => "span4"));
            echo $this->Html->para("help-block", "Los campos con <span class='required'>*</span> son requeridos");          
        ?>
            <div id="yw0_tab_5" class="tab-pane fade active in">
                <div class="info-panel">
                <?php 
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
                    echo $this->Form->input('Grado.idgrado', array(
                        "label" => "Grado",
                        "type" => "select",
                        "empty" => "Selecciona uno"
                    ));
                    echo $this->Form->input('idseccion', array(
                        "label" => "Sección",
                        "type" => "select",
                        "empty" => "Selecciona uno"
                    ));
                    echo $this->element("getAlumnos", array("model" => "Matricula"));
                    echo $this->Form->label("observacion", "Observación");
                    echo $this->Form->textarea("observacion", array(
                        "rows" => 10,
                        "cols" => 30,
                        "class" => "span4"
                    ));
                ?>
                </div>
            </div>
            <div id="yw0_tab_4" class="tab-pane fade">
                <div class="info-panel">

                </div>
            </div>
        <?php
            echo $this->Form->button("Siguiente", array(
                "id" => "btnNext", 
                "type" => "button", 
                "class" => "btn btn-primary btn-large"
            ));
            echo $this->Form->button("Matricular", array(
                "id" => "btnMatricula", 
                "type" => "submit", 
                "class" => "btn btn-primary btn-large"
            ));
            echo $this->Form->end();
        ?>
    </div>
</div>

<?php
    $this->Js->get('#NivelIdnivel')->event('change', 
        "$('#MatriculaIdseccion').html('<option value>Selecciona uno</option>');"
    );
    
    $gradosByIdnivel = $this->Js->request(array(
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
    ));
    
    $this->Js->get('#NivelIdnivel')->event('change', $gradosByIdnivel);
    
    $this->Js->buffer($gradosByIdnivel);
?>

<?php
    $getByIdgrado = $this->Js->request(array(
        "controller" => "Secciones",
        "action" => "getByIdgrado"
    ), array(
        "update" => "#MatriculaIdseccion",
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
?>

<?php 
    $getAlumnos = $this->Js->request(array(
        "controller" => "Alumnos",
        "action" => "getAlumnos"
    ), array(
        "update" => "#dvBuscarAlumnos",
        "async" => true,
        "method" => 'post',
        "dataExpression" => true,
        "data" => $this->Js->serializeForm(array(
            "isForm" => false,
            "inline" => true
        ))
    ));
    
    $this->Js->get('#AniolectivoIdaniolectivo')->event('change', $getAlumnos);
    $this->Js->buffer($getAlumnos);
    
    $getFormByAniolectivo = $this->Js->request(array(
        "controller" => "Conceptos",
        "action" => "getFormByAniolectivo"
    ), array(
        "update" => "#yw0_tab_4 div.info-panel",
        "async" => true,
        "method" => 'post',
        "dataExpression" => true,
        "data" => $this->Js->serializeForm(array(
            "isForm" => true,
            "inline" => true
        ))
    ));
    
    $this->Js->get('#AniolectivoIdaniolectivo')->event('change', $getFormByAniolectivo);
    $this->Js->buffer($getFormByAniolectivo);
?>

<?php $this->Js->get('#MatriculaAddForm')->event("submit", 
        "if(confirm('No olvides de definir los Pagos a realizar. ¿Estás seguro de continuar?')) return true; else return false;"
    );
?>

<script>
    function eventFire(el, etype){
        if (el.fireEvent) {
            el.fireEvent('on' + etype);
        } else {
            var evObj = document.createEvent('Events');
            evObj.initEvent(etype, true, false);
            el.dispatchEvent(evObj);
        }
    }
    $(document).ready(function() {
        $("#btnMatricula").hide();
        $("#btnNext").click(function() {
            eventFire(document.getElementById('tabPagos'), 'click');
            $(this).hide();
            $("#btnMatricula").show();
        });
        
        $("#tabDatosMatricular").click(function() {
            $("#btnNext").show();
            $("#btnMatricula").hide();
        });
    });
</script>