<?php

/**
 * CakePHP MatriculasController
 * @author admin
 */
class MatriculasController extends AppController {   
    public $uses = array("Matricula");
    
    public $components = array("Paginator");

    public $paginate = array(
        "limit" => 10,
        "order" => array(
            "Matricula.fecha" => "asc"
        ),
        "recursive" => 3,
        "conditions" => array(
            "Matricula.estado" => 1
        )
    );

    public function index() {
        $this->layout = "admin";
                
        $this->set("aniolectivos", $this->Matricula->Seccion->Aniolectivo->find("list", array(
            "fields" => array("Aniolectivo.idaniolectivo", "Aniolectivo.descripcion"),
            "conditions" => array("Aniolectivo.estado" => 1)
        )));
        
        $this->set("niveles", $this->Matricula->Seccion->Grado->Nivel->find("list", array(
            "fields" => array("Nivel.idnivel", "Nivel.descripcion"),
            "conditions" => array("Nivel.estado" => 1)
        )));
        
        $matriculas = array();
        
        $idaniolectivo = 0;
        if($this->request->is(array("post", "put"))) {
            if(!empty($this->request->data["Aniolectivo"]["idaniolectivo"])) {
                $this->paginate["conditions"]["Seccion.idaniolectivo"] = $this->request->data["Aniolectivo"]["idaniolectivo"];
                $idaniolectivo = $this->request->data["Aniolectivo"]["idaniolectivo"];
            }
            if(isset($this->request->data["Grado"]["idgrado"])) {
                $idgrado = $this->request->data["Grado"]["idgrado"];
                $this->paginate["conditions"]["Seccion.idgrado"] = $idgrado;
            }
            
            if(isset($this->request->data["Matricula"]["idseccion"])) {
                $idseccion = $this->request->data["Matricula"]["idseccion"];
                $this->paginate["conditions"]["Matricula.idseccion"] = $idseccion;
            }
        } else {
            $idaniolectivo = $this->Matricula->Seccion->Aniolectivo->getAniolectivoActual();
        }
        
        $this->Paginator->settings = $this->paginate;
        $matriculas = $this->Paginator->paginate();
        
        $this->set(compact("idaniolectivo"));
        $this->set(compact("matriculas"));
    }
    
    public function add() {
        $this->layout = "admin";
        
        $this->set("aniolectivos", $this->Matricula->Seccion->Aniolectivo->find("list", array(
            "fields" => array("Aniolectivo.idaniolectivo", "Aniolectivo.descripcion"),
            "conditions" => array("Aniolectivo.estado" => 1)
        )));
        
        $this->set("niveles", $this->Matricula->Seccion->Grado->Nivel->find("list", array(
            "fields" => array("Nivel.idnivel", "Nivel.descripcion"),
            "conditions" => array("Nivel.estado" => 1)
        )));
        
        $idaniolectivo = $this->Matricula->Seccion->Aniolectivo->getAniolectivoActual();
        $this->set(compact("idaniolectivo"));
        if ($this->request->is(array("post", "put"))) {
            
            $grado = $this->Matricula->Seccion->Grado->findByIdgrado($this->request->data["Grado"]["idgrado"]);
            $n_capacidad = $grado["Grado"]["capacidad"];
            $n_matriculados = $this->Matricula->find("count", array(
               "conditions" => array("Matricula.estado" => 1, "Matricula.idseccion" => $this->request->data["Matricula"]["idseccion"]) 
            ));
            if($n_matriculados == $n_capacidad) {
                $this->Session->setFlash(__("Ya se ha alcanzado el limite máximo de capacidad para esta sección."), "flash_bootstrap");
                return;
            }
            $ds = $this->Matricula->getDataSource();
            $ds->begin();
            $this->Matricula->create();
            if ($this->Matricula->save($this->request->data)) {
                $idmatricula = $this->Matricula->id;
                foreach($this->request->data["Pago"] as $key => $pago) {
                    $this->request->data["Pago"][$key]["idmatricula"] = $idmatricula;
                    $this->request->data["Pago"][$key]["deuda"] = $this->request->data["Pago"][$key]["monto"];
                }
                if($this->Matricula->Pago->saveMany($this->request->data["Pago"])) {
                    $ds->commit();
                    $this->Session->setFlash(__("El Alumno ha sido Matriculado correctamente."), "flash_bootstrap");
                    return $this->redirect(array("action" => "index"));
                }
            }
            $this->Session->setFlash(__("No fue posible matricular al Alumno."), "flash_bootstrap");
        }
    }
    
    public function view($id = null) {
        $this->layout = "admin";
                
        if (!$id) {
            throw new NotFoundException(__("Matricula inválida"));
        }
        $this->Matricula->recursive = 3;
        $matricula = $this->Matricula->findByIdmatricula($id);
        if (!$matricula) {
            throw new NotFoundException(__("Matricula inválida"));
        } 
        $this->set(compact("matricula"));
    }
    
    public function delete($id = null) {
        if ($this->request->is("get")) {
            throw new MethodNotAllowedException();
        }
        $this->Matricula->id = $id;
        if ($this->Matricula->saveField("estado", 2)) {
            $this->Session->setFlash(__("La Matrícula de código: %s ha sido Deshabilitado.", h($id)), "flash_bootstrap");
            return $this->redirect(array("action" => "index"));
        }
    }
}