<?php
require_once 'lib/limonade.php';
dispatch('/', 'hello');
  function hello()
  {
      return 'Hello world!';
  }
dispatch('/goodbye', 'goodbye');
  function goodbye()
  {
      return 'Goodbye, cruel world!';
  }
run();