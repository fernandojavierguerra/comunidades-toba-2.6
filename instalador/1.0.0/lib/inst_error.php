<?php

class inst_error extends Exception 
{
    public function __construct($mensaje, $code = 0) {
    	inst::logger()->error($mensaje);
        parent::__construct($mensaje, $code);
    }	
}

?>