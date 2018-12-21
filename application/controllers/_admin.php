<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _admin extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

public function index()	
	{
	   
		$this->load->view('admin/login');
	}
    
public function login()
    {
        
        $login=$this->input->post('login');
        $pass=$this->input->post('pass');
        
        if(($login=='')||($pass==''))
            {
                echo 'Ошибка авторизации!';
            }
        else
            {
                $this->db->where('admin',$login);
                $this->db->where('pass',md5($pass));
                $this->db->limit(1);
                $q=$this->db->get('admin');
                
                if ($q->num_rows()>0)
                {
                $row=$q->row();
                
                $data=array(   
                                'id_admin'=>$row->id_admin,
                                'admin'=>$row->admin
                        	                       
                             );
                $this->session->set_userdata($data);
              
                redirect('/_admin/main/');
            }
            
            else
            {
                echo 'Ошибка авторизации!';
                
            }
    }
    
    
  }  
  
public function main()
    {  if ($this->session->userdata('id_admin')==''){ redirect('/');}
         else  
         {
				$dt['title']='Раздел администратора';
                $this->skin->admin('admin/main_page',$dt);
		}
     }
	 

	
public function  admins()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$data['title']='Список администраторов';
			$this->skin->admin('admin/admins',$data);
        }
    }
public function do_add_admin()
    {
        $name=$_POST['name'];
        $pass=md5($_POST['pass']);
		$pass2=md5($_POST['pass2']);
		if (($name!='')&&($pass!='')&&($pass2!=''))
		{
			if ($pass==$pass2)
			{
				$this->db->where('admin',$name);
				$q=$this->db->get('admin');
				if ($q->num_rows()==0)
				{
			
				$dt=array('admin'=>$name,'pass'=>$pass);
				$this->db->insert('admin',$dt);
				
				$this->admins();  
				}
				else
				{
					$data['title']="Ошибка";
					$data['err']='<h2  class="err">Ошибка! Такой админ уже есть</h2>';
					$this->skin->admin('admin/admins',$data);
				}
			}
			else
			{
				$data['title']="Ошибка";
				$data['err']='<h2  class="err">Пароли не совпадают</h2>';
				$this->skin->admin('admin/admins',$data);
			}
		}
		else
		{
			$data['title']="Ошибка";
			$data['err']='<h2  class="err">Ошибка! Не заполнены поля</h2>';
			$this->skin->admin('admin/admins',$data);
		}
	}
	
   public function check_email($str)
    {
        
            $this->db->where('email',$str);
            $dt=$this->db->get('user');
            if ($dt->num_rows()>0)
                {
                  $this->form_validation->set_message('check_email','Email уже занят'); 
                    return FALSE;
                 }
           else
                {
                    return TRUE;
                }
    }
public function do_add_user()
    {
           
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|callback_check_email');
        $this->form_validation->set_rules('pass', 'Пароль', 'required|min_length[4]');
		$this->form_validation->set_rules('pass_conf', 'Повтор пароля', 'required|min_length[4]|matches[pass]');        
        $this->form_validation->set_rules('family', 'Фамилия', 'required');
        $this->form_validation->set_rules('name', 'Имя', 'required');
        $this->form_validation->set_rules('company', 'Компания', '');
        $this->form_validation->set_rules('phone', 'Телефон', 'required');
		
        $this->form_validation->set_message('required', 'Поле %s не заполнено');
		$this->form_validation->set_message('matches', 'Пароли не совпадают');
		$this->form_validation->set_message('valid_email', 'Неверный формат почты');
		$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
       $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        
		 if ($this->form_validation->run() == FALSE)
		{
		     
		       $data['title'] = 'Не верные данные';
			   $this->db->order_by('id_user','asc');
			   $data['user']=$this->db->get('user');
               $this->skin->admin('admin/users', $data);
			  
        }
      	else
		{
                   
            $data=array(
                            'email'=>$this->input->post('email'),
                            'pass'=>md5($this->input->post('pass')),
                            'family'=>$this->input->post('family'),
                            'name'=>$this->input->post('name'),
							'company'=>$this->input->post('company'),						
                            'phone'=>$this->input->post('phone'),
							'activ'=>1,
							'ban'=>1
                        );
            
			
                       $this->db->insert('user',$data);
						$data['err']='<h2  class="err">Пользователь добавлен</h2>';
                        $data['title'] = 'Пользователь добавлен';
						$this->db->order_by('id_user','asc');
						$data['user']=$this->db->get('user');
						$this->skin->admin('admin/users', $data);
                    
        }
    }

    	
	
 public function  do_c_p()
    {
        $pass=$_POST['pass'];
        $pass2=$_POST['pass2'];
        if (($pass!=$pass2)||($pass=='')||($pass2==''))
            {
                
					
               $data['err']='<h2  class="err">Пароли не совпадают</h2>';
			$this->skin->admin('admin/admins',$data);
            }
        else
            {
                $data=array('pass'=>md5($pass));
                $this->db->where('id_admin',$this->uri->segment(3));
                $this->db->update('admin',$data);
              $data['err']='<h2 class="err">Пароль изменен</h2>';
			$this->skin->admin('admin/admins',$data);
            }
    }	
	
	
public function edit_admin()
    {
	if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
        $id_admin=$this->db->get('admin');
        $data=array();
        $this->skin->admin('admin/edit_adm',$data);
		}
    }    
    
 public function del_admin()
    {
	if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
        $id=$this->uri->segment(3);
        $this->db->where('id_admin',$id);
        $this->db->delete('admin');
         $this->admins();  
		 }
        
    } 
	
	
	
 public function  info()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id',1);
			$q=$this->db->get('info');
			$row=$q->row();
			
			$this->db->where('id',2);
			$q=$this->db->get('info');
			$row2=$q->row();
			
			$this->db->where('id',3);
			$q=$this->db->get('info');
			$row3=$q->row();
			$data['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
			
			$data['email']=$row->text;
			$data['key']=$row2->text;
			$data['contact']=$row3->text;
			$data['title']='Информация сайте';
			
			$this->skin->admin('admin/info',$data);
        }
    }
	
	
 public function  info_upd()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				
				
			
				
				$data['title']='Информация изменена';
				$data['msg']='<h2 class="err">Информация изменена</h2>';
				$this->skin->admin('admin/message',$data);	
						
		}
	}
 public function  user_list()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->order_by('id_user','asc');
			$dt['user']=$this->db->get('user');
			$dt['title']='Список пользователей';
			$this->skin->admin('admin/users',$dt);
		}
		
	}
	
 public function  user_ban()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			if ($this->uri->segment(4)==1)
				$data=array('ban'=>1);
			else
				$data=array('ban'=>0);
			
			$this->db->where('id_user',$this->uri->segment(3));
			$this->db->update('user',$data);
			$dt['err']='Блокировка пользователя изменена';
			$this->db->order_by('id_user','asc');
			$dt['user']=$this->db->get('user');
			$dt['title']='Список пользователей';
			$this->skin->admin('admin/users',$dt);
			
		}
	}
	
