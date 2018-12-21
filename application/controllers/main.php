<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

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
	function __construct()
	{
		parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('func');
        
	}

	public function index()
	{
		$data['menu'] = "main";
		$this->db->where("key","main");
		$data['text']=$this->db->get('page');
		$data['tire']=true;
		$this->skin->main('main_page',$data);
	}
	public function concert()
	{
		$data['menu'] = "concert";
		$data['tire']=false;
		$this->db->where("key","concert");
		$data['text']=$this->db->get('page');
		$this->skin->main('blue-fon',$data);
		
	}
	public function stock()
	{
		$data['menu'] = "stock";
		$data['tire']=false;
		$this->db->where("key","stock");
		$data['text']=$this->db->get('page');
		$this->skin->main('stock',$data);
	}
/*	public function silpo()
	{
		$data['menu'] = "silpo";
		$data['tire']=false;
		$this->db->where("key","silpo");
		$data['text']=$this->db->get('page');
		$this->skin->main('stock',$data);
	}*/
	public function about()
	{
		$data['menu'] = "about";
		$this->db->where("key","about");
		$data['text']=$this->db->get('page');
		$data['tire']=true;
		$this->skin->main('main_page',$data);
	}
	public function works()
	{
		$data['menu'] = "works";
		$data['tire']=false;
		$data['works']=$this->db->get('music');
		$this->db->where("key","works");
		$data['text']=$this->db->get('page');
		$this->skin->main('works',$data);
	}
	public function radio()
	{
		$data['menu'] = "radio";
		$data['tire']=true;
		$radio=$this->uri->segment(3);
		if($radio!=null){
			$this->db->where("key",$radio);
			$data['text']=$this->db->get('page');
			$this->skin->main('radio',$data);
		}else{
			$this->load->helper('url');
			redirect("/main", 'refresh');
		}
	}
	public function contact()
	{
		$data=array();
		$data['tire']=true;
		$data['menu'] = "contact";
		$this->db->where("key","contact");
		$data['text']=$this->db->get('page');
		$this->skin->main('contact',$data);
	}
public function calculator()
	{
		//print_r($_POST);
		$data=array();
		$this->db->where("key","calculator");
		$data['text']=$this->db->get('page');
		$data['menu'] = "calc";
		$data['price']=0;
		$data['radio'] = array(0=>'84',1=>' ',2=>' ',3=>' ');
		$skidka = 1;
		$data['tire']=false;
		$cost=0;
		$data['s1']=20;
		$data['s2']=2;
		$data['s3']=5;
		$data['s4']=14;
		
		if(!empty($_POST['btn'])){
			if(!empty($_POST['radio']))
				$cost=array_sum($_POST['radio']);
			else
				$data['error'][]="Выберите на какой радиостанции вы хотите разместить рекламу";
			
			if(!empty($_POST['radio'][0])&&!empty($_POST['radio'][1])&&!empty($_POST['radio'][2])){
				$cost=210;
				$skidka = 1;
			}

			$amount = $_POST['amount'];
			$prime = $_POST['prime'];
			$notprime = $_POST['notprime'];
			$days = $_POST['days'];
			if (empty($days)) $data['error'][]="Введите количество дней";
			if (gettype($days)!='integer') $data['error'][]="Введите количество дней";
			if (empty($prime)) $data['error'][]="Введите количество выходов ролика в прайм тайм";
			if (empty($notprime)) $data['error'][]="Введите количество выходов ролика в День";
			if (empty($amount)) $data['error'][]="Введите длину ролика";
                        //echo $days;
                        //$days_prime = round($days*0.71);
                       // echo $days_prime;
			$summ=($amount/60)*$cost*($notprime*$days+$prime*$days*1.2);
			//print_r($_POST['radio']);
			$data['price']=round($summ);
			$data['radio'][0] = (isset($_POST['radio'][0]))?$_POST['radio'][0]:"";
			$data['radio'][1] = (isset($_POST['radio'][1]))?$_POST['radio'][1]:"";
			$data['radio'][2] = (isset($_POST['radio'][2]))?$_POST['radio'][2]:"";
			
			//print_r($data['radio']);
			$data['s1']=$_POST['amount'];;
			$data['s2']=$_POST['prime'];;
			$data['s3']=$_POST['notprime'];;
			$data['s4']=$_POST['days'];;
			
		}
		
		$this->skin->main('calculator',$data);
	}
	public function client()
	{
		$data['menu'] = "client";
		$this->db->where("key","client");
		$data['text']=$this->db->get('page');
		$data['tire']=true;
		$this->skin->main('client',$data);
	}

    function send_mail()
	{
		if (isset($_POST['action']) && $_POST['action'] == 'send_question')
        {
            $this->form_validation->set_rules('user_name',  'Имя',        'trim|required|xss');
            $this->form_validation->set_rules('user_phone', 'Телефон',    'trim|required|xss|');
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			$this->form_validation->set_message('name_required', 'Введите свое Имя');
			$this->form_validation->set_message('phone_required', 'Введите свой номер телефона');
            if ($this->form_validation->run())
            {
                $user_name  = $this->input->post('user_name');
                $user_phone = $this->input->post('user_phone');

                $this->load->library('email');

                $this->email->clear();
                $this->email->to('trkprostir@gmail.com');
                $this->email->from('trkprostir@gmail.com');
                $this->email->subject('Запрос на обратный звонок - ' . $user_name);
                $body = "Перезвоните пожалуйста\r\n";
				$body .= 'Имя: ' . $user_name . "\r\n";
                $body .= 'Телефон: ' . $user_phone . "\r\n\r\n";
                $this->email->message($body);

                if ($this->email->send())
    			{
    				$response[0] = '<p class="success">Спасибо за Ваш запрос. Мы свяжемся с вами в течение рабочего дня.</p>';
    			}
    			else
    			{
    				$response[0] = '<p class="fail">Произошла непредвиденная ошибка при отправке сообщения.</p>';
    			}

				echo json_encode($response);
            }
            else
    		{
				if (form_error('user_name'))  $response[1] = form_error('user_name');;
	            if (form_error('user_phone')) $response[2] = form_error('user_phone');;
                
	            echo json_encode($response);
    		}
        }
	}	
}




/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */