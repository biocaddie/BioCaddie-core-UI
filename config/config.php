<?php

//for cookie security issue
/*ini_set("session.use_cookies", 0);
ini_set("session.use_only_cookies", 0);
ini_set("session.use_trans_sid", 1);
ini_set("session.cache_limiter", "");
*/
/*start session*/
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
date_default_timezone_set('America/chicago');

?>