public function del_user()
    {
	if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
        $id=$this->uri->segment(3);
        $this->db->where('id_user',$id);
        $this->db->delete('user');
         $this->user_list();  
		 }
        
    } 
	
 public function  add_project()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$dt=array();
			
			
			  
		$dt['title']='Добавить работу';
            $this->skin->admin('admin/add_project',$dt);
		}
		
	}
	
 public function  do_add_project()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						$dt['title'] = 'Не верные данные';
					   $this->skin->admin('admin/add_project',$dt);
				}
				else
				{
					$file1='';
					$file2='';
					
					
						$config['upload_path'] = './music/';
						$config['allowed_types'] = 'mp3';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
						if ($this->upload->do_upload('userfile3'))
						{             
							$dt1=$this->upload->data();
							$foto1=$dt1['file_name'];
							
							require_once('application/models/getid3/getid3.php');
							$getID3 = new getID3;
							$ThisFileInfo = $getID3->analyze("music/".$foto1);
							getid3_lib::CopyTagsToComments($ThisFileInfo);
							$ThisFileInfo['filename'];
							$ThisFileInfo['playtime_string'];
							$ThisFileInfo['filesize'];
							$data = array('name'=>$this->input->post("title"),
											'file_name'=>$ThisFileInfo['filename'],
											'file_size'=>$ThisFileInfo['filesize'],
											'time'=>$ThisFileInfo['playtime_string']
									);
							$this->db->insert("music",$data);
							$dt['msg']='<h2>Решение добавлено</h2>';
							$dt['title'] = 'Решение добавлено';
							$this->skin->admin('admin/message',$dt);	
		 
						}
					 
				}
		}
	}
 public function  project_list()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->order_by('id','asc');
			$dt['project']=$this->db->get('page');
			$dt['title'] = 'Спиcок cтраниц';
			$this->skin->admin('admin/page_list',$dt);	
		}
	}
	
 public function  do_del_project()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_project',$this->uri->segment(3));
			$q=$this->db->get('project');
			if ($q->num_rows()>0)
			{
			
			$row=$q->row();
			
			@unlink('./files/'.$row->broshura);
			@unlink('./files/'.$row->chertej);
			@unlink('./proj_gal/'.$row->foto1);
			@unlink('./proj_gal/'.$row->foto2);
			
			$this->db->where('id_project',$this->uri->segment(3));
			$this->db->delete('project');
			
			$dt['title'] = 'Проект удален';
			$dt['err']='<h2>Проект удален</h2>';
			$this->db->order_by('id_project','asc');
			$dt['project']=$this->db->get('project');
			$dt['title'] = 'Список проектов';
			$this->skin->admin('admin/project_list',$dt);	
		}
		else
		{
			$dt['title'] = 'Ошибка удаления';
			$dt['err']='<h2>Ошибка удаления</h2>';
			$this->db->order_by('id_project','asc');
			$dt['project']=$this->db->get('project');
			
			$this->skin->admin('admin/project_list',$dt);	
		}
		
	}
}
 public function  edit_music()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id',$this->uri->segment(3));
			$dt['proj']=$this->db->get('music');
			$dt['title']='Редактирование проекта';
			$this->skin->admin('admin/edit_music',$dt);
			
		}
	}	
	
 public function  edit_project()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id',$this->uri->segment(3));
			$dt['proj']=$this->db->get('page');
			
					
			$dt['title']='Редактирование проекта';
			
			
			 $dt['ckeditor'] = array(
						 
									//ID of the textarea that will be replaced
									'id'     =>     'content2',
									'path'    =>    'js/ckeditor',
						 
								   
						 
									//Replacing styles from the "Styles tool"
									'styles' => array(
						 
										//Creating a new style named "style 1"
										'style 1' => array (
											'name'         =>     'Blue Title',
											'element'     =>     'p',
											'styles' => array(
												'color'     =>     'White',
												'font-weight'     =>     'normal'
											)
										),
						 
										//Creating a new style named "style 2"
										'style 2' => array (
											'name'     =>     'Red Title',
											'element'     =>     'span',
											'styles' => array(
												'color'         =>     'White',
												'font-weight'         =>     'normal'
											)
										)                
									)
								);
								
								
								$dt['ckeditor2'] = array(
						 
									//ID of the textarea that will be replaced
									'id'     =>     'content3',
									'path'    =>    'js/ckeditor',
						 
								   
						 
									//Replacing styles from the "Styles tool"
									'styles' => array(
						 
										//Creating a new style named "style 1"
										'style 1' => array (
											'name'         =>     'Blue Title',
											'element'     =>     'p',
											'styles' => array(
												'color'     =>     'White',
												'font-weight'     =>     'normal'
											)
										),
						 
										//Creating a new style named "style 2"
										'style 2' => array (
											'name'     =>     'Red Title',
											'element'     =>     'span',
											'styles' => array(
												'color'         =>     'White',
												'font-weight'         =>     'normal'
											)
										)                
									)
								);
			$this->skin->admin('admin/edit_project',$dt);
			
		}
	}
	
public function  do_edit_project()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');
				$this->form_validation->set_rules('content2', 'Текст страницы', 'required');
			
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
												
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
					
					$dt['ckeditor2'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content3',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
					
						$this->db->where('id',$this->uri->segment(3));
						$dt['proj']=$this->db->get('page');
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/edit_project',$dt);
				}
				else
				{
		
					$this->db->where('id',$this->uri->segment(3));
					$q=$this->db->get('page');
					if ($q->num_rows()!=0)
					{
						
						$row=$q->row();
						
						
					
						$config1['upload_path'] = './proj_gal/';
						$config1['allowed_types'] = 'png|jpg|jpeg';
						$config1['encrypt_name'] = TRUE; 
								 
						
					//--------------------------			
								
										$data=array(
												'title'=>$this->input->post('title'),
												'text'=>$this->input->post('content2'),
												
										);
									$this->db->where('id',$this->uri->segment(3));
									 $this->db->update('page',$data);
									 $dt['msg']='<h2>Проект сохранен</h2>';
									$dt['title'] = 'Проект сохранен';
									$this->skin->admin('admin/message',$dt);					 
				}
				else
				{
				
						$dt['msg']='<h2>Ошибка</h2>';
						$dt['title'] = 'Ошибка';
						$this->skin->admin('admin/message',$dt);	
				}
				
				} 
							

	}
	
}
public function  do_edit_music()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('name', 'Название', 'required');
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$this->db->where('id',$this->uri->segment(3));
					$dt['proj']=$this->db->get('music');
					$data['title'] = 'Не верные данные';
					$this->skin->admin('admin/edit_music',$dt);
				}
				else
				{
		
					$this->db->where('id',$this->uri->segment(3));
					$q=$this->db->get('music');
					if ($q->num_rows()!=0)
					{
						
						$row=$q->row();
						$data=array(
								'name'=>$this->input->post('name'),
						);
						$this->db->where('id',$this->uri->segment(3));
						$this->db->update('music',$data);
						$dt['msg']='<h2>Проект сохранен</h2>';
						$dt['title'] = 'Проект сохранен';
						$this->skin->admin('admin/message',$dt);					 
				}
				else
				{
						$dt['msg']='<h2>Ошибка</h2>';
						$dt['title'] = 'Ошибка';
						$this->skin->admin('admin/message',$dt);	
				}
				
				} 
							

	}
	
}

