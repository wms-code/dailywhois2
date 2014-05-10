<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }



   	 //home page

   function redir()
   {
   	$url=strtolower($this->input->post('query'));
	$url =str_replace(' ','',$url);
	$domain=$this->domain_spilt($url); 
			
		if (!strpos($domain, "."))
			{
				$target=site_url();				
				header("Location: " . $target);	
				exit(0);
			}
			$domainname=$this->validatedomain($domain);
					
			if ($domainname === false)
			{
				if(!filter_var($domainname, FILTER_VALIDATE_IP)) {
				//echo "Not a valid IP address! Or Domain name";
				$target=site_url();				
				header("Location: " . $target);
				exit(0);
				}
				else
				{
				$target=site_url()."ip/".$domain;				
				header("Location: " . $target);
				}											
			}					
			else
			{
				$target=site_url()."domain/".$domainname;				
				header("Location: " . $target);				
			}
   }

  	 //domain page 

    function check_result($querydomain='')
    {
    	$querydomain=strtolower($querydomain);
	 	$this->db->where('domain_name', $querydomain); 
	 	$query = $this->db->get('domain');
		 	if ($query->num_rows() > 0)
			{ 
				foreach ($query->result() as $row) return $row;
			}
			else
			{
				return false;
			}
    }

    function checkwhoisserver($querydomain='')
    {
		$query="SELECT * FROM  whoisserver WHERE  server_id =(SELECT server_id FROM  temp_domains WHERE  dom_name= '".$querydomain."')";
		$query=$this->db->query($query);
		 	if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)	
					{											
						$server=$row->server_name;
					}
				return $server;
			}
			else
			{
				return false;
			} 
	}
	
	function write_data($rawdata='',$querydomain,$res_id="1")
	{
		//write raw data

		$folder=1;
		if($res_id!=1 && $res_id > 10000)
		{
			$folder = floor($res_id/10000) + 1;
		}
		$dir='rawdata/'.$folder;
		$filename=$dir.'/'.$querydomain.'.txt';
		if (!file_exists($dir)) {
		    mkdir($dir, 0755, true);
		}
		file_put_contents($filename, $rawdata);

		return true;
	}

	function get_raw($domid,$domain_name)
	{
		$folder = 1;
		if($domid > 10000)
		{
			$folder = ceil($domid/10000) + 1 ;
		}

		$dir='rawdata/'.$folder;
		$filename=$dir.'/'.$domain_name.'.txt';
		$test = file_get_contents($filename,true);
		return $test;
	}
 	


 	function build_result($querydomain,$nresult)
 	{
		$result=array();
		$result['domain_name'] =$querydomain;
		foreach ($nresult as  $nvalue)
		{
			$result = $result+$nvalue;
		}
		if(array_key_exists('domain_nserver',$result))
				 {
				 	unset($result["domain_nserver"]);
				 }

		if(array_key_exists('domain_status',$result))
				 {
				 	unset($result["domain_status"]);
				 }
		$this->db->insert('domain',$result);
		return $this->db->insert_id();
		/*	
		if (array_key_exists('domain_name', $result)) {
			    echo "The 'first' element is in the array";
			}
		print_r(array_keys($result));*/
		
 	}


 	function getwhoisserver($querydomain,$rawdata='')
 	{

    	if ($rawdata) 
    	{
    		//find domain name
    		if ( (stristr($rawdata, 'Whois Server:') ))
	    	{
	    	 	return $result = $this->newwhoisserver($rawdata,$querydomain);
			}	
			else
	    	{
	    		echo"unable to define whois server";
	    		print_r($rawdata);
	    		exit();
	    	}

		}
    	else
    	{
    		echo"domains is available";
    		print_r($rawdata);
    		exit();
    	}

 	}



    function newwhoisserver($rawdata='',$querydomain)
    {
	 	$nresult= $this->rawtoarray($rawdata);
	 	$result=array();
	 	foreach ($nresult as  $nvalue) {
	 		$result = $result+$nvalue;
	 		}
    	 	//get whois server id 
    	 	
    	 	if (isset($result['Whois Server'])) 
    	 	{
	    	 	$whoisserver=$result['Whois Server'];
	    	 	$domstatus=$result['Status'];
	    	 	$this->db->where('server_name', $whoisserver); 
	    	 	$query = $this->db->get('whoisserver');
	    	 	if ($query->num_rows() > 0)
				{ 
					foreach ($query->result() as $row)	$serverid=$row->server_id;
				}
				else 
				{
					$this->db->set('server_name', $whoisserver); 
					$this->db->insert('whoisserver'); 
					$serverid=$this->db->insert_id();
				}
				$this->db->set('dom_name', $querydomain); 
				$this->db->set('server_id', $serverid);
				$this->db->set('dom_status', $domstatus); 
		 		$query = $this->db->insert('temp_domains');
    	 	}
    	 	return $result;
    }

    function check_org($domain='')
    {
    	$domainarr=explode('.', $domain, 2);
		if ($domainarr[1]==="org"||$domainarr[1]==="info"||$domainarr[1]==="in"||$domainarr[1]==="co.in")
		{ return true;} 

		return false;		
    }

	function checkDomain($domain,$server='')
	{        
		$domainarr=explode('.', $domain, 2);
		$ext=".".$domainarr[1];			
        // Extensions to be checked
		$extensions = array(
		    '.com'      => array('whois.verisign-grs.com','No match for'),
		    '.info'     => array('whois.afilias.net','NOT FOUND'),  
		    '.net'      => array('whois.crsnic.net','No match for'),
		   // '.co.uk'    => array('whois.nic.uk','No match'),
		    '.in'   	=> array('whois.inregistry.in','NOT FOUND'), 
		    '.co.in'    => array('whois.inregistry.in','NOT FOUND'),         
		    '.org'      => array('whois.pir.org','NOT FOUND'),
		    '.biz'      => array('whois.biz','Not found'),
		    //'.tv'       => array('whois.nic.tv', 'No match for'),
		);
		if ($server=='') $server=$extensions[$ext][0];	
		
		
		if($domain && isset($ext))
		{
	        // Open a socket connection to the whois server
	        $con =@ fsockopen($server, 43,$errno, $errstr, 20) or die("Socket Error " . $errno . " - " . $errstr);
	        if (!$con) return false;
	        if($server == "whois.verisign-grs.com") $domain = "=".$domain; // whois.verisign-grs.com requires the equals sign ("=") or it returns any result containing the searched string.
	        // Send the requested doman name
	        fputs($con, $domain."\r\n");

	        // Read and store the server response
	        $response = ' :';
	        while(!feof($con)) {
	            $response .= fgets($con,128); 
	        }

	        // Close the connection
	        fclose($con);
	        // Check the response stream whether the domain is available
	        if ( (stristr($response, $extensions[$ext][1]) ) ) 
	        {
	        	return FALSE;
	        }
	        else
	        {
	        	return $response;
	        } 	
   		}
    }


    function rawtoarray($output,$server="")
    {
    	if ($server==="whois.omnis.com") {
    		# code...
    		echo "need to prase ".$server;
    		exit();
    	}
    	$output = strstr($output, 'Domain Name');
    	$output= explode("\n", $output);
    	$myvalnew=array();
    	foreach ($output as $value) 
		{
			if (strpos($value,':') !== false) 
			{
			# code...
			$myval= explode(':', $value, 2);
			$myvalnew[][trim($myval[0])]= trim($myval[1]);
			}
		}
		return $myvalnew;
    }


    


	function extractwhois($res)
	{
			$items = array(
				//'Domain Name' => 'domain_name',
				//'Domain ID' => 'domain_handle',
				'Sponsoring Registrar' => 'domain_sponsor',
				'Registrar ID' => 'domain_sponsor',
				'Registrar' => 'domain_sponsor',
				'Domain Status' => 'domain_status',
				'Status' => 'domain_status',
				'Name Server' => 'domain_nserver',
				'Nameservers' => 'domain_nserver',
				'Maintainer' => 'domain_referer', 
				'Domain Registration Date' => 'domain_created',
				'Domain Create Date' => 'domain_created',
				'Domain Expiration Date' => 'domain_expires',
				'Domain Last Updated Date' => 'domain_changed',
				'Updated Date' => 'domain_changed',				
				'Creation Date' => 'domain_created',
				'Last Modification Date' => 'domain_changed',
				'Expiration Date' => 'domain_expires',
				'Registry Expiry Date' => 'domain_expires',
				'Registrar Registration Expiration Date'=>'domain_expires',
				'Created On' => 'domain_created',
                'Last Updated On' => 'domain_changed',
                'Expiration Date' => 'domain_expires',

                //'Registrant ID' => 'owner_handle',
				'Registrant Name' => 'owner_name',
				'Registrant Organization' => 'owner_organization',
				'Registrant Address' => 'owner_address_street',
				'Registrant Address1' => 'owner_address_street',
				'Registrant Address2' => 'owner_address_street',
				'Registrant Street' => 'owner_address_street',
				'Registrant Street1' => 'owner_address_street',
				'Registrant Street2' => 'owner_address_street',
				'Registrant Street3' => 'owner_address_street',
				'Registrant Postal Code' => 'owner_address_pcode',
				'Registrant City' => 'owner_address_city',
				'Registrant State/Province' => 'owner_address_state',
				'Registrant Country' => 'owner_address_country',
				'Registrant Country/Economy' => 'owner_address_country',
				'Registrant Phone Number' => 'owner_phone',
				'Registrant Phone' => 'owner_phone',
				//'Registrant Facsimile Number' => 'owner_fax',
				//'Registrant FAX' => 'owner_fax',
				'Registrant Email' => 'owner_email',
				'Registrant E-mail' => 'owner_email',

				//'Administrative Contact ID' => 'admin_handle',
				'Administrative Contact Name' => 'admin_name',
				'Administrative Contact Organization' => 'admin_organization',
				'Administrative Contact Address' => 'admin_address_street',
				'Administrative Contact Address1' => 'admin_address_street',
				'Administrative Contact Address2' => 'admin_address_street',
				'Administrative Contact Postal Code' => 'admin_address_pcode',
				'Administrative Contact City' => 'admin_address_city',
				'Administrative Contact State/Province' => 'admin_address_state',
				'Administrative Contact Country' => 'admin_address_country',
				'Administrative Contact Phone Number' => 'admin_phone',
				'Administrative Contact Email' => 'admin_email',
				//'Administrative Contact Facsimile Number' => 'admin_fax',
				'Administrative Contact Tel' => 'admin_phone',
				//'Administrative Contact Fax' => 'admin_fax',
				//'Administrative ID' => 'admin_handle',
				'Administrative Name' => 'admin_name',
				'Administrative Organization' => 'admin_organization',
				'Administrative Address' => 'admin_address_street',
				'Administrative Address1' => 'admin_address_street',
				'Administrative Address2' => 'admin_address_street',
				'Administrative Postal Code' => 'admin_address_pcode',
				'Administrative City' => 'admin_address_city',
				'Administrative State/Province' => 'admin_address_state',
				'Administrative Country/Economy' => 'admin_address_country',
				'Administrative Phone' => 'admin_phone',
				'Administrative E-mail' => 'admin_email',
				//'Administrative Facsimile Number' => 'admin_fax',
				'Administrative Tel' => 'admin_phone',
				//'Administrative FAX' => 'admin_fax',
				//'Admin ID' => 'admin_handle',
				'Admin Name' => 'admin_name',
				'Admin Organization' => 'admin_organization',
				'Admin Street' => 'admin_address_street',
				'Admin Street1' => 'admin_address_street',
				'Admin Street2' => 'admin_address_street',
				'Admin Street3' => 'admin_address_street',
				'Admin Address' => 'admin_address_street',
				'Admin Address2' => 'admin_address_street',
				'Admin Address3' => 'admin_address_street',
				'Admin City' => 'admin_address_city',
				'Admin State/Province' => 'admin_address_state',
				'Admin Postal Code' => 'admin_address_pcode',
				'Admin Country' => 'admin_address_country',
				'Admin Country/Economy' => 'admin_address_country',
				'Admin Phone' => 'admin_phone',
				//'Admin FAX' => 'admin_fax',
				'Admin Email' => 'admin_email',
				'Admin E-mail' => 'admin_email',

				//'Technical Contact ID' => 'tech_handle',
				'Technical Contact Name' => 'tech_name',
				'Technical Contact Organization' => 'tech_organization',
				'Technical Contact Address' => 'tech_address_street',
				'Technical Contact Address1' => 'tech_address_street',
				'Technical Contact Address2' => 'tech_address_street',
				'Technical Contact Postal Code' => 'tech_address_pcode',
				'Technical Contact City' => 'tech_address_city',
				'Technical Contact State/Province' => 'tech_address_state',
				'Technical Contact Country' => 'tech_address_country',
				'Technical Contact Phone Number' => 'tech_phone',
				//'Technical Contact Facsimile Number' => 'tech_fax',
				'Technical Contact Phone' => 'tech_phone',
				//'Technical Contact Fax' => 'tech_fax',
				'Technical Contact Email' => 'tech_email',
				//'Technical ID' => 'tech_handle',
				'Technical Name' => 'tech_name',
				'Technical Organization' => 'tech_organization',
				'Technical Address' => 'tech_address_street',
				'Technical Address1' => 'tech_address_street',
				'Technical Address2' => 'tech_address_street',
				'Technical Postal Code' => 'tech_address_pcode',
				'Technical City' => 'tech_address_city',
				'Technical State/Province' => 'tech_address_state',
				'Technical Country/Economy' => 'tech_address_country',
				'Technical Phone Number' => 'tech_phone',
				//'Technical Facsimile Number' => 'tech_fax',
				'Technical Phone' => 'tech_phone',
				//'Technical Fax' => 'tech_fax',
				//'Technical FAX' => 'tech_fax',
				'Technical E-mail' => 'tech_email',
				//'Tech ID' => 'tech_handle',
				'Tech Name' => 'tech_name',
				'Tech Organization' => 'tech_organization',
				'Tech Address' => 'tech_address_street',
				'Tech Address2' => 'tech_address_street',
				'Tech Address3' => 'tech_address_street',
				'Tech Street' => 'tech_address_street',
				'Tech Street1' => 'tech_address_street',
				'Tech Street2' => 'tech_address_street',
				'Tech Street3' => 'tech_address_street',
				'Tech City' => 'tech_address_city',
				'Tech Postal Code' => 'tech_address_pcode',
				'Tech State/Province' => 'tech_address_state',
				'Tech Country' => 'tech_address_country',
				'Tech Country/Economy' => 'tech_address_country',
				'Tech Phone' => 'tech_phone',
				//'Tech FAX' => 'tech_fax',
				'Tech Email' => 'tech_email',
				'Tech E-mail' => 'tech_email',

				//'Billing Contact ID' => 'billing_handle',
				// 'Billing Contact Name' => 'billing_name',
				// 'Billing Contact Organization' => 'billing_organization',
				// 'Billing Contact Address1' => 'billing_address_street',
				// 'Billing Contact Address2' => 'billing_address_street',
				// 'Billing Contact Postal Code' => 'billing_address_pcode',
				// 'Billing Contact City' => 'billing_address_city',
				// 'Billing Contact State/Province' => 'billing_address_state',
				// 'Billing Contact Country' => 'billing_address_country',
				// 'Billing Contact Phone Number' => 'billing_phone',
				// 'Billing Contact Facsimile Number' => 'billing_fax',
				// 'Billing Contact Email' => 'billing_email',
				// //'Billing ID' => 'billing_handle',
				// 'Billing Name' => 'billing_name',
				// 'Billing Organization' => 'billing_organization',
				// 'Billing Address' => 'billing_address_street',
				// 'Billing Address1' => 'billing_address_street',
				// 'Billing Address2' => 'billing_address_street',
				// 'Billing Address3' => 'billing_address_street',
				// 'Billing Street' => 'billing_address_street',
				// 'Billing Street1' => 'billing_address_street',
				// 'Billing Street2' => 'billing_address_street',
				// 'Billing Street3' => 'billing_address_street',
				// 'Billing City' => 'billing_address_city',
				// 'Billing Postal Code' => 'billing_address_pcode',
				// 'Billing State/Province' => 'billing_address_state',
				// 'Billing Country' => 'billing_address_country',
				// 'Billing Country/Economy' => 'billing_address_country',
				// 'Billing Phone' => 'billing_phone',
				// 'Billing Fax' => 'billing_fax',
				// 'Billing FAX' => 'billing_fax',
				// 'Billing Email' => 'billing_email',
				// 'Billing E-mail' => 'billing_email',
				
				// //'Zone ID' => 'zone_handle',
    			// 'Zone Organization' => 'zone_organization',
    			//'Zone Name' => 'zone_name',
    			//'Zone Address' => 'zone_address_street',
    			//'Zone Address 2' => 'zone_address_street',
    			//'Zone City' => 'zone_address_city',
    			//'Zone State/Province' => 'zone_address_state',
    			//'Zone Postal Code' => 'zone_address_pcode',
    			//'Zone Country' => 'zone_address_country',
    			//'Zone Phone Number' => 'zone_phone',
    			//'Zone Fax Number' => 'zone_fax',
    			//'Zone Email' => 'zone_email'
				 );

				$arrnew = array();
				//print_r($res);
				
				$i=0;	$s=0;
				foreach ($res as $values) 
				{
					
					foreach ($values as $key => $value)
					{
						
						//check date
						if(isset($items[$key]))
						{
							$ts = $items[$key];

							if ($ts==="domain_created"||$ts==="domain_changed"||$ts==="domain_expires")
							{
								$value = date("Y-m-d", strtotime($value));
							}							
							
							if ($i< 4 && $items[$key]==="domain_nserver") {	$i++;	$ts=$ts.$i;	}
							if ($s< 4 && $items[$key]==="domain_status") { $s++;  $ts=$ts.$s; }

							$arrnew[][$ts] = $value;

						}
							

					}
				}
				 

				return $arrnew;
		}


  function domain_spilt($url)
	{
	$bits = explode('/', $url);
	if ($bits[0]=='http:' || $bits[0]=='https:')	{
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
	return $regs['domain'];
	}
	return $bits[0];
	} 
	else{
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $bits[0], $regs)) 
	{return $regs['domain'];}				
	return $url;					
	}
	}


	function validatedomain($domain)
	{
		if(!preg_match("/^([a-z0-9\-]{2,100})\.([a-z\.]{2,8})$/i", $domain)) 
		{
			return false;
		}
			return $domain;
	}
}

/* End of file home_model.php */
/* Location: ./application/modles/home_model.php */