<?php
require('header.php');
include_once XOOPS_ROOT_PATH . '/modules/avatarmaker/language/' . $xoopsConfig['language'] . '/main.php';
// Include the page header
include(XOOPS_ROOT_PATH . '/header.php');
//include('config.php');
include('AvatarMaker.php');
if ($xoopsTpl)
{
	$all_constants_ = get_defined_constants();
	foreach ($all_constants_ as $key => $val)
	{
		if (preg_match("/^_(MB|MD|AM|MI)_AVATARMAKER_(.)*$/", $key))
		{
			$xoopsTpl->assign($key, $val);
		}
	}
}

if (array_key_exists('preview', $HTTP_POST_VARS))
{
	$image_url = "./preview.php?preview=on";
	for ($i = 1; avatarmaker_check_file($i, $HTTP_POST_VARS); $i++)
	{
		$image_url .= "&i$i=" . urlencode($HTTP_POST_VARS['i' . $i]);
	}
	$xoopsTpl->assign('view', 'preview');
	$xoopsTpl->assign('image_url', $image_url);
}
elseif (array_key_exists('store', $HTTP_POST_VARS))
{
	if(!is_object($xoopsUser)){
		redirect_header(XOOPS_URL.'/' ,1, _MD_AVATARMAKER_PLEASE_LOGIN);
		exit();
	}
	$avatar = new AvatarMaker();
	for ($i = 1; avatarmaker_check_file($i, $HTTP_POST_VARS); $i++)
	{
		$avatar->addImage('img/' . $HTTP_POST_VARS['i'.$i] . '.' . $avatar->type, $avatar->type);
	}
	$result = $avatar->register2xoops();
	$avatar->destroy();
	redirect_header(XOOPS_URL.'/user.php' ,1, _MD_AVATARMAKER_UPDATED);
	exit();
}
else
{
	$xoopsTpl->assign('image_width', AVATAR_MAKER_WIDTH);
	$xoopsTpl->assign('image_height', AVATAR_MAKER_HEIGHT);
}

$checked = array();
for($i = 1; $i <= AVATAR_MAKER_IMAGE; $i++)
{
	$key = 'i' . $i;
	$checkedOption = ' checked="checked"';
	if (!$HTTP_POST_VARS || !array_key_exists($key, $HTTP_POST_VARS) || $HTTP_POST_VARS[$key] == 'space')
	{
		$checked[$key]['space'] = $checkedOption;
	}
	else
	{
		$checked[$key][$HTTP_POST_VARS[$key]] = $checkedOption;
	}
}
$xoopsTpl->assign('checked', $checked);

$xoopsOption['template_main'] = 'avatarmaker_index.html';
// Include the page footer
include(XOOPS_ROOT_PATH . '/footer.php');
?>
