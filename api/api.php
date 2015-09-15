<?php
include('all.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

function get_info_user($id)
{
	echo action_get_info_user($id);
}

function create_cloud($key, $name, $type)
{
	echo action_create_cloud(getId($key), $name, $type);
}

function get_data_user($key)
{
	echo action_get_data_user($key);
}
function get_cloud($key)
{
	echo action_get_cloud(getId($key));
}

function get_cloud_u($key, $id_cloud)
{
	if (read_permission($key, $id_cloud) == true)
		echo action_get_cloud_u($id_cloud);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function get_elements($key, $id_cloud, $id_folder)
{
	if (read_permission($key, $id_cloud))
		echo action_get_element($id_cloud, $id_folder);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}


function create_min_url($url, $type)
{
	echo action_create_min_url($url, $type);
}

function new_folder($key, $id_cloud, $id_parent, $name)
{
	if (write_permission($key, $id_cloud))
		echo action_new_folder(getId($key), $id_cloud, $id_parent, $name, 1);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function connect($login, $password)
{
	echo action_connect($login, $password);
}

function delete_folder($key, $id_cloud, $id_folder)
{
	if (delete_permission($key, $id_cloud))
		echo action_delete_folder($id_folder);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}


function delete_cloud($key, $id_cloud)
{
	if(isCreator($key, $id_cloud))
		action_delete_cloud($id_cloud);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}


function direct_download($uid)
{
	echo action_direct_download($uid);
}

function download($key, $id_file)
{
	$id_cloud = getIdCloud($id_file);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'file not exist'));
	else if (read_permission($key, $id_cloud))
		echo action_download($id_file);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}


function move_folder($key, $id_cloud, $id_folder, $id_parent)
{
	if (write_permission($key, $id_cloud))
		echo action_move_folder($id_folder, $id_parent);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function upload_file($key, $id_cloud, $id_folder, $file, $id)
{
	if (write_permission($key, $id_cloud))
	    echo action_upload_file(getId($key), $id_cloud, $id_folder, $file, $id);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function get_current_upload($key)
{
	echo action_get_current_upload($key);
}

function look_file($key, $id_file)
{
	$id_cloud = getIdCloud($id_file);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'file not exist'));
	else if (read_permission($key, $id_cloud))
		echo action_look_file($id_file);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function delete_file($key, $id_cloud, $id_file)
{
	if (delete_permission($key, $id_cloud))
		echo action_delete_file($id_file);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function read_file($key, $id_file)
{
	$id_cloud = getIdCloud($id_file);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'file not exist'));
	else if (read_permission($key, $id_cloud))
		echo action_read_file($id_file);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function write_file($key, $id_file, $content)
{
	$id_cloud = getIdCloud($id_file);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'file not exist'));
	if (write_permission($key, $id_cloud))
		echo action_write_file($id_file, $content);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function create_file($key, $id_cloud, $id_folder, $name)
{
	if (write_permission($key, $id_cloud))
		echo action_create_file(getId($key), $id_cloud, $id_folder, $name);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function rename_file($key, $id_file, $name)
{
	$id_cloud = getIdCloud($id_file);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'file not exist'));
	else if (write_permission($key, $id_cloud))
		echo action_rename_file($id_file, $name);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function rename_folder($key, $id_folder, $name)
{
	$id_cloud = getIdCloudfolder($id_folder);
	if ($id_cloud == false)
		echo json_encode(array('success' => false, 'error' => 'folder not exist'));
	else if (write_permission($key, $id_cloud))
		echo action_rename_folder($id_folder, $name);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function generate_pdf($key, $id_file)
{

}

function add_user_cloud($key, $id_cloud, $id_user, $write, $read, $delete)
{
}

function delete_user_cloud($key, $id_cloud, $id_user)
{
}

function register($login, $password, $repassword, $mail)
{
	echo action_register($login, $password, $repassword, $mail);
}
function get_table($key, $id_cloud)
{
	if (read_permission($key, $id_cloud))
		echo action_get_table($id_cloud);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}
function get_table_columns($key, $id_cloud, $name_table)
{
	if (read_permission($key, $id_cloud))
		echo action_get_table_columns($id_cloud, $name_table);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}

function get_table_elements($key, $id_cloud, $name_table)
{
	if (read_permission($key, $id_cloud))
		echo action_get_table_elements($id_cloud, $name_table);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}	

function create_table($key, $id_cloud, $json)
{
	if (write_permission($key, $id_cloud))
		echo action_create_table($id_cloud, $json);
	else
		echo json_encode(array('success' => false, 'error' => 'permission incorrect'));
}	
?>