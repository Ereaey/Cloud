<?php
include('class.Sql.php');
Sql::init("localhost", "blog", "root", "worldiscorrupt");
ini_set('display_errors', 1);
error_reporting(E_ALL);

function generatePassword($size = 8)
{
    $passwd = strtolower(md5(uniqid(rand())));
    $passwd = substr($passwd,2,$size);
    $passwd = strtr($passwd,'o0ODQGCiIl15Ss7','BEFHJKMNPRTUVWX');
    return $passwd;
}

function getId($key)
{
	$data = Sql::selectOne('users', 'keyApi = ?', $key);

	if (!$data)
		return false;
	else
		return $data['id'];
}

function isCreator($key, $id_cloud)
{
	$data = Sql::selectOne('users, cloud', 'keyApi = ? and cloud.id = ? and cloud.id_user = users.id', $key, $id_cloud, 'users.id as id_u');

	if (!$data)
		return false;
	else
		return true;
}

function write_permission($key, $id_cloud)
{
	if (isCreator($key, $id_cloud) == true)
		return true;
	else
	{
		$data = Sql::selectOne('users, permissions, cloud', 'keyApi = ? and permissions.id_cloud = ? and permissions.id_user = users.id', $key, $id_cloud);

		if ($data)
			if ($data['write'] == 1)
				return true;
			else
				return false;
		else
			return false;
	}
}

function read_permission($key, $id_cloud)
{
	if (isCreator($key, $id_cloud) == true)
		return true;
	else
	{
		$data = Sql::selectOne('users, permissions', 'keyApi = ? and permissions.id_cloud = ? and permissions.id_user = users.id', $key, $id_cloud);

		if ($data)
			if ($data['read'] == 1)
				return true;
			else
				return false;
		else
			return false;
	}
}

function delete_permission($key, $id_cloud)
{
	if (isCreator($key, $id_cloud) == true)
		return true;
	else
	{
		$data = Sql::selectOne('users, permissions', 'keyApi = ? and permissions.id_cloud = ? and permissions.id_user = users.id', $key, $id_cloud);

		if ($data)
			if ($data['delete'] == 1)
				return true;
			else
				return false;
		else
			return false;
	}
}



function getIdCloud($id_file)
{
	$data = Sql::selectOne('files', 'id = ?', $id_file);
	if ($data)
		return $data['id_cloud'];
	else
		return false;
}

function getIdCloudfolder($id_folder)
{
	$data = Sql::selectOne('folders', 'id = ?', $id_folder);
	if ($data)
		return $data['id_cloud'];
	else
		return false;
}

function getmime($extension) 
{
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
        );
		return $mime_types[$extension];
	
}

function action_rewrite_site($key, $id_cloud)
{

}

function action_rewrite_site_file($key, $id_file)
{

}


function action_create_min_url($url, $type)
{
	header('Content-Type: image/jpeg');

	$data = Sql::selectOne('min', 'min.lien = ?', urldecode($url), 'name');
	if ($data)
		readfile('../upload/min/'.$data['name']);
	else
	{
		$pathinfo = pathinfo($url);
		$extension = $pathinfo['extension'];
		if ($type == 1)
			$chemin_image = 'https://'.urldecode($url);
		else
			$chemin_image = 'http://'.urldecode($url);
		list($src_w, $src_h) = getimagesize($chemin_image);
		$dst_w = 300;
		$dst_h = 300;
	 
		if($src_w < $dst_w)
			$dst_w = $src_w;
	 
		// Teste les dimensions tenant dans la zone
		$test_h = round(($dst_w / $src_w) * $src_h);
		$test_w = round(($dst_h / $src_h) * $src_w);
	 
		if(!$dst_h)// Si Height final non précisé (0)
			$dst_h = $test_h;
		elseif(!$dst_w) // Sinon si Width final non précisé (0)
			$dst_w = $test_w;
		elseif($test_h>$dst_h) // Sinon teste quel redimensionnement tient dans la zone
			$dst_w = $test_w;
		else
			$dst_h = $test_h;
	 
		if($extension == 'jpg' || $extension == 'jpeg')
		   $img_in = imagecreatefromjpeg($chemin_image);
		else if($extension == 'png')
		   $img_in = imagecreatefrompng($chemin_image);
		else if($extension == 'gif')
		   $img_in = imagecreatefromgif($chemin_image);
		else
			return false;
	 
		$img_out = imagecreatetruecolor($dst_w, $dst_h);
		imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, $dst_w, $dst_h, imagesx($img_in), imagesy($img_in));
	 
	 	$uniqid = uniqid();
	 	$data = array('lien' => urldecode($url), 'name' => $uniqid);
		Sql::insert('min', $data);

		imagejpeg($img_out, '../upload/min/'.$uniqid);
		
		readfile('../upload/min/'.$uniqid);
	}
}

