<?php

function get_fixtures_for($fixture_def)
{
  global $_plugin;

  require_once($_plugin['dir']['fixtures'].$fixture_def.'.php');

  return $_fixtures;
}