public function  gallery()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{			
			$this->db->where('id_project',$this->uri->segment(3));
			$data['gal']=$this->db->get('gallery');
			$data['title'] = 'Галерея';
			$this->skin->admin('admin/gallery',$data);
		
		}		
	}
public function  do_add_gallery()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{		
				$this->form_validation->set_rules('title', 'подпись', 'required');				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
			   
			    if ($this->form_validation->run() == FALSE)
				{
					$this->db->where('id_project',$this->uri->segment(3));
					$data['gal']=$this->db->get('gallery');
					$data['title'] = 'Галерея';
					$this->skin->admin('admin/gallery',$data);
				}
				else
				{
						$config['upload_path'] = './gallery/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload())
							{             
								    $dt1=$this->upload->data();									
								    $foto1=$dt1['file_name'];
									$data=array('id_project'=>$this->uri->segment(3),'foto'=>$foto1,'title'=>$this->input->post('title'));
									
									$this->db->insert('gallery',$data);									
									$this->db->where('id_project',$this->uri->segment(3));
									
									$data['gal']=$this->db->get('gallery');
									
									$data['error']='Файл загружен';
									$this->db->where('id_project',$this->uri->segment(3));
									$data['gal']=$this->db->get('gallery');
									$data['title'] = 'Галерея';
									$this->skin->admin('admin/gallery',$data);
									
							}	
							else
							{
								$this->db->where('id_project',$this->uri->segment(3));
								$data['gal']=$this->db->get('gallery');
								$data['title'] = 'Галерея';
								$data['error']='<p class="error">'.$this->upload->display_errors().'</p>';
								$this->skin->admin('admin/gallery',$data);
							}
				}
		}
	}
	
public function  projects()
	{
		$this->db->where('menu',$this->uri->segment(3));
		$q=$this->db->get('project');
		
		foreach($q->result_array() as $key)
					{
						echo '<option value="'.$key['id_project'].'" >'.$key['title'].'</option>';
					}
	
	}
	
public function  do_del_gallery()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_gal',$this->uri->segment(3));
			$q=$this->db->get('gallery');
			if ($q->num_rows()>0)
			{
			$r=$q->row();
			
			
			@unlink('./gallery/'.$r->foto);
			
			$this->db->where('id_gal',$this->uri->segment(3));
			$this->db->delete('gallery');
			redirect('/_admin/gallery/'.$this->uri->segment(4));
		}
		else
		{
			redirect('/_admin/gallery/'.$this->uri->segment(4));
		}
		
	}
}

public function  add_reshenie()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$dt=array();
			$this->db->order_by('id_menu','asc');
			$dt['menu']=$this->db->get('menu');
			
			  $dt['ckeditor'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'content2',
            'path'    =>    'js/ckeditor',
 
           
 
            //Replacing styles from the "Styles tool"
            'styles' => array(
 
                //Creating a new style named "style 1"
                'style 1' => array (
                    'name'         =>     'Blue Title',
                    'element'     =>     'p',
                    'styles' => array(
                        'color'     =>     'White',
                        'font-weight'     =>     'normal'
                    )
                ),
 
                //Creating a new style named "style 2"
                'style 2' => array (
                    'name'     =>     'Red Title',
                    'element'     =>     'span',
                    'styles' => array(
                        'color'         =>     'White',
                        'font-weight'         =>     'normal'
                    )
                )                
            )
        );
		
		
			$dt['title']='Добавить решение';
            $this->skin->admin('admin/add_reshenie',$dt);
		}
		
	}	

public function  do_add_reshenie()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');				
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');        
				$this->form_validation->set_rules('desc', 'Описание', 'required');
				$this->form_validation->set_rules('content2', 'Конструкция', 'required');
				
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
					
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/add_reshenie',$dt);
				}
				else
				{
					
					
					
						$config['upload_path'] = './resh/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								  $dt1=$this->upload->data();
								  $foto=$dt1['file_name'];
								  
								  $config_r['image_library'] = 'gd2';
									$config_r['source_image'] = './resh/'.$foto;
									$config_r['maintain_ratio'] = FALSE;
									//echo $dt1['image_width']/$dt1['image_height'];
									 
									if ($dt1['image_width']>$dt1['image_height'])									
									{
										$s=$dt1['image_width']*(314/$dt1['image_height']);
										
										$config_r['height'] = 314;
										$config_r['width'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}											
									}
									else
									{
										$s=$dt1['image_height']*(960/$dt1['image_width']);
										
										$config_r['width'] = 960;
										$config_r['height'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}
									}
								  
								  
								  
								  
								  $this->load->helper('date');
								  
								  $data=array(
												'title'=>$this->input->post('title'),												
												'short_desc'=>$this->input->post('short_desc'),
												'desc'=>$this->input->post('desc'),
												'construct'=>$this->input->post('content2'),
												'show_gal'=>$this->input->post('show_gal'),
												'foto'=>$foto,												
												'date'=>now()
											);
								
								$this->db->insert('reshenie',$data);
								
								$dt['msg']='<h2>Решение добавлено</h2>';
								$dt['title'] = 'Решение добавлено';
								$this->skin->admin('admin/message',$dt);	
							}
							else
							{	 $dt['ckeditor'] = array(
			 
										//ID of the textarea that will be replaced
										'id'     =>     'content2',
										'path'    =>    'js/ckeditor',
							 
									   
							 
										//Replacing styles from the "Styles tool"
										'styles' => array(
							 
											//Creating a new style named "style 1"
											'style 1' => array (
												'name'         =>     'Blue Title',
												'element'     =>     'p',
												'styles' => array(
													'color'     =>     'White',
													'font-weight'     =>     'normal'
												)
											),
							 
											//Creating a new style named "style 2"
											'style 2' => array (
												'name'     =>     'Red Title',
												'element'     =>     'span',
												'styles' => array(
													'color'         =>     'White',
													'font-weight'         =>     'normal'
												)
											)                
										)
									);
							
									$dt['title'] = 'Фото не загружено';
									$dt['error']='<p class="error">'.$this->upload->display_errors().'</p>';
									$this->skin->admin('admin/add_reshenie',$dt);
							}
				}
				
		}
	}

public function  resh_list()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->order_by('id_resh','asc');
			$dt['resh']=$this->db->get('reshenie');
			$dt['title'] = 'Список решений';
			$this->skin->admin('admin/resh_list',$dt);
		
		}
	}
	
