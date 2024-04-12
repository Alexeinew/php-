<?php
	#token
	$vk_user_token = "";
	
	#API version
	$v = "5.81";
	
	/*
	#Текст для накрутки
	#Text for promotion
	*/
	$get_me_wall_tex11t = "";
	
	/*
		#Список групп куда постить будет ваш текста
		#List of groups where your text will be posted
	*/
	$gruptp = [
		53294903,
		179585143,
		101712223,
		55161898
	];

	foreach($gruptp as $run){
		curest_get("https://api.vk.com/method/wall.post?owner_id=-".$run."&message=".urlencode($get_me_wall_tex11t)."&from_group=0&signed=0&v=".$v."&access_token=".$vk_user_token);  
		sleep(rand(2,6));
	}
	
	/*
		#Автоматически принимает всех подписчиков в друзей
		#Automatically accepts all subscribers as friends
	*/
	$json_decode = json_decode(curest_get("https://api.vk.com/method/friends.getRequests?v=$v&access_token=".$vk_user_token),true);
	$item = $json_decode['response']['items'];
	
	foreach ($item as $user) 
	{
		$fileget = curest_get("https://api.vk.com/method/friends.add?user_id=".$user."&v=$v&access_token=".$vk_user_token);
		sleep(1);
	}
	
	unset($user);
	
	function curest_get($url)
	{
		$headers = array(
			'cache-control: max-age=0',
			'upgrade-insecure-requests: 1',
			'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
			'sec-fetch-user: ?1',
			'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
			'x-compress: null',
			'sec-fetch-site: none',
			'sec-fetch-mode: navigate',
			'accept-encoding: deflate, br',
			'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
		);
		 
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		 curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$get_me_wall = curl_exec($ch);
		curl_close($ch);
		
		return $get_me_wall;
	}