<?php

/*
  Config switch allows staging control implementation.
  By changing the switch number, administrator is able to change the state of the application.
  These switches are fixed. Don't attempt to change them:
  -----------------------------
  0: Production Environment
  1: Development Environment
  2: Test Environment
 */
$config_switch = 2;

/*
  Add your custom switches here and document them.
  Remember to remove any unnessary switches you have created.
 */
$debug_switch = 0;

//session_start();
