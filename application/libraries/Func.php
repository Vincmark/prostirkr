<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Func {

    /**
	 * CodeIgniter global
     * 
     * @var string
	 **/
    private $CI;


    /**
	 * Конструктор
     * 
     * @return void
	 **/
	public function __construct()
	{
        $this->CI =& get_instance();

        $this->CI->load->database();
	}

    /**
     * Получить новости
     * 
     * @return array
     */
    public function get_news($limit = 2, $offset = NULL)
    {
        $this->CI->db->select('ID, post_date, post_title, post_content, post_type, post_status, guid');
        $this->CI->db->order_by('ID', 'DESC');
        $posts = $this->CI->db->get_where('wp_posts', "post_type = 'post' AND post_status = 'publish'", $limit, $offset)->result_array();

        for ($i = 0; $i < $limit; $i++)
        {
            // Находим позицию разделителя
            if ($pos = strpos($posts[$i]['post_content'], '<!--more-->'))
            {
                // Вырезаем всё до разделителя
                $time = strtotime($posts[$i]['post_date']);
                $posts[$i]['post_date'] = date("d-m-Y", $time);
                $posts[$i]['post_content'] = substr($posts[$i]['post_content'], 0, $pos);
            }
        }

        return $posts;
    }

    /**
     * Получить мета-теги страницы
     * 
     * @return array
     */
    public function get_meta_tags($page)
    {
        $this->CI->db->select('page_meta_tags.*')
                     ->join('page_meta_tags', 'page_meta_tags.page_id = pages.ID', 'left')
                     ->where(array('pages.page_key' => $page))
                     ->limit(1);

        return $this->CI->db->get('pages')->row();
    }

    /**
     * Получить постраничную навигацию
     * 
     * @param $table    string
     * @param $per_page int
     * 
     * @return string
     */
    public function get_pagination($url, $table, $per_page, $uri_segment = NULL, $where = NULL)
    {
        $this->CI->load->library('pagination');

        $config = array();
        $config['base_url']   = $url;
        $config['total_rows'] = $this->CI->db->get_where($table, $where)->num_rows();
        $config['per_page']   = $per_page;

        $config['full_tag_open'] = '<div id="pagination">';
        $config['full_tag_close'] = '</div>';

        $config['cur_tag_open'] = '<b id="cur_page">';
        $config['cur_tag_close'] = '</b>';

        $config['next_tag_open'] = '<span style="margin-left:20px;">';
        $config['next_link'] = 'Далее';
        $config['next_tag_close'] = '</span>';

        $config['prev_tag_open'] = '<span style="margin-right:20px;">';
        $config['prev_link'] = 'Назад';
        $config['prev_tag_close'] = '</span>';

        $config['first_tag_open'] = '<span style="margin-right:20px;">';
        $config['first_link'] = 'Первая';
        $config['first_tag_close'] = '</span>';

        $config['last_tag_open'] = '<span style="margin-left:20px;">';
        $config['last_link']  = 'Последняя';
        $config['last_tag_close'] = '</span>';

        if ($uri_segment != NULL)
        {
            $config['uri_segment'] = (int)$uri_segment;
        }
        
        $this->CI->pagination->initialize($config);

		return $this->CI->pagination->create_links();
    }

}