<?php
/**
 * Functions Plugin
 * 
 * this plugin bla bla bla
 * 
 * @author     Joshua Morse <dashvibe@gmail.com>
 */

function install()
{
  global $_plugin;
  global $success;
  global $error;
  global $errors;

  !plugin_is_installed($_plugin['name'])
    or $error = $errors['installed'];

  create_dir($_plugin['dir']['user'])
    or $error = $errors['create_dir'];

  create_dir($_plugin['dir']['base'])
    or $error = $errors['create_dir'];

  set_as_installed()
    or $error = $errors['installing'];

  $success = $_plugin['name'].' was successfully installed';
}

function uninstall()
{
  global $_plugin;
  global $success;
  global $error;
  global $errors;

  plugin_is_installed($_plugin['name'])
    or $error = $errors['not_installed'];

  delete_dir($_plugin['dir']['base'])
    or $error = $errors['delete_dir'];
    
  delete_dir($_plugin['dir']['user'])
    or $error = $errors['delete_dir'];
    
  set_as_uninstalled()
    or $error = $errors['uninstalling'];

  $success = $_plugin['name'].' was successfully uninstalled';
}

function build($argv)
{
  global $_plugin;
  global $success;
  global $error;
  global $errors;

  # Fetch definitions functions.
  require(get_plugin_functions_path_for('definitions'));
  
  $_definition = get_definition_for($argv[3]);

  //print_r($_definition); die();

  # Build declare fields string.

  $_declare_fields = '';
  $_get_methods = '';
  $_one_to_one_methods = '';

  foreach($_definition['fields'] as $field => $data)
  {
    $_declare_fields .= "\t".'private $'.$field.';'."\n"; 

    $_get_methods .= "\n\t".'public function get_'.$field.'()'."\n"; 
    $_get_methods .= "\t".'{'."\n"; 
    $_get_methods .= "\t\t".'return $this->'.$field.';'."\n"; 
    $_get_methods .= "\t".'}'."\n"; 

    if(isset($data['relation']))
    {
      if($data['relation']['type'] == 'one')
      {
        $_one_to_one_methods .= "\n\t".'public function get_'.$data['relation']['name'].'()'."\n"; 
        $_one_to_one_methods .= "\t".'{'."\n"; 
        $_one_to_one_methods .= "\t\t".'$query = mysql_query(\'SELECT * FROM '.$data['relation']['name'].' WHERE id = \'.$this->'.$field.');'."\n"; 
        $_one_to_one_methods .= "\t\t".'return mysql_fetch_assoc($query);'."\n"; 
        $_one_to_one_methods .= "\t".'}'."\n"; 
      }
    }
  }

  # Fetch the template. Change this to example.php?
  $_functions = file_get_contents('plugins/functions/lib/base_template.php'); 

  # Replace the place holders.
  $_functions = str_replace('%table%', $_definition['name'], $_functions);
  $_functions = str_replace('%table_base%', $_definition['name'].'_base', $_functions);
  $_functions = str_replace('////declare_vars', $_declare_fields, $_functions);
  $_functions = str_replace('////get_methods', $_get_methods, $_functions);
  $_functions = str_replace('////one_to_one_methods', $_one_to_one_methods, $_functions);

  # Write the base functions file.
  $handle = fopen($_plugin['dir']['base'].$_definition['name'].'.php', 'w');

  if(fwrite($handle, $_functions))
  {
    $success = 'successfully built functions for '.$_definition['name'].'!';
  }

  # Write the user functions file, if none exists.
  $_user_function_path = $_plugin['dir']['user'].$_definition['name'].'.php';

  if(!file_exists($_user_function_path))
  {
    # Fetch the user template.
    $_functions = file_get_contents('plugins/functions/lib/user_template.php'); 

    $_functions = str_replace('%table_base%', $_definition['name'].'_base', $_functions);
    $_functions = str_replace('%table_user%', $_definition['name'], $_functions);

    # Write the user functions file.
    $handle = fopen($_plugin['dir']['user'].$_definition['name'].'.php', 'w');

    if(fwrite($handle, $_functions))
    {
      $success = 'successfully built functions for '.$_definition['name'].'!';
    }
  }
}