public function  edit_resh()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		$this->db->where('id_resh',$this->uri->segment(3));
			$dt['resh']=$this->db->get('reshenie');
			
			$dt['title']='Редактирование решения';
			
			
			 $dt['ckeditor'] = array(
						 
									//ID of the textarea that will be replaced
									'id'     =>     'content2',
									'path'    =>    'js/ckeditor',
						 
								   
						 
									//Replacing styles from the "Styles tool"
									'styles' => array(
						 
										//Creating a new style named "style 1"
										'style 1' => array (
											'name'         =>     'Blue Title',
											'element'     =>     'p',
											'styles' => array(
												'color'     =>     'White',
												'font-weight'     =>     'normal'
											)
										),
						 
										//Creating a new style named "style 2"
										'style 2' => array (
											'name'     =>     'Red Title',
											'element'     =>     'span',
											'styles' => array(
												'color'         =>     'White',
												'font-weight'         =>     'normal'
											)
										)                
									)
								);
								
								
							
			$this->skin->admin('admin/edit_reshenie',$dt);
		}
		
	}
	
public function  do_edit_resh()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');        
				$this->form_validation->set_rules('desc', 'Описание', 'required');
				$this->form_validation->set_rules('content2', 'Конструкция', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						
						$this->db->where('id_resh',$this->uri->segment(3));
						$dt['resh']=$this->db->get('reshenie');
						
						$dt['title']='Редактирование решения';
			
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
				
					
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/edit_reshenie',$dt);
				}
				else
				{
					$this->db->where('id_resh',$this->uri->segment(3));
					$q=$this->db->get('reshenie');
					
					$row=$q->row();					
					
					$foto=$row->foto;
					
						$config['upload_path'] = './resh/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								    $dt1=$this->upload->data();
									@unlink('./resh/'.$foto);
								    $foto=$dt1['file_name'];
									
									
									$config_r['image_library'] = 'gd2';
									$config_r['source_image'] = './resh/'.$foto;
									$config_r['maintain_ratio'] = FALSE;
									//echo $dt1['image_width']/$dt1['image_height'];
									 
									if ($dt1['image_width']>$dt1['image_height'])									
									{
										$s=$dt1['image_width']*(314/$dt1['image_height']);
										
										$config_r['height'] = 314;
										$config_r['width'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}											
									}
									else
									{
										$s=$dt1['image_height']*(960/$dt1['image_width']);
										
										$config_r['width'] = 960;
										$config_r['height'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}
									}
							}	
					//--------------------------		
							
							
										$data=array(
												'title'=>$this->input->post('title'),											
												'short_desc'=>$this->input->post('short_desc'),
												'desc'=>$this->input->post('desc'),
												'construct'=>$this->input->post('content2'),
												'show_gal'=>$this->input->post('show_gal'),												
												'foto'=>$foto
										);
									$this->db->where('id_resh',$this->uri->segment(3));
									 $this->db->update('reshenie',$data);
									 $dt['msg']='<h2>Решение сохранено</h2>';
									$dt['title'] = 'Решение сохранено';
									$this->skin->admin('admin/message',$dt);					 
				}
							
	}
	
}		

public function  resh_gal()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_resh',$this->uri->segment(3));
			$dt['resh_gal']=$this->db->get('resh_gal');
			$dt['title'] = 'Редактирование слайдов решения';
			$this->skin->admin('admin/resh_gallery',$dt);	

		}
	}
	

public function  do_add_r_g()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Описание', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$this->db->where('id_resh',$this->uri->segment(3));
					$dt['resh_gal']=$this->db->get('resh_gal');
					$dt['title'] = 'Редактирование слайдов решения';
					$this->skin->admin('admin/resh_gallery',$dt);	
				}
				else
				{
					$config['upload_path'] = './resh/';
					$config['allowed_types'] = 'png|jpg|jpeg';
					$config['encrypt_name'] = TRUE; 
										 
					$this->load->library('upload', $config);
									
						if ($this->upload->do_upload('userfile'))
						{             
							 $dt1=$this->upload->data();									
							 $foto=$dt1['file_name'];
							 
							 $data=array(
												'text'=>$this->input->post('title'),											
												'id_resh'=>$this->uri->segment(3),												
												'foto'=>$foto
										);
							$this->db->insert('resh_gal',$data);
							
							$this->db->where('id_resh',$this->uri->segment(3));
							$dt['resh_gal']=$this->db->get('resh_gal');
							$dt['title'] = 'Редактирование слайдов решения';
							$dt['err']='Слайд добавлен';
							$this->skin->admin('admin/resh_gallery',$dt);
							
						}
						else
						{
							$dt['title'] = 'Фото не загружено';
							$dt['error']='<p class="error">'.$this->upload->display_errors().'</p>';
							$this->skin->admin('admin/add_reshenie',$dt);
						}
				}
		}
	}
public function  do_del_r_g()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_r_g',$this->uri->segment(3));
			$q=$this->db->get('resh_gal');
			if ($q->num_rows()>0)
			{
			$r=$q->row();
			
			@unlink('./resh/'.$r->foto);		
		
			$this->db->where('id_r_g',$this->uri->segment(3));
			$this->db->delete('resh_gal');
			
			$this->db->where('id_resh',$this->uri->segment(4));
			$dt['resh_gal']=$this->db->get('resh_gal');
			$dt['title'] = 'Редактирование слайдов решения';
			$this->skin->admin('admin/resh_gallery',$dt);
		}
		else
		{
			$this->db->where('id_resh',$this->uri->segment(4));
			$dt['resh_gal']=$this->db->get('resh_gal');
			$dt['title'] = 'Редактирование слайдов решения';
			$this->skin->admin('admin/resh_gallery',$dt);
		}
		
	}
}
public function  edit_r_g()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
			$this->db->where('id_r_g',$this->uri->segment(3));
			$dt['resh_gal']=$this->db->get('resh_gal');
			$dt['title'] = 'Редактирование слайдов решения';
			$this->skin->admin('admin/edit_resh_gallery',$dt);	
		}
		
	}
public function  do_edit_r_g()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Описание', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$this->db->where('id_r_g',$this->uri->segment(3));
					$dt['resh_gal']=$this->db->get('resh_gal');
					$dt['title'] = 'Редактирование слайдов решения';
					$this->skin->admin('admin/edit_resh_gallery',$dt);	
				}
				else
				{
					$config['upload_path'] = './resh/';
					$config['allowed_types'] = 'png|jpg|jpeg';
					$config['encrypt_name'] = TRUE; 
										 
					$this->load->library('upload', $config);
									
					$this->db->where('id_r_g',$this->uri->segment(3));
					$dt['resh_gal']=$this->db->get('resh_gal');
						
					$row=$dt['resh_gal']->row();
					$foto=$row->foto;				
									
						if ($this->upload->do_upload('userfile'))
						{             
							 $dt1=$this->upload->data();	
							 @unlink('./resh/'.$foto);
							 $foto=$dt1['file_name'];
						}
					 $data=array(
									'text'=>$this->input->post('title'),												
									'foto'=>$foto
								);
								
					$this->db->where('id_r_g',$this->uri->segment(3));
					$this->db->update('resh_gal',$data);
					
					
					$this->db->where('id_resh',$row->id_resh);
					$dt['resh_gal']=$this->db->get('resh_gal');
					$dt['error']='<h2>Сохранено</h2>';
					$dt['title'] = 'Редактирование слайдов решения';
					$this->skin->admin('admin/resh_gallery',$dt);
				}
		}
		
	}
