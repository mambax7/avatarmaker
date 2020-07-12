<?php
include('AvatarMaker.php');

$avatar = new AvatarMaker();
/*
for ($i = 1; (
	array_key_exists('i' . $i, $HTTP_GET_VARS) &&
	!empty($HTTP_GET_VARS['i' . $i]) &&
	is_file('img/' . $HTTP_GET_VARS['i' . $i] . '.gif')
	); $i++)
{
*/
for ($i = 1; avatarmaker_check_file($i, $HTTP_GET_VARS); $i++){
	$avatar->addImage('img/' . $HTTP_GET_VARS['i' . $i] . '.' . $avatar->type, $avatar->type);
}

if (array_key_exists('preview', $HTTP_GET_VARS))
{
	$avatar->show();
}

$avatar->destroy();
?>
