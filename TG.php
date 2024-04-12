<?php


class TGT
{
	public $botToken; // Токен бота
		
	// Функция для получения и сохранения токена бота
	// Принимает сам токен
	function __construct($token) {
		$this->botToken = $token;
	}
		
		// Функция для отправки простого сообщения с клавиатурой
		// Принимает ID чата, в который отправить, текст, клавиатура (Не обязательно)
		// Пример: sendMessage(123456789, "Hello", ['inline_keyboard' => [[["text" => 'Hello', "callback_data"=>"hello"]]]]);
		// Параметры: если $marmurkup = ["clear"], то клавиатура будет очищена
		function sendMessage($chatId, $text, $murkup=[], $webPagePreview=false) {
			if(!empty($murkup)) {
			if(isset($murkup[0])){
				if($murkup[0] == "clear") {
					$murkup = json_encode(['remove_keyboard' => true]);
				} else {
					$murkup = json_encode($murkup);
				}
			}else {
					$murkup = json_encode($murkup);
				}
			}
			$parameters = [
				"chat_id" 	=> $chatId,
				"text"    	=> $text,
				"reply_markup" => $murkup,
				"parse_mode" => "HTML",
				"disable_web_page_preview" => $webPagePreview
			];
			return $this->sendApiMethod("sendMessage", $parameters);
		}
		
		// Функция для редактирования сообщения
		// Принимает ID чата, ID сообщения, текст, клавиатура (Не обязательно)
		// Пример: editMessageText(123456789, 645, "Новый текст");
		function editMessageText($chatId, $messageId, $text, $murkup=[], $webPagePreview=false) {
		
			$parameters = [
				"chat_id" 	=> $chatId,
				"message_id" 	=> $messageId,
				"text"    	=> $text,
				"reply_markup" => $murkup,
				"parse_mode" => "HTML",
				"disable_web_page_preview" => $webPagePreview
			];
			return $this->sendApiMethod("editMessageText", $parameters);
		}
		
		
		function editMessageFile($chatId, $file) {
			$parameters = [
				'post' => [
					'chat_id' =>  $chatId,
					'document' => curl_file_create($file)
				]
			];	
			return $this->sendApiMethod("sendDocument", $parameters);
		}
		// Метод для удаления сообщения
		// Принимает ID чата и ID сообщения
		// Пример: deleteMessage($chatID, $data->callback_query->message->message_id);
		function deleteMessage($chatId, $messageId) {
			$parameters = [
				"chat_id" 		=> $chatId,
				"message_id"    => $messageId
			];
			return $this->sendApiMethod("deleteMessage", $parameters);
		}
		
		
		// Метод для отправки GIF или H.264/MPEG-4 AVC видео без звука
		// Принимает ID чата и file_id
		// Пример: sendAnimation($chatId, $fileId)
		function sendAnimation($chatId,$messageId, $fileId, $caption="", $murkup=[]) {
			$parameters = [
				"chat_id" => $chatId,
				"parse_mode" => "HTML",
				"message_id" 	=> $messageId,
				"caption" => $caption,
				"animation" => $fileId
			];
			
			if(!empty($murkup)) {
				if($murkup[0] == "clear") {
					$parameters += ['reply_markup' => json_encode(['remove_keyboard' => true])];
				} else {
					$parameters += ['reply_markup' => json_encode($murkup)];
				}
			}
			return $this->sendApiMethod("sendAnimation", $parameters);
		}
		
		
		// Метод для отправки картинок
		// Принимает ID чата, file_id и необязательные описание, клавиатура
		// Пример: sendPhoto($chatId, $fileId)
		function kickChatMember($Group_id, $chat_id) {
			$parameters =[
				'chat_id' => $Group_id, 
				'user_id' => $chat_id
			];
			return $this->sendApiMethod("kickChatMember", $parameters);
		}
		function sendPhoto($chatId, $fileId, $caption="", $murkup=[]) {
			$parameters =[
				"chat_id" => $chatId,
				"parse_mode" => "HTML",
				"caption" => $caption,
				"photo" => $fileId,
			
			];
			
			if(!empty($murkup)) {
				if(isset($murkup[0]) && $murkup[0] == "clear") {
					$parameters += ['reply_markup' => json_encode(['remove_keyboard' => true])];
				} else {
					$parameters += ['reply_markup' => json_encode($murkup)];
				}
			}
			return $this->sendApiMethod("sendPhoto", $parameters);
		}
		function editPhoto($chatId,$messageId, $fileId, $caption="", $murkup=[]) {
			
			$photo = [  
				'type'=> 'photo',
                'media' => $fileId,
                'caption' => $caption,
                'parse_mode' => 'html'
            ];
			$parameters = [
				'chat_id' => $chatId, 
                'message_id' => $messageId,  //id сообщения 
                'media' => json_encode($photo),
				'reply_markup' => $murkup
			];
			
		
			
			return $this->sendApiMethod("editMessageMedia", $parameters);
		}		
		
		
		function getPhoto($chat_id){
			$out = json_decode($this->sendApiMethod("getUserProfilePhotos?user_id=$chat_id", []), TRUE);;
		  		
			if (!$out['result']['photos']['0']['0']['file_id']) // Проверяем, есть ли аватарка у пользователя
			{
				return false;
			}
			$path = $this->getFilePath($out['result']['photos'][0][2]['file_id']);
			
			return 'https://api.telegram.org/file/bot'.$this->botToken.'/'.$path;
		}
		
