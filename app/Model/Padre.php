<?php

/**
 * CakePHP Padre
 * @author admin
 */
class Padre extends AppModel {
    public $primaryKey = "idpadre";
    
    public $virtualFields = array(
        "nombreCompleto" => "CONCAT(Padre.apellidoPaterno, ' ', Padre.apellidoMaterno, ', ', Padre.nombres )"
    );
    
    public $belongsTo = array(
        "User" => array(
            'foreignKey' => 'iduser'
        )
    );
       
    public $hasMany = array(
        "AlumnosPadre" => array(
            "foreignKey" => "idpadre"
        )
    );
    
    public $validate = array(
        "nombres" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            )
        ),
        "apellidoPaterno" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            )
        ),
        "apellidoMaterno" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            )
        ),
        "dni" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            ),
            "numeric" => array(
                "rule" => "numeric",
                "message" => "Sólo permitido números"
            ),
            "minLength" => array(
                "rule" => array("minLength", 8),
                "message" => "El DNI debe tener 8 dígitos"
            )
        ),
        "fechaNac" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            )
        )
    );
}
