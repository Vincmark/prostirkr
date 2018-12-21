<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Template Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Template
 * @author		Ladygin Sergey (webcoderu@gmail.com)
 * @version     0.1
 */
class Template {

	/**
	 * Массив с переменными для передачи в шаблон
	 */
	private $data = array();

	/**
	 * Разделитель сегментов для загаловка
	 */
	private $title_separator = ' | ';

	/**
	 * Занятые имена переменных
	 */
	private $valide_string = array('head', 'block');

	/**
	 * CodeIgniter object
	 */
	private $_CI;


	//Конструктор
	function Template($params = array())
	{
		//Загружаем настройки
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
				elseif($key == 'vars_default')
				{
					$this->data = $val;
				}
				else
				{
					$this->data[$key] = $val;
				}
			}
		}

		//Инициируем суперобъект
		$this->_CI =& get_instance();

		//Загружаем необходимые хелперы
		$this->_CI->load->helper('html');

		log_message('debug', "Template Class Initialized");
	}

	/**
	 * Вывод страницы
	 * Вызывается в конце метода контроллера (когда необходимо вывести результат).
	 * 
	 * @params $main string имя шаблона для контента
	 */
	public function compile($main = 'main')
	{
		$this->data['header'] = '';

		//Обработка загаловков
		if (isset($this->data['head']['title']) && count($this->data['head']['title']) > 0)
		{
            krsort($this->data['head']['title']);
			$this->data['title'] = implode($this->title_separator, $this->data['head']['title']);
		}

		//Обработка метаинформации
		if (isset($this->data['head']['meta']) && count($this->data['head']['meta']) > 0)
		{
			$this->data['header'] .= meta($this->data['head']['meta']);
		}

        //Обработка css
		if (isset($this->data['head']['css']) && count($this->data['head']['css']) > 0)
		{
			foreach($this->data['head']['css'] as $row)
			{
				$this->data['header'] .= "\r\n" . link_tag($row);
			}
		}

		//Оюрабтка feed
		if (isset($this->data['head']['feed']) && count($this->data['head']['feed']) > 0)
		{
			foreach($this->data['head']['feed'] as $row)
			{
				$this->data['header'] .= "\r\n" . link_tag($row);
			}
		}
		
		//Обработка Js
		if (isset($this->data['head']['js']) && count($this->data['head']['js']) > 0)
		{
			foreach($this->data['head']['js'] as $row)
			{
				$this->data['header'] .= "<script type=\"text/javascript\" src=\"".$this->_CI->config->slash_item('base_url').$row['href']."\"></script>\r\n";
			}
		}

        //Добавление блоков в sidebar
        if (isset($this->data['sidebar_add']))
        {
            $sidebar_data = array_merge($this->data['sidebar_default'], $this->data['sidebar_add']);
            $this->set_block_view('sidebar_block', 'sidebar', $sidebar_data);

            unset($this->data['sidebar_default']);
            unset($this->data['sidebar_add']);
        }
        elseif (isset($this->data['sidebar_default']))
        {
            $this->set_block_view('sidebar_block', 'sidebar', $this->data['sidebar_default']);
        }

		unset($this->data['head']);
	    $this->_CI->load->vars($this->data);
	  
		$this->_CI->load->view('header');
		$this->_CI->load->view($main);
		$this->_CI->load->view('footer');
	}

	/**
	 * Создание переменной для отображения
	 * 
	 * @params string имя переменной
	 * @params mixed значение переменной
	 */
	public function set_var($name, $value = '')
	{
		if (!$this->valide_string_name($name))
		{
			return false;
		}

		$this->data[$name] = $value;

		return true;
	}

	/**
	 * Создание блока на странице, который можно в любой момент продолжить указав его имя.
	 * 
	 * @param string имя блока
	 * @param string содержимое блока
	 */
	public function set_block($name, $value = '')
	{
		if (isset($this->data['block'][$name]))
		{
			$this->data['block'][$name] .= $value;
		}
		else
		{
			$this->data['block'][$name] = $value;
		}

		return true;
	}

	/**
	 * Создание блока на странице из файла, который можно в любой момент продолжить указав его имя.
	 * 
	 * @param string имя блока
	 * @param string имя файла из папки /blocks/
	 * @param array данные для передачи в вид
	 */
	public function set_block_view($name, $file_name, $vars = array())
	{
		if (isset($this->data['block'][$name]))
		{
			$this->data['block'][$name] .= $this->view($file_name, $vars);
		}
		else
		{
			$this->data['block'][$name] = $this->view($file_name, $vars);
		}

		return true;
	}

    /**
     * Установка переменных для Sidebar
     * 
     * @param $vars array имя переменной - значение
     */
	public function set_sidebar_vars($vars = array())
	{
        $this->data['sidebar_add'] = $vars;

		return true;
	}

	/**
	 * Получение блока созданного ранее
	 * 
	 * @param $name string имя блока
	 */
	public function get_block($name)
	{
		if (isset($this->data['block'][$name]))
		{
			return $this->data['block'][$name];
		}

		return false;
	}

	/**
	 * Добавление сегмента в загаловок страницы
	 * 
	 * @param $title string содержимое сегмента
	 */
	public function set_title($title)
	{
		$this->data['head']['title'][] = $title;

		return true;
	}

	/**
	 * Установка метаданных для страницы
	 * 
	 * @param $name string имя метатэга
	 * @param $content string значение метатэга
	 */
	public function set_meta($name, $content, $type = 'name')
	{
		$this->data['head']['meta'][] = array('name' => $name, 'content' => $content, 'type' => $type);

		return true;
	}

	/**
	 * Добавление фида на страницу
	 * 
	 * @param $link string ссылка на фид
	 */
	public function set_feed($href, $title = '',  $rel='alternate', $type = 'application/rss+xml')
	{
		$this->data['head']['feed'][] = array('href'  => $href,
											  'rel'   => $rel,
											  'type'  => $type,
											  'title' => $title
		);

		return true;
	}

	/**
	 * Подключение css файла на страницу
	 * 
	 * @param $href string ссылка на css файл
	 */
	public function set_css($href, $rel = 'stylesheet', $type = 'text/css', $media = '')
	{
		$this->data['head']['css'][] = array('href'  => $href,
											 'rel' 	 => $rel,
											 'type'  => $type,
											 'media' => $media
		);

		return true;
	}

	/**
	 * Подключение js файла на страницу
	 * 
	 * @param $link string ссылка на js файл
	 */
	public function set_js($link)
	{
		$this->data['head']['js'][] = $link;

		return true;
	}

	/**
	 * Загрузка файла вида
	 * 
	 * @param $view string путь до подключаемого файла
	 * @param $vars array данные для передачи в вид
	 */
	public function view($view, $vars = array())
	{
		return $this->_CI->load->view($view, $vars, TRUE);
	}

	/**
	 * Проверяем не занята ли переменная
	 * 
	 * @param $name string имя проверяемой переменной
	 */
	private function valide_string_name($name)
	{
		return (in_array($name, $this->valide_string)) ? FALSE : TRUE;
	}

    public function in_multi_array($search, $array)
    {
        foreach ($array as $val)
        {
            if ($search == $val['id_lesson'])
            {
                return TRUE;
            }
        }

        return FALSE;
    }
 
}