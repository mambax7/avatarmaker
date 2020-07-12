<?php
// $Id: xoops_version.php,v 1.0 2004/02/09 12:33:12 chiron Exp $

$modversion['name'] = _MI_AVATARMAKER_NAME;
$modversion['description'] = _MI_AVATARMAKER_DESC;
$modversion['version'] = '1.01a';
$modversion['credits'] = '';
$modversion['author'] = '<a href="http://petitoops.net" target="_blank">PetitOOps</a> traduction par BJ http://www.totalgratuit.com ';
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "slogo.png";
$modversion['dirname'] = "avatarmaker";

// Admin
$modversion['hasAdmin'] = 0;
$modversion['adminmenu'] = '';

// Menu
$modversion['hasMain'] = 1;

$modversion['templates'][1] = array(
	'file'        => 'avatarmaker_index.html',
	'description' => 'avatarmaker'
);
?>
