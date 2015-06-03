<?php

/**
 * CakePHP Concepto
 * @author Roberto
 */
class Concepto extends AppModel {
    public $primaryKey = "idconcepto";
    
    public $belongsTo = array(
        "Aniolectivo" => array(
            'foreignKey' => 'idaniolectivo'
        )
    );
    
    public $validate = array(
        "descripcion" => array(
            "notEmpty" => array(
                "rule" => "notEmpty",
                "message" => "No puede estar vacio"
            )
        )
    );
}