﻿<?php
/*Done! Congratulations on your new bot. You will find it at t.me/ya_v_zp_bot. You can now add a description, about section and profile picture for your bot, see /help for a list of commands. By the way, when you've finished creating your cool bot, ping our Bot Support if you want a better username for it. Just make sure the bot is fully operational before you do this.

For a description of the Bot API, see this page: https://core.telegram.org/bots/api
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'parser.php';
include 'api.php';



define('BASE_URL', 'https://api.telegram.org/bot'.TOKEN.'/');

$options=[
	'url'=>'https://slawek.dev/tlgrm/index.php',
		];
$init_msg='Приветствую вас в чат-боте "Я В ЗП"';



function setWebhook($url, $options){
		$method='setWebhook';
		$response=file_get_contents($url.$method.'?'.http_build_query($options));
		var_dump($response);
}

//setWebhook(BASE_URL, $options);



function sendMessage(){
	$method='getUpdates';
	$response=file_get_contents(BASE_URL.$method);
	var_dump($response);
}

//sendMessage();

function ToLogFile($fileName, $body){
	if (isset($fileName) ){
	 	file_put_contents(__DIR__.'/'.$fileName, $body.PHP_EOL.PHP_EOL, FILE_APPEND);
	 	//return $output;
	}
}



function sendRequest($params,$replyMarkup){

	if(!empty($params)){
		
		 //json_decode(file_get_contents(BASE_URL.'sendMessage?'.http_build_query($params)),JSON_OBJECT_AS_ARRAY);
		 
	$raw_query=file_get_contents($url_query=BASE_URL.'sendMessage?'.http_build_query($params).'&reply_markup=' . $replyMarkup);
    //file_put_contents(__DIR__.'/log_array_responses.txt', file_get_contents('php://input'), FILE_APPEND);
    ToLogFile('log_responses.txt',urldecode ($raw_query));
    ToLogFile('log_url.txt',$url_query);
    
	//file_put_contents(__DIR__.'/log_input.txt', file_get_contents('php://input'), FILE_APPEND);
    //file_put_contents(__DIR__.'/log_responses.txt',  $raw_query, FILE_APPEND);
    //file_put_contents(__DIR__.'/log_url.txt', $query);
    
    //echo BASE_URL.'sendMessage?'.http_build_query($params).'&reply_markup=' . $replyMarkup;
    
	}else{
		printf('check parameters');
	}

}



$GLOBALS['updates']=json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
ToLogFile('log_input_stream.txt', file_get_contents('php://input'));
//$GLOBALS ['chat_id']=$updates['message']['chat']['id'];
//ToLogFile('log_id.txt', $chat_id);
$GLOBALS ['text'] = $GLOBALS['updates']["message"]["text"];
//$GLOBALS ['callback_query']=$GLOBALS['updates']["callback_query"]['data'];
//ToLogFile('callback_query', $GLOBALS ['callback_query']);



	//$inline_button1 = array("text"=>"Google url","url"=>"http://google.com");
    //$inline_button2 = array("text"=>"work plz","callback_data"=>'/plz');
    //$inline_keyboard = [[$inline_button1,$inline_button2]];
    //$keyboard=array("inline_keyboard"=>$inline_keyboard,"resize_keyboard" => true);
    //$replyMarkup = json_encode($keyboard, JSON_UNESCAPED_UNICODE); 

	
 	//$keyboard_test=array("inline_keyboard"=>[{"text":" Нажми","callback_data":"\/help"}],'resize_keyboard' => true);
 	//$replyMarkupTest=json_encode($keyboard_test, JSON_UNESCAPED_UNICODE);

function helper_check_id($id){
		return is_numeric($id);
}

function messageIterator($xpathQuery, $url_parse, $chat_id){

		
	if(!empty($xpathQuery)&&!empty($url_parse)&&!empty($chat_id)&&helper_check_id($chat_id)){

		$domObj=domTreeHandler($xpathQuery, $url_parse);

		for ($i=1; $i < count($domObj); $i++) { 
			$replyMarkupInline=array("inline_keyboard"=>array([["text"=>"Выбрать","callback_data"=>$i]]),'resize_keyboard' => true);
			$replyMarkupTest=json_encode($replyMarkupInline, JSON_UNESCAPED_UNICODE);
			sendRequest(['chat_id'=>$chat_id,'text'=>$domObj[$i]], $replyMarkupTest);
			//$updates2=json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);
		    ToLogFile('log_input.txt', file_get_contents('php://input'));
		}

	}else{
		$warning_text='Упс, что-то пошло не так';
		$replyMarkupInline=array("inline_keyboard"=>array([["text"=>"Сообщить","url"=>'mailto:slawek@slawek.dev']]),'resize_keyboard' => true);
		$replyMarkupTest=json_encode($replyMarkupInline, JSON_UNESCAPED_UNICODE);

		$warning_id=(!helper_check_id($GLOBALS['updates']['callback_query']['message']['chat']['id']))?
		$GLOBALS['updates']['message']['chat']['id']:
		$GLOBALS['updates']['callback_query']['message']['chat']['id'];
		sendRequest(['chat_id'=>$warning_id,'text'=>$warning_text], $replyMarkupTest);

		ToLogFile('log_failed_input.txt', $warning_id);
	}
}
echo ";ldd";
switch ($GLOBALS['text']) {
    case '/start':
     	messageIterator('//*[contains(@class,"article-intro")][p]', 'http://health.zp.ua/stomatolohiya', $GLOBALS['updates']['message']['chat']['id']);
		break;
    case '\/help':
     	echo "help";
     	break;
}

if (!empty($GLOBALS['updates']['callback_query']['data'])){
	 $callback=$GLOBALS['updates']['callback_query']['data'];
	 $callback_id=$GLOBALS['updates']['callback_query']['message']['chat']['id'];
	switch ($callback) {
	    case ($callback>0):
	    	
	     	$deep_1=domTreeHandler('(//*[contains(@class,"readmore")]/a/@href)['.$callback.']', 'http://health.zp.ua/stomatolohiya');
	     	$deep_2=domTreeHandler('(//a[contains(@class,"btn-secondary4")]/@href)[1]' ,$temp1='health.zp.ua/stomatolohiya'.$deep_1[0]);
	     	//$deep_3=domTreeHandler('//div[contains(@class,"button-4-gl")]/a/@href', $deep_2);//get doctors appointments
	     	//messageIterator('//div[contains(@class,"button-4-gl")]/a/@href', $deep_2[0], $callback_id);
	     	ToLogFile('log_jyst temportest.txt', json_encode($deep_1).'---'.$deep_2[0].'---'.$callback.'---'.$callback_id.'=----'.$temp1);
			break;
	    case '\/help':
	     	echo "help";
	     	break;
	}
}
 	//var_dump(json_encode($keyboard_test));
 	//var_dump($replyMarkup);
 	//var_dump($keyboard);
 	//var_dump($replyMarkupTest);
	//sendRequest(['chat_id'=>$chat_id,'text'=>domTreeHandler('//*[contains(@class,"article-intro")][p]')], $replyMarkupTest);
//echo '<br>'.$i++.' - '.$nodes_iterator_announce_title=$value->nodeValue; 
unset($updates);
?>