public function  add_exp()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
					
			 
							//Creating a new style named "style 1"
							'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
			$dt['title'] = 'Добавить интервью';
			$this->skin->admin('admin/add_expert',$dt);			
		}
	}
	
public function  do_add_exp()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Заголовок', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');
				$this->form_validation->set_rules('content2', 'Текст интервью', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
					$dt['title'] = 'Добавить интервью';
					$this->skin->admin('admin/add_expert',$dt);
				}
				else
				{
					$config['upload_path'] = './inter/';
					$config['allowed_types'] = 'png|jpg|jpeg';
					$config['encrypt_name'] = TRUE; 										 
					$this->load->library('upload', $config);
					$foto='';
						if ($this->upload->do_upload('userfile'))
						{             
							 $dt1=$this->upload->data();							 
							 $foto=$dt1['file_name'];
						}
						
					$video=$this->input->post('video');
					
					if (($video=='')&&($foto==''))
						{
							$dt['ckeditor'] = array(
			 
								//ID of the textarea that will be replaced
								'id'     =>     'content2',
								'path'    =>    'js/ckeditor',
					 
							   
					 'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
							);
							$dt['title'] = 'Добавить интервью';
							$dt['error']='<p class="error">Необходимо выбрать файл или видео!</p>';
							$this->skin->admin('admin/add_expert',$dt);
						}
						else
						{
							$this->load->helper('date');
							$data=array(
											'title'=>$this->input->post('title'),
											'short'=>$this->input->post('short_desc'),
											'foto'=>$foto,
											'video'=>$video,
											'text'=>$this->input->post('content2'),
											'date'=>now()							
										);
							$this->db->insert('expert',$data);
							$dt['msg']='<h2>Интервью добавлено</h2>';
							$dt['title'] = 'Интервью добавлено';
							$this->skin->admin('admin/message',$dt);
						}
				}
		}
	}
	
public function  exp_list()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
			$dt['exp']=$this->db->get('expert');
			$dt['title'] = 'Список интервью';
			$this->skin->admin('admin/exp_list',$dt);
		}
	}
public function  do_del_exp()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		
			$this->db->where('id',$this->uri->segment(3));
			$q=$this->db->get('music');
			if ($q->num_rows()>0)
			{
			
			$row=$q->row();
			$this->db->where('id',$this->uri->segment(3));
			$this->db->delete('music');
			
			$dt['title'] = 'Работа удалена';
			$dt['err']='<h2>Работа удалена</h2>';
			
			$dt['project']=$this->db->get('music');
			$dt['title'] = 'Наши работы';
			$this->skin->admin('admin/music_list',$dt);
		}
		
	}
}

public function  edit_exp()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		$dt['ckeditor'] = array(
			 
								//ID of the textarea that will be replaced
								'id'     =>     'content2',
								'path'    =>    'js/ckeditor',
					 
							   
					 
								//Replacing styles from the "Styles tool"
								'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
							);
			$this->db->where('id_exp',$this->uri->segment(3));
			$dt['exp']=$this->db->get('expert');
			$dt['title'] = 'Редактировать интервью';
			$this->skin->admin('admin/edit_expert',$dt);
		}
	}
	

public function  do_edit_exp()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
					$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Заголовок', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');
				$this->form_validation->set_rules('content2', 'Текст интервью', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
					$this->db->where('id_exp',$this->uri->segment(3));
					$dt['exp']=$this->db->get('expert');
					$dt['title'] = 'Редактировать интервью';
					$this->skin->admin('admin/edit_expert',$dt);
				}
				else
				{
						$this->db->where('id_exp',$this->uri->segment(3));
						$dt['exp']=$this->db->get('expert');
						
						$row=$dt['exp']->row();
						$foto=$row->foto;
					
						$config['upload_path'] = './inter/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 										 
						$this->load->library('upload', $config);
					
							if ($this->upload->do_upload('userfile'))
							{             
								 $dt1=$this->upload->data();
								 @unlink('./inter/'.$foto);									
								 $foto=$dt1['file_name'];
							}
							
					$video=$this->input->post('video');
					
					if (($video=='')&&($foto==''))
						{
							$dt['ckeditor'] = array(
			 
								//ID of the textarea that will be replaced
								'id'     =>     'content2',
								'path'    =>    'js/ckeditor',
					 
							   
								'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
							);
							
							$dt['error']='<p class="error">Необходимо выбрать файл или видео!</p>';
							$this->db->where('id_exp',$this->uri->segment(3));
							$dt['exp']=$this->db->get('expert');
							$dt['title'] = 'Редактировать интервью';
							$this->skin->admin('admin/edit_expert',$dt);
						}
						else
						{
							$this->load->helper('date');
							$data=array(
											'title'=>$this->input->post('title'),
											'short'=>$this->input->post('short_desc'),
											'foto'=>$foto,
											'video'=>$video,
											'text'=>$this->input->post('content2')						
										);
							$this->db->where('id_exp',$this->uri->segment(3));
							$this->db->update('expert',$data);
							$dt['msg']='<h2>Интервью сохранено</h2>';
							$dt['title'] = 'Интервью сохранено';
							$this->skin->admin('admin/message',$dt);
						}		
							
				}
		}
		
	}


/*-=---------------------------*/
	
	
public function  add_spec()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
					
			 
							//Creating a new style named "style 1"
							'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
			$dt['title'] = 'Добавить спецпредложение';
			$this->skin->admin('admin/add_spec',$dt);			
		}
	}
	
public function  do_add_spec()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Заголовок', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');
				$this->form_validation->set_rules('content2', 'Текст интервью', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
					$dt['title'] = 'Добавить спец. предложение';
					$this->skin->admin('admin/add_spec',$dt);
				}
				else
				{
					$config['upload_path'] = './spec/';
					$config['allowed_types'] = 'png|jpg|jpeg';
					$config['encrypt_name'] = TRUE; 										 
					$this->load->library('upload', $config);
					$foto='';
						if ($this->upload->do_upload('userfile'))
						{             
							 $dt1=$this->upload->data();							 
							 $foto=$dt1['file_name'];
						}
						
					$video=$this->input->post('video');
					
							$this->load->helper('date');
							$data=array(
											'title'=>$this->input->post('title'),
											'short'=>$this->input->post('short_desc'),
											'foto'=>$foto,
											'text'=>$this->input->post('content2'),
											'date'=>now()							
										);
							$this->db->insert('spec',$data);
							$dt['msg']='<h2>Спец.предложение добавлено</h2>';
							$dt['title'] = 'Спец.предложение добавлено';
							$this->skin->admin('admin/message',$dt);
					
				}
		}
	}
	
public function  spec_list()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
			$this->db->order_by('id_spec','asc');
			$dt['spec']=$this->db->get('spec');
			$dt['title'] = 'Список интервью';
			$this->skin->admin('admin/spec_list',$dt);
		}
	}
