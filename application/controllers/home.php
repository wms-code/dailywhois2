<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {


	public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
    }
    
	 public function index() 
	{
		if($this->input->post('query'))		
		{		
			$this->home_model->redir();	
		}	
		/*$this->db->select('domainname');
		$this->db->order_by("currentdt", "desc");
		$this->db->limit(25);
		$result['recent'] = $this->db->get('list');*/
		$result="";
		
        $this->template->set('title', ' Whois lookup Domain and IP - DailyWhois.com');
        $this->template->load('layouts/main', 'home',$result);
    }


    public function domain()
    {
    	$querydomain=$this->uri->segment(2);
    	if($querydomain) 
    	{
     	 	// check result avil indatabase
     	 	$status=$this->home_model->check_result($querydomain);
    	 	if ($status)
			{ 	
				//$res['rawdata']=$status;
				$res['rawdata'] = $this->home_model->get_raw($status->domain_id,$status->domain_name);

			}
			else
			{
	    	 	$server=$this->home_model->checkwhoisserver($querydomain);
	    	 	$org=$this->home_model->check_org($querydomain);
	    	 	if ($server||$org)
				{ 
					$res['rawdata']=$this->home_model->checkDomain($querydomain,$server);
					$result= $this->home_model->rawtoarray($res['rawdata'],$server);
					$nresult=$this->home_model->extractwhois($result);
					$res_id = $this->home_model->build_result($querydomain,$nresult);
					$this->home_model->write_data($res['rawdata'],$querydomain,$res_id);
				}
				else
				{
					$res['rawdata']=$this->home_model->checkDomain($querydomain);
					$result=$this->home_model->getwhoisserver($querydomain,$res['rawdata']);
				}				
			}

			
			$this->template->set('title', ' Whois lookup Domain and IP - DailyWhois.com');
			$this->template->load('layouts/main', 'domain',$res);
    	
	    }
	    else
	    {
	    	$target=site_url();				
			header("Location: " . $target);
	    }
    	
    }


	



}

/* End of file home.php */
/* Location: ./application/controllers/home.php */