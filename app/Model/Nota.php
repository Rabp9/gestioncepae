<?php

/**
 * CakePHP Nota
 * @author admin
 */
class Nota extends AppModel {
    public $primaryKey = "idnota";
    
    public $hasMany = array(
        "Detallenota" => array(
            "foreignKey" => "idnota"
        )
    );
}
