<?php
// CMS file: authentication
// last known update: 2014-02-03

require_once 'zefiro/ini.php';

$dbi->requireUserAuthentication ();

unset($_SESSION[Z_SESSION_NAME]['user']);

$dbi->importUserData();

$layout
	->set('title',L_LOGOUT)
	->set('content',
		'<p>'.L_LOGGED_OUT.'</p>'.
		createHomeLink ()
	)
	->cast();

?>