function action_create_min($uid, $extension)
{

	$chemin_image_out = '../upload/min/'.$uid;
	$chemin_image = '../upload/'.$uid;
	list($src_w, $src_h) = getimagesize($chemin_image);
	$dst_w = 500;
	$dst_h = 500;
 
	if($src_w < $dst_w)
		$dst_w = $src_w;
 
	// Teste les dimensions tenant dans la zone
	$test_h = round(($dst_w / $src_w) * $src_h);
	$test_w = round(($dst_h / $src_h) * $src_w);
 
	if(!$dst_h)// Si Height final non précisé (0)
		$dst_h = $test_h;
	elseif(!$dst_w) // Sinon si Width final non précisé (0)
		$dst_w = $test_w;
	elseif($test_h>$dst_h) // Sinon teste quel redimensionnement tient dans la zone
		$dst_w = $test_w;
	else
		$dst_h = $test_h;
 
	if($extension == 'jpg' || $extension == 'jpeg')
	   $img_in = imagecreatefromjpeg($chemin_image);
	else if($extension == 'png')
	   $img_in = imagecreatefrompng($chemin_image);
	else if($extension == 'gif')
	   $img_in = imagecreatefromgif($chemin_image);
	else
		return false;
 
	$img_out = imagecreatetruecolor($dst_w, $dst_h);
	imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, $dst_w, $dst_h, imagesx($img_in), imagesy($img_in));
 
	imagejpeg($img_out, $chemin_image_out);
}

function action_upload_file($id_user, $id_cloud, $id_folder, $file, $upload_key)
{
	$size = $file['size'];
	$pathinfo = pathinfo($file['name']);
	$extension = $pathinfo['extension'];
	$extension = Sql::selectOne('extensions', 'name = ?', $extension);
	$name = $pathinfo['filename'];
	$uniqid = uniqid();
	
	if ($size > 30 * 1024 * 1024)
		return $_SESSION['file_uid_'.$upload_key] = array('done' => true, 'error' => "The file size must be less than 30 MB.");
	if (!$extension)
		return $_SESSION['file_uid_'.$upload_key] = array('done' => true, 'error' => "The file extension is invalid.");

	//Fichier accepté
	
	if ($extension['status'] == 1)
		$content = file_get_contents($file['tmp_name']);
	else
		move_uploaded_file($file['tmp_name'], '../upload/'.$uniqid);

	$d = array('id_cloud' => $id_cloud, 'id_user' => $id_user, 'id_folder' => $id_folder, 'name' => $name, 'uid' =>  $uniqid, 'id_ext' => $extension['id'], 'content' => $content, 'size' => $size);
	Sql::insert('files', $d);

	if ($extension['name'] == 'png' or $extension['name'] == 'jpg' or $extension['name'] == 'jpeg' or $extension['name'] == 'gif')
		action_create_min($uniqid, $extension['name']);

	$_SESSION['file_uid_'.$upload_key] = array('done' => true, 'uid' => $uniqid);
}

function action_read_file($id_file)
{
	$data = Sql::selectOne('files, extensions', 'files.id = ? and extensions.id = id_ext', $id_file, 'files.id as id_file, files.name as name_file, extensions.name as name_ext, id_ext, content, uid');
	if ($data)
	{
		$d = array('success' => true, 'id' => $data['id_file'], 'name' => $data['name_file'], 'id_ext' => $data['id_ext'], 'name_ext' => $data['name_ext'], 'content' => $data['content'], 'uid' => $data['uid']);
		return json_encode($d);
	}
	else
	{
		return json_encode(array('success' => false));
	}
}

function action_write_file($id_file, $content)
{
	Sql::update('files', array('content' => $content), 'id = ?', $id_file);
}

function action_get_current_upload($key)
{
	if (!empty($_SESSION["upload_progress_".$key]['bytes_processed']))
		return json_encode(array('current' => $_SESSION["upload_progress_".$key]['bytes_processed'],
						'total' => $_SESSION["upload_progress_".$key]['content_length']));
	else if (!empty($_SESSION['file_uid_'.$key]))
		return json_encode($_SESSION['file_uid_'.$key]);
	else
		return json_encode(array());
}