		function getFilePath($file_id){
			$out = json_decode($this->sendApiMethod("getFile?file_id=$file_id"), TRUE);
			$path = stripslashes($out['result']['file_path']);
			return $path;
		}
		
		
		// Метод редактирования inline_button
		// Принимает ID чата, ID сообщения, новую клавиатуру
		// Пример: editMessageReplyMarkup($chatId, $msgId, $murkup)
		function editMessageReplyMarkup($chatId, $messageId, $murkup) {
			$parameters = [
				"chat_id" => $chatId,
				"message_id" => $messageId
			];
			
			if(!empty($murkup)) {
				if($murkup[0] == "clear") {
					$parameters += ['reply_markup' => json_encode(['remove_keyboard' => true])];
				} else {
					$parameters += ['reply_markup' => json_encode($murkup)];
				}
			}
			return $this->sendApiMethod("editMessageReplyMarkup", $parameters);
		}
		
		
		// Метод для подтверждения inline button
		// Принимает ID inline button
		// Пример: answerCallbackQuery($data->callback_query->id);
		function answerCallbackQuery($inlineButtonId,$text="") {
			if($text != ''){
				$parameters = [
					"callback_query_id" => $inlineButtonId,
					"text" => $text,
					'parse_mode' => 'html',
					"show_alert" => 1,
					"cache_time" => 0
				];
			}else{
				$parameters = [
					"callback_query_id" => $inlineButtonId
				];
			}
			return $this->sendApiMethod("answerCallbackQuery", $parameters);
		}
		
		
		// Системный метод для отправки API метода через CURL
		// Принимает название метода как в документации и массив с параметрами
		// Пример: sendApiMethod("sendMessage", $parameters);
		function sendApiMethod($method, $parameters = []) {
			$ch = curl_init(); 
			$ln = "https://api.telegram.org/bot" . $this->botToken . "/" . $method;
			curl_setopt($ch, CURLOPT_URL, $ln);
			curl_setopt($ch, CURLOPT_POST, true);
		
			if(!isset($parameters['post'])){
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
			}else{
				if(!isset($parameters['get'])){
					curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters['post']);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						"Content-Type:multipart/form-data"
					));
				}
			}
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curlResult = curl_exec($ch);
			curl_close($ch);
			

			return $curlResult;
		}
	}
?>