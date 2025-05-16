<?php

defined('BASEPATH') or exit('No direct script access allowed');
  // Get Organization
  function getOrganization(){
    $CI = &get_instance();
    $response = array();
    $CI->db->select('*');
    $q = $CI->db->get(db_prefix() .'organization');
    $response = $q->result_array();
    return $response;
  }

  function getOrganizationName($id){
    $CI = & get_instance();
    $CI->db->select('name');
    $CI->db->where('id', $id);
    $task = $CI->db->get(db_prefix() . 'organization')->row();
    if ($task) {
        return $task->name;
    }

    return '';
  }

  // function getAreas(){
  //   $CI = &get_instance();
  //   $response = array();
  //   $CI->db->select('*');
  //   $CI->db->where('status', 1);
  //   $q = $CI->db->get(db_prefix() .'area');
  //   $response = $q->result_array();
  //   return $response;
  // }