function action_get_cloud($id_user)
{
	if ($id_user == false)
	{
		return json_encode(array('success' => false));
	}
	else
	{
		Sql::select('cloud', 'id_user = ?', $id_user);

		$dataCloud = array();
		while ($data = Sql::getData())
		{
		    $d = array('id' => $data['id'], 'name' => $data['name']);
		    array_push($dataCloud, $d);
		}

		return json_encode(array('success' => true, 'data' => $dataCloud));
	}
}

function action_get_cloud_u($id_cloud)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	
	if ($data)
	{
		$dataCloud =  array('id' => $data['id'], 'name' => $data['name'], 'type' => $data['type']);
		return json_encode($dataCloud);
	}
	else
	{
		return json_encode(array('success' => false, 'error' => 'cloud not exist'));
	}

}

function action_get_element($id_cloud, $id_folder)
{
	$dataCloud = array();

	Sql::select('folders, users', 'id_parent = ? and id_cloud = ? and status = 1 and folders.id_user = users.id', $id_folder, $id_cloud, 'folders.id, folders.name, folders.date ,users.pseudo');

	while ($data = Sql::getData())
	{
	    $d = array('id' => $data['id'], 'name' => $data['name'], 'date' => $data['date'], 'name_creator' => $data['pseudo'], 'ext' => "folder");
	    array_push($dataCloud, $d);
	}

	Sql::select('files, users, extensions', 'id_folder = ? and id_cloud = ? and files.id_user = users.id and files.id_ext = extensions.id', $id_folder, $id_cloud, 'files.id, files.name, files.date, users.pseudo, files.size, files.id_ext, extensions.name as name_ext, extensions.status');

	while ($data = Sql::getData())
	{
		$d = array('id' => $data['id'], 'name' => $data['name'], 'date' => $data['date'], 'name_creator' => $data['pseudo'], 'ext' => $data['name_ext'], 'size' => $data['size'], 'status' => $data['status']);

	    array_push($dataCloud, $d);
	}

	return json_encode($dataCloud);
}

function action_create_cloud($id_user, $name, $type)
{
	if ($id_user != false)
	{
		$uid = 'c_'.uniqid();
		$pwd = generatePassword();
		$d = array('id_user' => $id_user, 'name' => $name, 'type' => $type, 'uid' => $uid, 'passwordsql' => $pwd);
		Sql::insert('cloud', $d);
		if ($type == 1)
		{
			mkdir("../Sites/".$uid."/", 0777, true);	
			Sql::query('CREATE USER ?@? IDENTIFIED BY ?;', $uid, '%', $pwd);
			Sql::query('GRANT USAGE ON * . * TO ?@? IDENTIFIED BY ?;', $uid, '%', $pwd);
			Sql::query('CREATE DATABASE IF NOT EXISTS '.$uid.';');
			Sql::query('GRANT ALL PRIVILEGES ON ? . * TO ?@?;', $uid, $uid, '%');
		}
		return json_encode(array('success' => true));
	}
	else
		return json_encode(array('success' => false));
}

function action_new_folder($id_user, $id_cloud, $id_parent, $name, $status)
{
	$d = array('id_cloud' => $id_cloud, 'id_user' => $id_user, 'id_parent' => $id_parent, 'name' => $name, 'status' => $status);
	Sql::insert('folders', $d);
	return json_encode(array('success' => true));
}

function action_delete_folder($id_folder)
{
	Sql::delete('folders', 'id = ?', $id_folder);
}

function action_delete_cloud($id_cloud)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);

	if ($data)
	{
		if ($data['type'] == 1)
			Sql::query('DROP DATABASE '.$data['uid']);
		Sql::delete('cloud', 'id = ?', $id_cloud);
	}
}

function action_direct_download($uid)
{
	$data = Sql::selectOne('files, extensions', 'files.uid = ? and files.id_ext = extensions.id', $uid, 'files.name, files.uid, files.size, files.id_ext, extensions.name as name_ext, content, extensions.status');

	if (!$data)
		return;

	header('Content-Type: application/octet-stream');
    header('Content-Length: '. $data['size']);
    header('Content-disposition: attachment; filename='. str_replace(" ", '_', $data['name']).'.'.$data['name_ext']);
    header('Pragma: public');
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    if ($data['status'] == 1)
    	echo($data['content']);
    else
    	readfile('../upload/'.$data['uid']);

    exit();

}

