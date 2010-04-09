<?php

class song
{
////declare_vars
  public function __construct($id)
  {
    $this->id = $id;
    $this->set_values($this->fetch_values());
  }

  private function fetch_values()
  {
    $query = mysql_query(' 
      SELECT *
      FROM song
      WHERE id = '.$this->id.'
    ');

    return mysql_fetch_assoc($query);
  }

  private function set_values($results)
  {
    foreach($results as $field => $result)
    {
      $this->$field = $result;
    }
  }
////get_methods
}
