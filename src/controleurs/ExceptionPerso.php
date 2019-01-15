<?php

namespace wishlist\controleurs;

class ExceptionPerso extends \Exception {
    
    private $type;
    
    public function __construct($message, $type) {
        $this->type = $type;
        parent::__construct($message);
    }
    
    public function getType(){
        return $this->type;
    }
  
    public function __toString() {
        return $this->message;
    }
}