public function  do_del_spec()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
			$this->db->where('id_spec',$this->uri->segment(3));
			$q=$this->db->get('spec');
			if ($q->num_rows()>0)
			{
			$r=$q->row();
			@unlink('./spec/'.$r->foto);
		
			$this->db->where('id_spec',$this->uri->segment(3));
			$this->db->delete('spec');
			
			$dt['title'] = 'Спец предложение  удалено';
			$dt['err']='<h2>Спец предложение  удалено</h2>';
			$this->db->order_by('id_spec','desc');
			$dt['spec']=$this->db->get('spec');
			$dt['title'] = 'Список спец предложений';
			$this->skin->admin('admin/spec_list',$dt);
			}
			else
			{
				$this->db->order_by('id_spec','desc');
				$dt['spec']=$this->db->get('spec');
				$dt['err']='<h2>Спец предложение не найдено</h2>';
				$dt['title'] = 'Список спец предложений';
				$this->skin->admin('admin/spec_list',$dt);
			}
		}
	}

public function  edit_spec()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		$dt['ckeditor'] = array(
			 
								//ID of the textarea that will be replaced
								'id'     =>     'content2',
								'path'    =>    'js/ckeditor',
					 
							   
					 
								//Replacing styles from the "Styles tool"
								'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
							);
			$this->db->where('id_spec',$this->uri->segment(3));
			$dt['spec']=$this->db->get('spec');
			$dt['title'] = 'Редактировать спец.предложение';
			$this->skin->admin('admin/edit_spec',$dt);
		}
	}
	

public function  do_edit_spec()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
					$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Заголовок', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');
				$this->form_validation->set_rules('content2', 'Текст интервью', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
					$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
					$this->db->where('id_exp',$this->uri->segment(3));
					$dt['exp']=$this->db->get('expert');
					$dt['title'] = 'Редактировать спец.предложение';
					$this->skin->admin('admin/edit_expert',$dt);
				}
				else
				{
						$this->db->where('id_spec',$this->uri->segment(3));
						$dt['spec']=$this->db->get('spec');
						
						$row=$dt['spec']->row();
						
						$foto=$row->foto;
					
						$config['upload_path'] = './spec/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 										 
						$this->load->library('upload', $config);
					
							if ($this->upload->do_upload('userfile'))
							{             
								 $dt1=$this->upload->data();
								 @unlink('./spec/'.$foto);									
								 $foto=$dt1['file_name'];
							}
							
					
							$data=array(
											'title'=>$this->input->post('title'),
											'short'=>$this->input->post('short_desc'),
											'foto'=>$foto,
											'text'=>$this->input->post('content2')						
										);
							$this->db->where('id_spec',$this->uri->segment(3));
							$this->db->update('spec',$data);
							$dt['msg']='<h2>Cпец.предложение сохранено</h2>';
							$dt['title'] = 'Cпец.предложение сохранено';
							$this->skin->admin('admin/message',$dt);
								
							
				}
		}
		
	}


/*---------------------------------------*/

public function add_state()
    {  if ($this->session->userdata('id_admin')==''){ redirect('/');}
         else  
         {	 
			$dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						'styles' => array(
					 
									//Creating a new style named "style 1"
									'Вопрос' => array (
										'name'         =>     'Blue Title',
										'element'     =>     'p',
										'styles' => array(
											'color'     =>     'Black',
											'font-weight'     =>     'normal',
											'background' => '#ececec'
										)
									)              
								)
					);
			$dt['title'] = 'Добавить статью';
			$this->skin->admin('admin/add_state',$dt);
		 
		 }
	}

public function state_list()
    {  if ($this->session->userdata('id_admin')==''){ redirect('/');}
         else  
         {	 
				$dt['states']=$this->db->get('state');
				$dt['title']='Статьи';
                $this->skin->admin('admin/state_list',$dt);
		}
	}
	
	
public function  do_add_state()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');				
			
				$this->form_validation->set_rules('content2', 'Конструкция', 'required');
				
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
					
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/add_state',$dt);
				}
				else
				{
					
					
					
						$config['upload_path'] = './state/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								  $dt1=$this->upload->data();
								  $foto=$dt1['file_name'];
								  $this->load->helper('date');
								  
								  $data=array(
												'title'=>$this->input->post('title'),							
												'text'=>$this->input->post('content2'),
												'foto'=>$foto,												
												'date'=>now()
											);
								
								$this->db->insert('state',$data);
								
								$dt['msg']='<h2>Статья добавлена</h2>';
								$dt['title'] = 'Статья добавлена';
								$this->skin->admin('admin/message',$dt);	
							}
							else
							{	 $dt['ckeditor'] = array(
			 
										//ID of the textarea that will be replaced
										'id'     =>     'content2',
										'path'    =>    'js/ckeditor',
							 
									   
							 
										//Replacing styles from the "Styles tool"
										'styles' => array(
							 
											//Creating a new style named "style 1"
											'style 1' => array (
												'name'         =>     'Blue Title',
												'element'     =>     'p',
												'styles' => array(
													'color'     =>     'White',
													'font-weight'     =>     'normal'
												)
											),
							 
											//Creating a new style named "style 2"
											'style 2' => array (
												'name'     =>     'Red Title',
												'element'     =>     'span',
												'styles' => array(
													'color'         =>     'White',
													'font-weight'         =>     'normal'
												)
											)                
										)
									);
							
									$dt['title'] = 'Фото не загружено';
									$dt['error']='<div class="error">'.$this->upload->display_errors().'</div>';
									$this->skin->admin('admin/add_state',$dt);
							}
				}
				
		}
	}
	
	
	
  public function edit_state()         
    {
		$this->load->library('form_validation');
		$this->load->helper('ckeditor_helper');
        $this->db->where('id_state',$this->uri->segment(3));
        $data['state']=$this->db->get('state');
      
         $data['ckeditor'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'content2',
            'path'    =>    'js/ckeditor',
 
           
 
            //Replacing styles from the "Styles tool"
            'styles' => array(
 
                //Creating a new style named "style 1"
                'style 1' => array (
                    'name'         =>     'Blue Title',
                    'element'     =>     'p',
                    'styles' => array(
                        'color'     =>     'White',
                        'font-weight'     =>     'normal'
                    )
                ),
 
                //Creating a new style named "style 2"
                'style 2' => array (
                    'name'     =>     'Red Title',
                    'element'     =>     'span',
                    'styles' => array(
                        'color'         =>     'White',
                        'font-weight'         =>     'normal'
                    )
                )                
            )
        );
        
	$data['title']='Редактирование статьи';
     $this->skin->admin('admin/edit_state',$data);
    
     }  
     
     
