<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl_subdomain_model extends CI_Model {

  /**
   * Get all subdomains
   *
   * @access public
   * @return object all subdomain details
   */
  function get()
  {
    return $this->db->get('tripdocs_acl_subdomain')->result();
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Get all unrestricted subdomains
   * @access public
   * 
   * @return object subdomain details
   */
  function get_unrestricted()
  {
    return $this->db->get_where('tripdocs_acl_subdomain',  array('all_access' => TRUE))->result();
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Get all restricted subdomains
   * @access public
   * 
   * @return object subdomain details
   */
  function get_restricted()
  {
    return $this->db->get_where('tripdocs_acl_subdomain',  array('all_access' => FALSE))->result();
  }
  
  // --------------------------------------------------------------------
  
  /**
   * Get subdomain by id
   * @param int $subdomain_id
   * @access public
   * 
   * @return object subdomain details
   */
  function get_by_id($subdomain_id)
  {
    return $this->db->get_where('tripdocs_acl_subdomain', array('id' => $subdomain_id))->row();
  }

  // --------------------------------------------------------------------

  /**
   * Get subdomain by name
   * @param string $subdomain_name
   * @access public
   * 
   * @return object subdomain details
   */
  function get_by_name($subdomain_name)
  {
    return $this->db->get_where('tripdocs_acl_subdomain', array('name' => $subdomain_name))->row();
  }

  // --------------------------------------------------------------------

  /**
   * Get subdomains by account_id
   *
   * @access public
   * @param int $account_id
   * @return object all account subdomains
   */
  function get_by_account_id($account_id)
  {
    $this->db->select('tripdocs_acl_subdomain.*');
    $this->db->from('tripdocs_acl_subdomain');
    $this->db->join('tripdocs_rel_account_subdomain', 'tripdocs_acl_subdomain.id = tripdocs_rel_account_subdomain.subdomain_id', 'left');
    $this->db->where("tripdocs_rel_account_subdomain.account_id = $account_id");
    $this->db->or_where("tripdocs_acl_subdomain.all_access", 1);
    
    return $this->db->get()->result();
  }

  // --------------------------------------------------------------------

  /**
   * Get count of users assigned to provided subdomain
   *
   * @access public
   * @param int $subdomain_id
   * @return int user count
   */
  function get_user_count($subdomain_id)
  {
    $this->db->select('account_id');
    $this->db->from('tripdocs_rel_account_subdomain');
    $this->db->where('subdomain_id', $subdomain_id);
    $query = $this->db->get();

    return $query->num_rows();
  }

  // --------------------------------------------------------------------

  /**
   * Get subdomains by account_id
   *
   * @access public
   * @param string $subdomain_name
   * @param int $account_id
   * @return object all account subdomains
   */
  function has_subdomain($subdomain_name, $account_id)
  {
    $this->db->select('tripdocs_acl_subdomain.*');
    $this->db->from('tripdocs_acl_subdomain');
    $this->db->join('tripdocs_rel_account_subdomain', 'tripdocs_acl_subdomain.id = tripdocs_rel_account_subdomain.subdomain_id');
    $this->db->where("tripdocs_rel_account_subdomain.account_id = $account_id AND tripdocs_acl_subdomain.name = `$subdomain_name`");
    
    return ($this->db->count_all_results() > 0);
  }

  // --------------------------------------------------------------------
  
  /**
   * Update or create subdomain details
   *
   * @access public
   * @param int $subdomain_id
   * @param array $attributes
   * @return integer subdomain id
   */
  function update($subdomain_id, $attributes = array())
  {
  	$existing = $this->get_by_id($subdomain_id);
  	
    // Update
    if ( !empty($existing) )
    {      
      $this->db->where('id', $subdomain_id);
      $this->db->update('tripdocs_acl_subdomain', $attributes);
      
      $updated = $this->get_by_id($subdomain_id);
      
      if ($existing->name !== $updated->name)
      {
         $this->rename_subdomain_dir( $existing->name, $updated->name);
      }
      
      if ( $updated->all_access )
      {
        // Delete the relationships
        $this->db->delete('tripdocs_rel_account_subdomain', array('subdomain_id' => $subdomain_id));
      }
    }
    // Insert
    else
    {
      $this->db->insert('tripdocs_acl_subdomain', $attributes);
      $subdomain_id = $this->db->insert_id();
      
      if ( $subdomain_id > 0 ) {
        $subdomain = $this->get_by_id($subdomain_id);
        
        // Create the subdomain directory in the filesystem
        $this->create_subdomain_dir( $subdomain->name );
      }
    }

    return $subdomain_id;
  }

  // --------------------------------------------------------------------

  /**
   * Delete subdomain
   *
   * @access public
   * @param int $subdomain_id
   * @return void
   */
  function delete($subdomain_id)
  {
    // Load the subdomain and get its name
    $subdomain = $this->get_by_id($subdomain_id);
    
    if ( !empty($subdomain) )
    {      
      $name = $subdomain->name;
    
      // Delete the relationships
      $this->db->delete('tripdocs_rel_account_subdomain', array('subdomain_id' => $subdomain_id));
      // Delete the subdomain
      $this->db->delete('tripdocs_acl_subdomain', array('id' => $subdomain_id));
    
      // Delete the subdomain record from the filesystem
  	  $this->load->config('tripdocs');
  	  $path = $this->config->item('tripdocs_subdomaindir');
  	  $deletepath = $path . DIRECTORY_SEPARATOR . $name;
  	  
  	  if (file_exists($deletepath))
  	  {
  	    $this->rrmdir($deletepath);
  	  }
  	}
  }
  
  /**
   *
   * @access public
   * @return void
   */
  function scan_for_subdomains()
  {
  	$this->load->config('tripdocs');
  	
  	// Delete all instances of this file in the cookie filesystem
    $path = $this->config->item('tripdocs_subdomaindir');
	$results = scandir($path);
	
	$subdomains = array();
	$existing_names = array();
	$existing_subdomains = $this->get();
	
	foreach ($results as $result) {
    	if ($result === '.' or $result === '..') continue;

    	if ( is_dir($path . DIRECTORY_SEPARATOR . $result)) {
    		
    		$subdomains[] = $result;
    	}
    }
    
    foreach ($existing_subdomains as $s) {
    	$existing_names[] = $s->name;
    	
    	if ( !in_array( $s->name, $subdomains )) {
    		// Delete the subdomain entry and any related relationships
    		$this->delete($s->id);
    	}
    }
    
    foreach ($subdomains as $s) {
    	if ( !in_array( $s, $existing_names )) {
    		// Create the subdomain entry
    		$subdomain = array(
    			"name" => $s,
			);
    		$this->update(0, $subdomain);
    	}
    }
  }
  
  function clean_cookies()
  {
    $path = $this->config->item('tripdocs_cookiedir');
	$results = scandir($path);
		
	foreach ($results as $result) {
    	if ($result === '.' or $result === '..') continue;
    	
    	$subpath = $path . DIRECTORY_SEPARATOR . $result;

    	if (is_dir($subpath)) {
    		$cookieresults = scandir( $subpath );
    		
    		foreach ($cookieresults as $cookie ) {
    		    if ($cookie === '.' or $cookie === '..') continue;
    		
    			$cookiepath = $subpath . DIRECTORY_SEPARATOR . $cookie;
    			
    			if (is_file($cookiepath)) {
    				if (filemtime($cookiepath) < time() - $this->config->item('tripdocs_expire')) {
  						unlink($cookiepath);
					}
    			}
    		}
    	}
    }
  }
  
  function create_subdomain_dir( $new )
  {
    $this->load->config('tripdocs');
    
  	$path = $this->config->item('tripdocs_subdomaindir');
    $newpath = $path . DIRECTORY_SEPARATOR . $new;
      
    // Check if the new directory exists
    if ( file_exists($newpath))
    {
      if ( is_file($newpath) )
      {
        // Delete the file and create the directory
        unlink($newpath);
        mkdir($newpath);
      }
    } 
    else
    {
      // Create the directory      
      mkdir($newpath);
    }
  }
  
  function rename_subdomain_dir( $existing, $updated )
  {
    $this->load->config('tripdocs');
    
  	$path = $this->config->item('tripdocs_subdomaindir');  
  	
  	$updatedpath = $path . DIRECTORY_SEPARATOR . $updated;
  	$existingpath = $path . DIRECTORY_SEPARATOR . $existing;
  	
  	if ( file_exists($updatedpath) && is_file($updatedpath) )
    {
      // Exists but if a file - delete the file
      unlink($updatedpath);
    }
    
    if ( file_exists($existingpath) && is_dir($existingpath) )
    {
      // Existing directory exists
      if ( file_exists($updatedpath) )
      {
        // Remove the EXISTING DIRECTORY as the UPDATED already exists
        $this->rrmdir($existingpath);
      }
      else
      {
        // Move the EXISTING directory to the UPDATED directory
        rename($existingpath, $updatedpath);
      }
    }
    else
    {
      // Create the UPDATED DIRECTORY
      mkdir($updatedpath);
    }
  }
  
  function rrmdir($dir)
  {
    if (is_dir($dir)) 
    {
      $ds = DIRECTORY_SEPARATOR;
            
      $objects = scandir($dir); 
      
      foreach ($objects as $object) { 
        if ($object != "." && $object != "..") 
        { 
          if (filetype($dir . $ds .$object) == "dir") $this->rrmdir($dir . $ds . $object); else unlink($dir . $ds . $object); 
        } 
      } 
      reset($objects); 
      rmdir($dir); 
    } 
  }
}