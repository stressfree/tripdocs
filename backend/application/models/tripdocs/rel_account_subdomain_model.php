<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rel_account_subdomain_model extends CI_Model {

  /**
   * Get all account subdomains
   *
   * @access public
   * @return object all account permissions
   */
  function get()
  {
    return $this->db->get('tripdocs_rel_account_subdomain')->result();
  }

  /**
   * Get subdomains by account id
   *
   * @access public
   * @param int $account_id
   * @return object account details object
   */
  function get_by_account_id($account_id)
  {
    $this->db->select('tripdocs_acl_subdomain.*');
    $this->db->from('tripdocs_rel_account_subdomain');
    $this->db->join('tripdocs_acl_subdomain', 'tripdocs_rel_account_subdomain.subdomain_id = tripdocs_acl_subdomain.id');
    $this->db->where("tripdocs_acl_subdomain.account_id = $account_id");

    return $this->db->get()->result();
  }

  /**
   * Check if account already has this subdomain assigned
   *
   * @access public
   * @param int $account_id
   * @param int $subdomain_id
   * @return object account details object
   */
  function exists($account_id, $subdomain_id) 
  {
    $this->db->from('tripdocs_rel_account_subdomain');
    $this->db->where('account_id', $account_id);
    $this->db->where('subdomain_id', $subdomain_id);

    return ( $this->db->count_all_results() > 0 );
  }

  // --------------------------------------------------------------------
  
  /**
   * Create a new account subdomain
   *
   * @access public
   * @param int $account_id
   * @param int $subdomain_id
   * @return void
   */
  function update($account_id, $subdomain_id)
  {
    // Insert
    if (!$this->exists($account_id, $subdomain_id))
    {
      $this->db->insert('tripdocs_rel_account_subdomain', array('account_id' => $account_id, 'subdomain_id' => $subdomain_id));
    }
  }

  /**
   * Batch update account subdomains.
   *
   * @access public
   * @param int $account_id
   * @param array $subdomain_ids
   * @return void
   */
  function update_batch($account_id, $subdomain_ids)
  {
    // Blank array, then no insert for you
    if( count($subdomain_ids) > 0)
    {
      // Create a new batch
      $batch = array();
      foreach($subdomain_ids as $subdomain_id)
      {
        $batch[] = array(
          'account_id' => $account_id,
          'subdomain_id' => $subdomain_id
          );
      }

      // Insert all the new subdomains
      $this->db->insert_batch('tripdocs_rel_account_subdomain', $batch);
    }
  }

  /**
   * Delete all current subdomains and replace with array of subdomains passed in.
   *
   * @access public
   * @param int $account_id
   * @param array $subdomain_ids
   * @return void
   */
  function delete_update_batch($account_id, $subdomain_ids)
  {
    // Delete all current subdomains
    $this->delete_by_account($account_id);

    // Batch update the account subdomains
    $this->update_batch($account_id, $subdomain_ids);
  }

  /**
   * Delete single instance by account/subdomain
   *
   * @access public
   * @param int $account_id
   * @param int $subdomain_id
   * @return void
   */
  function delete($account_id, $subdomain_id)
  {
    $this->db->delete('tripdocs_rel_account_subdomain', array('account_id' => $account_id, 'subdomain_id' => $subdomain_id));
  }



  /**
   * Delete all subdomains for account
   *
   * @access public
   * @param int $account_id
   * @return void
   */
  function delete_by_account($account_id)
  {
    $this->db->delete('tripdocs_rel_account_subdomain', array('account_id' => $account_id));
  }



  /**
   * Delete all by subdomains id
   *
   * @access public
   * @param int $subdomain_id
   * @return void
   */
  function delete_by_subdomain($subdomain_id)
  {
    $this->db->delete('tripdocs_rel_account_subdomain', array('subdomain_id' => $subdomain_id));
  }
}