public function do_edit_state()
    {    
          $this->load->library('form_validation');
		  $this->form_validation->set_rules('title', 'Название', 'required|min_length[3]|xss_clean');
          $this->form_validation->set_rules('content2', 'Text', 'required');
		
            $this->form_validation->set_message('required', 'Поле %s не заполнено');
			 $this->form_validation->set_message('min_length', 'Минимальная длина поля %s 3 символа');
         $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
	   
         if ($this->form_validation->run() == FALSE)
        {
		
			
			$this->db->where('id_state',$this->uri->segment(3));
			$data['dt']=$this->db->get('state');
			$this->load->helper('ckeditor_helper');
            $data['ckeditor'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'content2',
            'path'    =>    'js/ckeditor',
 
           
 
            //Replacing styles from the "Styles tool"
            'styles' => array(
 
                //Creating a new style named "style 1"
                'style 1' => array (
                    'name'         =>     'Blue Title',
                    'element'     =>     'p',
                    'styles' => array(
                        'color'     =>     'White',
                        'font-weight'     =>     'normal'
                    )
                ),
 
                //Creating a new style named "style 2"
                'style 2' => array (
                    'name'     =>     'Red Title',
                    'element'     =>     'span',
                    'styles' => array(
                        'color'         =>     'White',
                        'font-weight'         =>     'normal'
                    )
                )                
            )
        );
		  $data['title']='Ошибка';
          $this->skin->admin('admin/edit_state',$data);
          
          }
          
          
        else
           
			{
				$this->db->where('id_state',$this->uri->segment(3));
				$q=$this->db->get('state');
				$row=$q->row();
				$foto=$row->foto;
						$config['upload_path'] = './state/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								  $dt1=$this->upload->data();
								  @unlink('./state/'.$foto);
								  $foto=$dt1['file_name'];
							}
			
			
			$data=array(
							'title'=>$this->input->post('title'),							
							'text'=>$this->input->post('content2'),
							'foto'=>$foto												
						);
								$this->db->where('id_state',$this->uri->segment(3));
								$this->db->update('state',$data);
								
								$dt['msg']='<h2>Статья сохранена</h2>';
								$dt['title'] = 'Статья сохранена';
								$this->skin->admin('admin/message',$dt);	
           
			
			}
    }	

public function  do_del_state()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		
			$this->db->where('id_state',$this->uri->segment(3));
			$q=$this->db->get('state');
			if ($q->num_rows()>0)
			{
			
			$row=$q->row();
			
			@unlink('./state/'.$row->foto);
		
			$this->db->where('id_state',$this->uri->segment(3));
			$this->db->delete('state');
			
			$dt['title'] = 'Статья удалена';
			$dt['err']='<h2>Статья удалена</h2>';
			
			$dt['states']=$this->db->get('state');
			$dt['title']='Статьи';
            $this->skin->admin('admin/state_list',$dt);
		}
		else
		{
			$dt['exp']=$this->db->get('state');
			$dt['title'] = 'Список интервью';
			$this->skin->admin('admin/state_list',$dt);
		}
	}
}	
	
/*=--0--------------------------------------------*/
public function  add_reshenie2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$dt=array();
			$this->db->order_by('id_menu','asc');
			$dt['menu']=$this->db->get('menu');
			
			  $dt['ckeditor'] = array(
 
            //ID of the textarea that will be replaced
            'id'     =>     'content2',
            'path'    =>    'js/ckeditor',
 
           
 
            //Replacing styles from the "Styles tool"
            'styles' => array(
 
                //Creating a new style named "style 1"
                'style 1' => array (
                    'name'         =>     'Blue Title',
                    'element'     =>     'p',
                    'styles' => array(
                        'color'     =>     'White',
                        'font-weight'     =>     'normal'
                    )
                ),
 
                //Creating a new style named "style 2"
                'style 2' => array (
                    'name'     =>     'Red Title',
                    'element'     =>     'span',
                    'styles' => array(
                        'color'         =>     'White',
                        'font-weight'         =>     'normal'
                    )
                )                
            )
        );
		
		
			$dt['title']='Добавить проект';
            $this->skin->admin('admin/add_reshenie2',$dt);
		}
		
	}	

public function  do_add_reshenie2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');				
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');        
				$this->form_validation->set_rules('desc', 'Описание', 'required');
				$this->form_validation->set_rules('content2', 'Конструкция', 'required');
				
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			    $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
					
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/add_reshenie2',$dt);
				}
				else
				{
					
					
					
						$config['upload_path'] = './resh2/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								  $dt1=$this->upload->data();
								  $foto=$dt1['file_name'];
								  
								  $config_r['image_library'] = 'gd2';
									$config_r['source_image'] = './resh2/'.$foto;
									$config_r['maintain_ratio'] = FALSE;
									//echo $dt1['image_width']/$dt1['image_height'];
									 
									if ($dt1['image_width']>$dt1['image_height'])									
									{
										$s=$dt1['image_width']*(314/$dt1['image_height']);
										
										$config_r['height'] = 314;
										$config_r['width'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}											
									}
									else
									{
										$s=$dt1['image_height']*(960/$dt1['image_width']);
										
										$config_r['width'] = 960;
										$config_r['height'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}
									}
								  
								  
								  
								  
								  $this->load->helper('date');
								  
								  $data=array(
												'title'=>$this->input->post('title'),												
												'short_desc'=>$this->input->post('short_desc'),
												'desc'=>$this->input->post('desc'),
												'construct'=>$this->input->post('content2'),
												'show_gal'=>$this->input->post('show_gal'),
												'foto'=>$foto,												
												'date'=>now()
											);
								
								$this->db->insert('reshenie_2',$data);
								
								$dt['msg']='<h2>Проект добавлен</h2>';
								$dt['title'] = 'Проект добавлен';
								$this->skin->admin('admin/message',$dt);	
							}
							else
							{	 $dt['ckeditor'] = array(
			 
										//ID of the textarea that will be replaced
										'id'     =>     'content2',
										'path'    =>    'js/ckeditor',
							 
									   
							 
										//Replacing styles from the "Styles tool"
										'styles' => array(
							 
											//Creating a new style named "style 1"
											'style 1' => array (
												'name'         =>     'Blue Title',
												'element'     =>     'p',
												'styles' => array(
													'color'     =>     'White',
													'font-weight'     =>     'normal'
												)
											),
							 
											//Creating a new style named "style 2"
											'style 2' => array (
												'name'     =>     'Red Title',
												'element'     =>     'span',
												'styles' => array(
													'color'         =>     'White',
													'font-weight'         =>     'normal'
												)
											)                
										)
									);
							
									$dt['title'] = 'Фото не загружено';
									$dt['error']='<p class="error">'.$this->upload->display_errors().'</p>';
									$this->skin->admin('admin/add_reshenie2',$dt);
							}
				}
				
		}
	}

public function  resh_list2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->order_by('id_resh','asc');
			$dt['resh']=$this->db->get('reshenie_2');
			$dt['title'] = 'Список решений';
			$this->skin->admin('admin/resh_list2',$dt);
		
		}
	}
	