function action_download($id_file)
{
	$data = Sql::selectOne('files, extensions', 'files.id = ? and files.id_ext = extensions.id', $id_file, 'files.name, files.uid, files.size, files.id_ext, extensions.name as name_ext, content, extensions.status');

	if (!$data)
		return;

	header('Content-Type: application/octet-stream');
    header('Content-Length: '. $data['size']);
    header('Content-disposition: attachment; filename='. str_replace(" ", '_', $data['name']).'.'.$data['name_ext']);
    header('Pragma: public');
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    if ($data['status'] == 1)
    	echo($data['content']);
    else
    	readfile('../upload/'.$data['uid']);

    exit();

}

function action_look_file($id_file)
{
	$data = Sql::selectOne('files, extensions', 'files.id = ? and files.id_ext = extensions.id', $id_file, 'files.name, files.uid, files.size, files.id_ext, extensions.name as name_ext, content, extensions.status');

	if (!$data)
		return;
    if ($data['status'] == 1)
    {
		header('Content-Type: '.getmime($data['name_ext']));
	    header('Content-Length: '. $data['size']);
	    header('Pragma: public');
	    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	    header('Expires: 0');
	    header('Connection:Close');

    	echo($data['content']);
    }
    else if ($data['name_ext'] == 'png' or $data['name_ext'] == 'jpg' or $data['name_ext'] == 'jpeg' or $data['name_ext'] == 'gif')
    {
    	header('Content-Type: image/jpeg');

	    if (file_exists('../upload/min/'.$data['uid']))
    		readfile('../upload/min/'.$data['uid']);
	}
    else if ($data['name_ext'] == "mp3")
    {
    	header('Content-Type: '.getmime($data['name_ext']));
	    header('Content-Length: '.$data['size']);
	    header('Cache-Control: public, must-revalidate, max-age=0');
	    header('Content-Transfer-Encoding: binary');
	    header('Pragma: no-cache');

	    header('Content-Disposition: inline; filename='.$data['name'].'');
	    header('Accept-Ranges: bytes');
	    header('Content-Range: bytes 0-'.($data['size'] - 1). '/'.$data['size']);

	    if (file_exists('../upload/'.$data['uid']))
    		readfile('../upload/'.$data['uid']);
    }
    else if ($data['name_ext'] == "pdf")
    {
    	header('Content-Type: '.getmime($data['name_ext']));
	    header('Content-Length: '.$data['size']);
	    header('Content-Disposition: inline; filename='.$data['name'].'');
	    
	    if (file_exists('../upload/'.$data['uid']))
    		readfile('../upload/'.$data['uid']);
    }

    exit();   
}
function action_delete_file($id_file)
{
	Sql::delete('files', 'id = ?', $id_file);
}

function action_rename_folder($id_folder, $name)
{
	Sql::update('folders', array('name' => $name), 'id = ?', $id_folder);
}

function action_rename_file($id_file, $name)
{
	Sql::update('files', array('name' => $name), 'id = ?', $id_file);
}

function action_move_folder($id_folder, $id_parent)
{
	Sql::update('folders', array('id_parent' => $id_parent), 'id = ?', $id_folder);
}

function action_create_file($id_user, $id_cloud, $id_folder, $name)
{
	$uniqid = uniqid();

	$extension = pathinfo($name, PATHINFO_EXTENSION);
	$name = pathinfo($name, PATHINFO_FILENAME);
	$extensiond = Sql::selectOne('extensions', 'name = ? and status = 1', $extension);
	if ($extensiond)
	{
		$d = array('id_cloud' => $id_cloud, 'id_user' => $id_user, 'id_folder' => $id_folder, 'name' => $name, 'uid' =>  $uniqid, 'id_ext' => $extensiond['id'], 'size' => '0');
		Sql::insert('files', $d);
		$re = array('success' => true);
	}
	else
	{
		$re = array('success' => false, 'error' => 'incorrect extension');
	}
	return json_encode($re);
}

function action_connect($login, $password)
{
	$data = Sql::selectOne('users', 'pseudo = ? and password = ?', $login, $password);

	if($data)
		$dataCloud =  array('success' => true, 'id' => $data['id'], 'login' => $data['pseudo'], 'key' => $data['keyApi']);
	else
		$dataCloud =  array('success' => false);

	return json_encode($dataCloud);
}

function action_get_info_user($id)
{
	$data = Sql::selectOne('users', 'id = ?', $id);
	if ($data)
		return json_encode(array('success' => true, 'name' => $data['login']));
	else
		return json_encode(array('success' => false));
}

