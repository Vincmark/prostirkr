<?php
	//==========================================================//
	// model for skin		version 1.1 						//
	// coded by Kuzhel D.S. aka KoRsaR, Armada Team				//
	// date 07.11.2010											//
	//==========================================================//
	// FUNCTIONS:												//
	// Skin - Конструктор										//
	// select_array - Выборка из базы, возвращает массив		//	
	// select_row - Выборка из базы, возвращает строку массива  //
	// load - Генерирует скин								    //
	//==========================================================//
class Skin extends CI_Model {
      
	 
	
      function admin($name, $data) // render skin
	 {
	
		$this->load->view('admin/menu', $data);
        $this->load->view($name, $data);
        $this->load->view('admin/footer', $data);
	 }
	 
	 function main($name, $data)
	 {         
		        
		
		$this->load->view('top', $data);     
        $this->load->view($name, $data);       
		$this->load->view('footer', $data);     	 
	 }

     
      
  

}