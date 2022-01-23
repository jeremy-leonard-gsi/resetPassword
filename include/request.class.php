<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of request
 *
 * @author jeremyl
 */
class request {
    
    private $filter;
    
    public function __construct($filter=FILTER_DEFAULT) {
        $this->filter=$filter;
    }
    
    public function __get($name) {
        return filter_input(INPUT_GET, $name, $this->filter) ?? filter_input(INPUT_POST, $name, $this->filter);
    }
    
}
