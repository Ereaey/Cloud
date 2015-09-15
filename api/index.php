<?php
session_start();
include('api.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['action']))
	return;

$action = $_GET['action'];


if ($action == "get_cloud_u")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];

	get_cloud_u($key, $id_cloud);
}
else if ($action == "get_data_user")
{
	$key = $_GET['key'];

	get_data_user($key);
}
else if ($action == "get_cloud")
{
	$key = $_GET['key'];

	get_cloud($key);
}
else if ($action == "get_elements")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_folder = $_GET['id_folder'];

	get_elements($key, $id_cloud, $id_folder);
}
else if ($action == "delete_folder")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_folder = $_GET['id_folder'];

	delete_folder($key, $id_cloud, $id_folder);
}
else if ($action == "delete_file")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_file = $_GET['id_file'];

	delete_file($key, $id_cloud, $id_file);
}
else if ($action == "create_folder")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_folder = $_GET['id_folder'];
	$name = $_GET['name'];

	new_folder($key, $id_cloud, $id_folder, $name);
}
else if ($action == "create_file")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_folder = $_GET['id_folder'];
	$name = $_GET['name'];

	create_file($key, $id_cloud, $id_folder, $name);
}
else if ($action == "delete_cloud")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];

	delete_cloud($key, $id_cloud);
}
else if ($action == "get_upload")
{
	$id = $_GET['id'];
	
	get_current_upload($id);
}
else if ($action == "upload_file")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$id_folder = $_GET['id_folder'];
	$file = $_FILES['file'];
	$upload_key = $_POST['UPLOAD_PROGRESS_ID'];

	upload_file($key, $id_cloud, $id_folder, $file, $upload_key);
}
else if ($action == "connect")
{
	$login = $_GET['login'];
	$password = $_GET['password'];

	connect($login, $password);
}
else if ($action == "register")
{
	$login = $_GET['login'];
	$password = $_GET['password'];
	$repassword = $_GET['repassword'];
	$mail = $_GET['mail'];


	register($login, $password, $repassword, $mail);
}
else if ($action == "download")
{
	$key = $_GET['key'];
	$id_file = $_GET['id_file'];

	download($key, $id_file);
}
else if ($action == "look_file")
{
	$key = $_GET['key'];
	$id_file = $_GET['id_file'];

	look_file($key, $id_file);
}
else if ($action == "read_file")
{
	$key = $_GET['key'];
	$id_file = $_GET['id_file'];

	read_file($key, $id_file);
}
else if ($action == "write_file")
{
	$key = $_GET['key'];
	$id_file = $_GET['id_file'];
	$content = $_POST['content'];

	write_file($key, $id_file, $content);
}
else if ($action == "create_cloud")
{
	$key = $_GET['key'];
	$name = $_GET['name'];
	$type = $_GET['type'];

	create_cloud($key, $name, $type);
}
else if ($action == "rename_file")
{
	$key = $_GET['key'];
	$name = $_GET['name'];
	$id_file = $_GET['id_file'];
	rename_file($key, $id_file, $name);
}
else if ($action == "rename_folder")
{
	$key = $_GET['key'];
	$name = $_GET['name'];
	$id_folder = $_GET['id_folder'];
	rename_folder($key, $id_folder, $name);
}
else if ($action == "create_min_url")
{
	$url = $_GET['url'];
	$type = $_GET['type'];
	create_min_url($url, $type);
}
else if ($action == "get_table")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	get_table($key, $id_cloud);
}
else if ($action == "get_table_columns")	
{	
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$name_table = $_GET['name_table'];
	get_table_columns($key, $id_cloud, $name_table);
}
else if ($action == "get_elements_table")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$name_table = $_GET['name_table'];
	get_table_elements($key, $id_cloud, $name_table);
}
else if ($action == "create_table")
{
	$key = $_GET['key'];
	$id_cloud = $_GET['id_cloud'];
	$json = $_POST['content'];
	create_table($key, $id_cloud, $json);
}
else if ($action == "direct_download")
{
	$uid = $_GET['uid'];
	direct_download($uid);
}
?>