public function  edit_resh2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{	
		$this->db->where('id_resh',$this->uri->segment(3));
			$dt['resh']=$this->db->get('reshenie_2');
			
			$dt['title']='Редактирование решения';
			
			
			 $dt['ckeditor'] = array(
						 
									//ID of the textarea that will be replaced
									'id'     =>     'content2',
									'path'    =>    'js/ckeditor',
						 
								   
						 
									//Replacing styles from the "Styles tool"
									'styles' => array(
						 
										//Creating a new style named "style 1"
										'style 1' => array (
											'name'         =>     'Blue Title',
											'element'     =>     'p',
											'styles' => array(
												'color'     =>     'White',
												'font-weight'     =>     'normal'
											)
										),
						 
										//Creating a new style named "style 2"
										'style 2' => array (
											'name'     =>     'Red Title',
											'element'     =>     'span',
											'styles' => array(
												'color'         =>     'White',
												'font-weight'         =>     'normal'
											)
										)                
									)
								);
								
								
							
			$this->skin->admin('admin/edit_reshenie2',$dt);
		}
		
	}
	
public function  do_edit_resh2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Название', 'required');
				$this->form_validation->set_rules('short_desc', 'Короткое описание', 'required');        
				$this->form_validation->set_rules('desc', 'Описание', 'required');
				$this->form_validation->set_rules('content2', 'Конструкция', 'required');
				
				$this->form_validation->set_message('required', 'Поле %s не заполнено');
				$this->form_validation->set_message('matches', 'Пароли не совпадают');
				$this->form_validation->set_message('valid_email', 'Неверный формат почты');
				$this->form_validation->set_message('min_length', 'Длина поля %s должна быть не меньше 4 символов');
			   $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

			   
			    if ($this->form_validation->run() == FALSE)
				{
						
						$this->db->where('id_resh',$this->uri->segment(3));
						$dt['resh']=$this->db->get('reshenie_2');
						
						$dt['title']='Редактирование решения';
			
						  $dt['ckeditor'] = array(
			 
						//ID of the textarea that will be replaced
						'id'     =>     'content2',
						'path'    =>    'js/ckeditor',
			 
					   
			 
						//Replacing styles from the "Styles tool"
						'styles' => array(
			 
							//Creating a new style named "style 1"
							'style 1' => array (
								'name'         =>     'Blue Title',
								'element'     =>     'p',
								'styles' => array(
									'color'     =>     'White',
									'font-weight'     =>     'normal'
								)
							),
			 
							//Creating a new style named "style 2"
							'style 2' => array (
								'name'     =>     'Red Title',
								'element'     =>     'span',
								'styles' => array(
									'color'         =>     'White',
									'font-weight'         =>     'normal'
								)
							)                
						)
					);
					
				
					
					   $data['title'] = 'Не верные данные';
					   $this->skin->admin('admin/edit_reshenie2',$dt);
				}
				else
				{
					$this->db->where('id_resh',$this->uri->segment(3));
					$q=$this->db->get('reshenie_2');
					
					$row=$q->row();					
					
					$foto=$row->foto;
					
						$config['upload_path'] = './resh2/';
						$config['allowed_types'] = 'png|jpg|jpeg';
						$config['encrypt_name'] = TRUE; 
								 
						$this->load->library('upload', $config);
							
							if ($this->upload->do_upload('userfile'))
							{             
								    $dt1=$this->upload->data();
									@unlink('./resh2/'.$foto);
								    $foto=$dt1['file_name'];
									
									
									$config_r['image_library'] = 'gd2';
									$config_r['source_image'] = './resh2/'.$foto;
									$config_r['maintain_ratio'] = FALSE;
									//echo $dt1['image_width']/$dt1['image_height'];
									 
									if ($dt1['image_width']>$dt1['image_height'])									
									{
										$s=$dt1['image_width']*(314/$dt1['image_height']);
										
										$config_r['height'] = 314;
										$config_r['width'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}											
									}
									else
									{
										$s=$dt1['image_height']*(960/$dt1['image_width']);
										
										$config_r['width'] = 960;
										$config_r['height'] =  $s;
										
										$this->load->library('image_lib',$config_r);
										
										if ( ! $this->image_lib->resize())
												{
													echo $this->image_lib->display_errors();
													
												}
									}
							}	
					//--------------------------		
							
							
										$data=array(
												'title'=>$this->input->post('title'),											
												'short_desc'=>$this->input->post('short_desc'),
												'desc'=>$this->input->post('desc'),
												'construct'=>$this->input->post('content2'),
												'show_gal'=>$this->input->post('show_gal'),												
												'foto'=>$foto
										);
									$this->db->where('id_resh',$this->uri->segment(3));
									 $this->db->update('reshenie_2',$data);
									 $dt['msg']='<h2>Проект сохранен</h2>';
									$dt['title'] = 'Проект сохранен';
									$this->skin->admin('admin/message',$dt);					 
				}
							
	}
	
}


	
 public function  do_del_resh()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_resh',$this->uri->segment(3));
			$q=$this->db->get('reshenie');
			if ($q->num_rows()>0)
			{
			
			$row=$q->row();
			
			@unlink('./resh/'.$row->foto);
			
			$this->db->where('id_resh',$this->uri->segment(3));
			$this->db->delete('reshenie');
			
			$dt['title'] = 'Решение удалено';
			$dt['err']='<h2>Решение удалено</h2>';
			$this->db->order_by('id_resh','asc');
			$dt['resh']=$this->db->get('reshenie');
			$dt['title'] = 'Список проектов';
			$this->skin->admin('admin/resh_list',$dt);	
		}
		else
		{
			$dt['title'] = 'Ошибка удаления';
			$dt['err']='<h2>Ошибка удаления</h2>';
			$dt['resh']=$this->db->get('reshenie');
			$dt['title'] = 'Список проектов';
			$this->skin->admin('admin/resh_list',$dt);		
		}
		
	}
}		

 public function  do_del_resh2()
    {
		if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->where('id_resh',$this->uri->segment(3));
			$q=$this->db->get('reshenie2');
			if ($q->num_rows()>0)
			{
			
			$row=$q->row();
			
			@unlink('./resh2/'.$row->foto);
			
			$this->db->where('id_resh',$this->uri->segment(3));
			$this->db->delete('reshenie2');
			
			$dt['title'] = 'Решение удалено';
			$dt['err']='<h2>Решение удалено</h2>';
			$this->db->order_by('id_resh','asc');
			$dt['resh']=$this->db->get('reshenie2');
			$dt['title'] = 'Список проектов';
			$this->skin->admin('admin/resh_list2',$dt);	
		}
		else
		{
			$dt['title'] = 'Ошибка удаления';
			$dt['err']='<h2>Ошибка удаления</h2>';
			$dt['resh']=$this->db->get('reshenie2');
			$dt['title'] = 'Список проектов';
			$this->skin->admin('admin/resh_list2',$dt);		
		}
		
	}
}
public function music_list(){
	if ($this->session->userdata('id_admin')=='')
		{
			redirect('/');
		}
		else
		{
			$this->db->order_by('id','asc');
			$dt['project']=$this->db->get('music');
			$dt['title'] = 'Наши работы';
			$this->skin->admin('admin/music_list',$dt);	
			
		}

}
public function insert_music(){
		
		$this->skin->admin('admin/add_project',"");	
		}
		
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */