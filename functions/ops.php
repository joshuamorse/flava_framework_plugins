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

  # Fetch the definition.
  # Fetch the template.

  $temp = file_get_contents('plugins/functions/lib/template.php'); 

  $str = '';
  $str .= 'private $var;'."\n";
  $str .= "\t".'private $other_var;'."\n";

  $temp = str_replace('////declare_vars', $str, $temp);
  $temp = str_replace('////declare_vars', 'omogmgomgomg', $temp);

  die($temp);
}
