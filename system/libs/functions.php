<?php
defined('BASE_URL') OR exit('Access denied !!!');

/**
 * Common and most used functions
 */

/**
 * @param $file array, file to be saved on server
 * @param $folder string, path to saved folder on server
 * @param $name string, name of the file on server
 * @param $msg string, base message to notify user after operation
 * @param $types array, list of accepted file extension
 * @param $maxsize number, maximum size of the image
 * @return string msg to notify user
 */
function singleUpload($file, $folder, $name = 'img_', $msg = '', $types = ['.jpg', '.jpeg', '.png', '.svg'], $maxsize = 3) {
    $flag = false;
    $msg = 'Image '.$file['name'].': '.$msg;
    unset($_SESSION['uploaded_name']);
    // verify uploaded file
    if (isset($file['error'], $file['tmp_name']) && $file['error'] == 0 && is_uploaded_file($file['tmp_name'])) {
        // check file size, bsize means byte size
        $bsize = $maxsize * 1024 * 1024;
        if ($file['size'] > 0 && $file['size'] < $bsize) {
            // check file type
            $file_extension = strtolower(substr($file['name'], strrpos($file['name'], '.')));
            if (in_array($file_extension, $types)) {
                $full_name = $name.'-'.time().$file_extension;
                $full_path = $folder.'/'.$full_name;
                // upload image to server
                if (move_uploaded_file($file['tmp_name'], $full_path)) {
                    $msg .= 'Upload successful.';
                    $flag = true;
                    $_SESSION['uploaded_name'] = $full_name;
                } else {
                    $msg .= 'Upload failed.';
                }
            } else {
                $msg .= $file_extension.' is not a valid image file. Please choose file with extension jpg, jpeg, png, and svg.';
            }
        } else {
            $msg .= "File is too large. Max file size is $maxsize MB.";
        }
    } else {
        $msg .= 'Chosen file is invalid!';
    }
    return $flag ? alert_msg($msg, 1) : alert_msg($msg);
}

/**
 * @param $files array of arrays, contains array of uploaded files
 * @param $folder string, stored location on server
 * @param $name string, specific name for file
 * @param $msg string, notify to user
 * @param $types array, list of accepted file types
 * @param $maxsize number, maximum size of each file
 * @return string msg to notify user
 */
function multipleUpload($files, $folder, $name = 'img_', $msg = '', $types = ['.jpg', '.jpeg', '.png', '.svg'], $maxsize = 2)
{
    if (isset($files['name']) && is_array($files['name'])) {
        foreach ($files['name'] as $i => $imgname) {
            $file = [
                'name' => $imgname,
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
                'type' => $files['type'][$i],
            ];
            $msg .= singleUpload($file, $folder, $name.'-'.$i, '', $types, $maxsize);
            $_SESSION['uploaded_names'][$i] = $_SESSION['uploaded_name'];
        }
        unset($_SESSION['uploaded_name']);
    } else {
        $msg .= 'Chosen files are invalid!';
        return alert_msg($msg);
    }
    return $msg;
}

function is_login() {
    return isset($_SESSION['login_status']) && $_SESSION['login_status'];
}

function redirect_to($location, $msg = '') {
    $_SESSION['global_msg'] = $msg;
    header('Location: '.$location);
    exit();
}

function alert_msg($msg, $status = 0) {
    $type = $status == 1 ? 'success' : 'danger';
    return '<div class="alert alert-'.$type.' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>'.$msg.'</strong></div>';
}

/**
 * @param $directory string, path to the director of the images
 * @return array
 */
function getImages($directory) {
    $images = [];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];

    if (is_dir($directory)) {
        $files = scandir($directory);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $directory . '/' . $file;
                if (is_file($path)) {
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    if (in_array($extension, $allowedExtensions)) {
                        $images[] = $path;
                    }
                }
            }
        }
    }

    return $images;
}
function permission_check($permission) : bool {
    if (!is_login()) return false;
    return in_array($permission, explode(',', $_SESSION['permission']));
}

function addComponent(string $component, array $params = []) {
    include 'views/components/'.$component.'.php';
}