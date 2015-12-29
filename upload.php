<?php
/**
 * 使用curl提交图片到zimg服务器上
 */
$img_host = 'http://127.0.0.1:4869'; //图片服务器

$zimg_upload_url = $img_host.'/upload';

// 上传图片到zimg图片存储服务
foreach ($_FILES as  $file) {
	$ch = curl_init();

	// 关键在这里！
	$post_data = file_get_contents($file['tmp_name']); // raw_post方式

	$ext = get_file_ext($file['name']);
	$headers = array();
	$headers[] = 'Content-Type:'.$ext; // 还有这里！

	curl_setopt($ch, CURLOPT_URL, $zimg_upload_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
	curl_setopt($ch, CURLOPT_POST, true);  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

	$info = curl_exec($ch);
	curl_close($ch);

	//var_dump($info);
	$json = json_decode($info, true);
	$signature = $json['info']['md5'];

	echo 'The picture path :'.$signature;
}

function get_file_ext($file) {
	$info = pathinfo($file);
	return $info['extension'];
}

