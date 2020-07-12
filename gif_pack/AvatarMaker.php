<?php
define('AVATAR_MAKER_WIDTH', 50);
define('AVATAR_MAKER_HEIGHT', 50);
define('AVATAR_MAKER_IMAGE', 11);
define('IMAGE_TYPE', 'GIF'); // GIF or PNG
//include('./config.php');

class AvatarMaker
{
	var $img = null;
	var $width;
	var $height;
	var $type;
	var $debug = false;

	function AvatarMaker($width = AVATAR_MAKER_WIDTH, $height = AVATAR_MAKER_HEIGHT, $type = IMAGE_TYPE)
	{
		$this->width = $width;
		$this->height = $height;
		$this->type = strtolower($type);
	}

	function log($str)
	{
		if($this->debug)
		{
			echo $str;
		}
	}

	function addImage($filename, $filetype)
	{
		if ($filetype == ('gif' || 'png'))
		{
			$imagecreate = 'imagecreatefrom' . $filetype;
		}

		if (is_file($filename))
		{
			if ($this->img == null)
			{
				$this->img = $imagecreate('img/space.' . $this->type);
			}
			$tmp_image = $imagecreate($filename);
			imagecopy($this->img, $tmp_image, 0, 0, 0, 0, $this->width, $this->height);
		}
	}

	function show()
	{
		header("Content-type: image/png");
		imagepng($this->img);
	}

	function save($filename, $mode = 0755)
	{
		imagepng($this->img, $filename);
		chmod($filename, $mode);
	}

	function destroy()
	{
		if ($this->img != null)
		{
			@imagedestroy($this->img);
			$this->img = null;
		}
	}

	function register2xoops()
	{
		global $xoopsUser, $xoopsDB;

		if (!defined('XOOPS_ROOT_PATH') || (empty($xoopsUser)) || (!is_object($xoopsUser)))
		{
			return false;
		}
		$file = 'avt' . $xoopsUser->uid() . base_convert(time(), 10, 16) . '.png';
		$filename = XOOPS_ROOT_PATH . '/uploads/' . $file;
		$this->save($filename, 0666);
		$avt_handler =& xoops_gethandler('avatar');
		$avatar =& $avt_handler->create();
		$avatar->setVar('avatar_file', $file );
		$avatar->setVar('avatar_name', $xoopsUser->getVar('uname'));
		$avatar->setVar('avatar_mimetype', 'image/png');
		$avatar->setVar('avatar_display', 1);
		$avatar->setVar('avatar_type', 'C');
		if (!$avt_handler->insert($avatar))
		{
			$this->log("ok 1<br />");
			@unlink($filename);
		}
		else
		{
			$oldavatar = $xoopsUser->getVar('user_avatar');
			if ($oldavatar && $oldavatar != 'blank.gif' && !preg_match('/^savt/', strtolower($oldavatar)))
			{
				$avatars =& $avt_handler->getObjects(new Criteria('avatar_file', $oldavatar));
				$avt_handler->delete($avatars[0]);
				@unlink(XOOPS_ROOT_PATH.'/uploads/'.$oldavatar);
			}
			$sql = sprintf("UPDATE %s SET user_avatar = '%s' WHERE uid = %u",
				$xoopsDB->prefix('users'), $file, $xoopsUser->getVar('uid'));
			$xoopsDB->queryF($sql);
			$avt_handler->addUser($avatar->getVar('avatar_id'), $xoopsUser->getVar('uid'));
			$this->log("ok 2<br />" . $sql);
		}
		return true;
	}
}

function avatarmaker_check_file($i, &$array, $type = IMAGE_TYPE)
{
	$key = 'i' . $i;
	return (
		array_key_exists($key, $array) &&
		!empty($array[$key]) &&
		is_file('img/' . $array[$key] . '.' . strtolower($type))
	);
}
?>
