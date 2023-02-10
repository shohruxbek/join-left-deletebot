<?php

define('API_KEY', '#'); //Bot tokeni uchun joy
$bot_username = "#";   //*@* sichqoncha belgisisiz joylang


//Pastdagi comment qilingan joyni webhook qilish uchun vaqtinchalik ochib turasiz.
//echo "https://api.telegram.org/bot" . API_KEY . "/setwebhook?url=" . $_SERVER['SERVER_NAME'] . "" . $_SERVER['SCRIPT_NAME'];



function bot($method, $datas = []){

    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $res = curl_exec($ch);
    if (curl_error($ch))
    {
        var_dump(curl_error($ch));
    }
    else
    {
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$message_id = $message->message_id;
$chat_id = $message->chat->id;
$text = $message->text;

if (!$chat_id)
{
    $callback = $update->callback_query;
    $chat_id = $callback->message->chat->id;
    $message_id = $callback->message->message_id;
}

if($text=="/start"){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Ushbu bot guruhda kirdi-chiqdilarni o'chirishga mo'ljallangan",
        'parse_mode'=>"markdown",
        'reply_markup' => json_encode([
            'inline_keyboard'=>[
             [['text'=>"➕ Guruhga qo'shish➕",'url'=>"t.me/$bot_username?startgroup=new"]]
         ]
     ])
    ]);
}

if(isset($message->new_chat_member) or isset($message->left_chat_member)){
    bot('deleteMessage',[
        'chat_id'=>$message->chat->id,
        'message_id'=>$message->message_id,
    ]);
}


if($message->new_chat_participant->username == $bot_username and $message->new_chat_member->username == $bot_username){
    bot('sendmessage',[
        'chat_id'=>$chat_id,
        'text'=>"Iltimos, botni guruhga admin qiling. (Agar admin bo'lmagan bo'lsa!)\n\n Bot guruhda kirdi-chiqdilarni o'chirib turadi...",
    ]);
    if($chat_id){
     $local_database = file_get_contents("saved_groups_id.dat"); 
     if(mb_stripos($local_database, $chat_id) !== false){ 
     }else{ 
        if($chat_id<0){
            file_put_contents("saved_groups_id.dat", "$local_database\n$chat_id");
        }
    } 
}
}

if($message->left_chat_participant->username == $bot_username and $message->left_chat_member->username == $bot_username){

    $local_database = file_get_contents("saved_groups_id.dat"); 
    $local_database = str_replace("\n$chat_id", "", $local_database);
    file_put_contents("saved_groups_id.dat", "$local_database");

}


if($text == "/stat"){

    $date = date('d.m.Y | H:i:s', strtotime('2 hour'));
    $local_database = file_get_contents("saved_groups_id.dat"); 
    $groups_count = substr_count($local_database,"-"); 

    bot('sendMessage',[ 
       'chat_id'=>$chat_id, 
       'text'=>"Statistika: \n
       👥Guruhlar soni: $groups_count ta\n
       📆 Oxirgi yangilanish: $date",
   ]); 
} 
