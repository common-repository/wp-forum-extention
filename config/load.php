<?php
   
   /**
    *  @Application WP MVC
   **/
   
   /**
    *  System load wp mvc default classes
   **/
   
   /** auto load 
       $system_load = array( 'system1', 'system2', 'system3' );
   **/

   $system_load = array( 'add', 'load', 'input', 'html', 'post', 'user' ); 
   
   /** config load 
       $config_load = array( 'config1', 'config2', 'config3' );
   **/
        
   $config_load = array( 'autoload' );
   
   /**
    *  Model load custom classes
   **/
   
   /** model load 
       $model_load = array( 'model1', 'model2', 'model3' );
   **/
    
   $model_load = array( 'db', 'users', 'comments', 'form' );
    
   /**
    *  Controller load custom classes
   **/

   /** control load 
       $control_load = array( 'control1', 'control2', 'control3' );
   **/
    
   $control_load = array( 'action' );
   
?>