function action_register($login, $password, $repassword, $mail)
{
	if ($password != $repassword)
	{
		$d = array('success' => false, 'error' => 'password and repassword');
	}
	else if(Sql::count('users', 'pseudo = ?', $login) > 0)
	{
		$d = array('success' => false, 'error' => 'login');
	}
	else if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
	{
		$d = array('success' => false, 'error' => 'incorrect mail');
	}
	else
	{
		$data = array('pseudo' => $login, 'password' => $password, 'mail' => $mail);
		Sql::insert('users', $data);

		$d = array('success' => true);
	}
	return json_encode($d);
}

function action_get_table($id_cloud)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	if ($data)
	{
		$dataA = array();

		Sql::select('information_schema.TABLES t', 't.TABLE_SCHEMA = ?', $data['uid'], 't.TABLE_NAME');

		while ($result = Sql::getData())
		{
			$d = array('name' => $result['TABLE_NAME'], 'nb' => Sql::count($data['uid'].'.'.$result['TABLE_NAME']));
	    	array_push($dataA, $d);
		}
		return json_encode($dataA);
	}
	else
		return json_encode(array('success' => false));
}

function action_get_table_columns($id_cloud, $name_table)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	if ($data)
	{

		$dataA = array();
		Sql::select('INFORMATION_SCHEMA.COLUMNS c', 'c.TABLE_SCHEMA = ? and c.TABLE_NAME = ?', $data['uid'], $name_table);

		while ($result = Sql::getData())
		{
			$d = array("name" => $result['COLUMN_NAME'], "type" => $result['COLUMN_TYPE'], "key" => $result['COLUMN_KEY'], "extra" => $result['EXTRA']);
			
	    	array_push($dataA, $d);
		}
		
		return json_encode($dataA);
	}
	else
		return json_encode(array('success' => false));
}

function action_get_table_elements($id_cloud, $name_table)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	if ($data)
	{

		$dataA = array();
		Sql::select($data['uid'].'.'.$name_table.' t');

		while ($result = Sql::getData())
		{
			$d = array();
			foreach ($result as &$value) 
			{
			    $d[] = $value;
			}
	    	array_push($dataA, $d);
		}
		
		return json_encode($dataA);
	}
	else
		return json_encode(array('success' => false));
}

function action_delete_column_table($id_cloud, $name_table, $name_column)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	if ($data)
	{
		Sql::query('ALTER TABLE '.$data['uid'].'.'.$name_table.' DROP '.$name_column);
		return json_encode(array('success' => true));
	}
	else
		return json_encode(array('success' => false));
}

function action_get_data_user($key)
{
	$data = Sql::selectOne('users', 'keyApi = ?', $key);
	if ($data)
	{
		return json_encode(array('action' => 'get_data_user', 'success' => true, 'id' => $data['id'], 'login' => $data['pseudo'], 'mail' => $data['mail']));
	}
	else
		return json_encode(array('action' => 'get_data_user', 'success' => false));
}

function action_create_table($id_cloud, $json)
{
	$data = Sql::selectOne('cloud', 'id = ?', $id_cloud);
	if ($data)
	{
		$e = json_decode($json);
		if (count($e->columns) < 1)
			return json_encode(array('success' => false));
		Sql::query('CREATE TABLE '.$data['uid'].'.'.$e->name.' (idazertyuiop INT(6))');
		for ($i = 0; $i < count($e->columns); $i++)
		{
			if ($e->columns[$i]->extra == "AUTO_INCREMENT")
				Sql::query('ALTER TABLE '.$data['uid'].'.'.$e->name.' ADD '.$e->columns[$i]->name.' '.$e->columns[$i]->type.' AUTO_INCREMENT PRIMARY KEY');
			else if ($e->columns[$i]->extra == "NOT NULL")
				Sql::query('ALTER TABLE '.$data['uid'].'.'.$e->name.' ADD '.$e->columns[$i]->name.' '.$e->columns[$i]->type.' NOT NULL');
			else if ($e->columns[$i]->extra == "CURRENT_TIMESTAMP")
				Sql::query('ALTER TABLE '.$data['uid'].'.'.$e->name.' ADD '.$e->columns[$i]->name.' '.$e->columns[$i]->type.' DEFAULT CURRENT_TIMESTAMP');
			else
				Sql::query('ALTER TABLE '.$data['uid'].'.'.$e->name.' ADD '.$e->columns[$i]->name.' '.$e->columns[$i]->type.'');

		}
		Sql::query('ALTER TABLE '.$data['uid'].'.'.$e->name.' DROP idazertyuiop');
		return json_encode(array('success' => true));
	}
	else
		return json_encode(array('success' => false));
}
?>