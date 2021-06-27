<?php
$token = "#token";
$admin = "#admin_id";   
$botim = "#bot_username";   ///////*@*Не пиши знак
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".$token."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
$update = json_decode(file_get_contents('php://input'));  
$message = $update->message;
$chat_id = $message->chat->id;
$mid = $message->message_id;
$cid = $message->chat->id;
$uid= $message->from->id;
$ty = $message->chat->type;
$title = $message->chat->title;

/// Чтобы проверить, работает ли бот /////
if($tx == "bot"){
    if($tx = $admin){
bot('deleteMessage',[
'chat_id'=>$message->chat->id,
'text'=>"бот работает",
]);
}
}
if($tx == "Admin" or $tx == "admin"){
    bot('replyMessage',[
'chat_id'=>$message->chat->id,
'text'=>"привет",
]);
}

if(isset($message->new_chat_member) or isset($message->left_chat_member)){
    bot('deleteMessage',[
        'chat_id'=>$message->chat->id,
        'message_id'=>$message->message_id,
    ]);
}
///////Знать команды бота
if($tx=="/start"){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"Этот бот очистит вашу группу от записей!",
'parse_mode'=>"markdown",
'reply_markup' => json_encode([
                'inline_keyboard'=>[
                   [['text'=>"➕ Gruppaga Qoʻshish➕",'url'=>'t.me/$botim?startgroup=new'],
]
]
])
]);
}


/// Gruppaga start
if($ty=="supergroup" or $ty == "group"){
if(strpos($tx == "/start" or $tx=="/start@$botim" ) !==false){
 $cr=bot('getchatmember',[
    'chat_id'=>$cid,
    'user_id'=>$uid
    ]);
$cr = $cr->result->status;
if($cr=="creator"or $cr=="administrator"){    
$yes = file_get_contents("data/gruppalar.dat");

if($yes){
bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"Этот бот был перезапущен в группе $title !",
'parse_mode'=>"markdown"
]);

}else{

bot('sendmessage',[
'chat_id'=>$cid,
'text'=>"Этот бот был перезапущен в группе $title !",
'parse_mode'=>"markdown"
]);
file_put_contents("data/gruppalar.dat","ok");
}
}
}
}







//●●●●●●●●} Members and Group {●●●●●●●●//  
//●●●●●●●●} Statika {●●●●●●●●//  


       $baza = file_get_contents("data/gruppalar.dat"); 
if(mb_stripos($baza, $chat_id) !== false){ 
}else{ 
file_put_contents("data/gruppalar.dat", "$baza\n$chat_id");
} 


 if($callback_data == 'stat') {
     $kun = date('d.m.Y | H:i:s', strtotime('5 hour'));
$baza = file_get_contents("data/gruppalar.dat"); 
$baza1 = substr_count($baza,"\n"); 
$gruppa = substr_count($baza,"-"); 
$odam = $baza1 - $gruppa; 
            
        $text = "Пользователи ботов: \ n
   🌎Все: $base1
   👤Пользователь: $odam
   👥Группа: $gruppa

📆 Последнее обновление: $kun";
                  $res = ('editmessagetext', [
            'chat_id' => $chat_id,
            'message_id' => $mid,
            'text' => $text,
            'parse_mode' => 'markdown',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
       [ ['text' => '♻Обновить♻', 'callback_data' => "stat"] ],
                   
                ]
            ])
        ]);
    }


if($mtext == "/Stat" or $mtext == "/stat"){ 
$baza = file_get_contents("data/gruppalar.dat"); 
$baza1 = substr_count($baza,"\n"); 
$gruppa = substr_count($baza,"-"); 
$odam = $baza1 - $gruppa; 

     ('sendMessage',[ 
     'chat_id'=>$chat_id, 
     'text'=>"Пользователи ботов: \ n
   🌎Все: $base1
   👤Пользователь: $odam
   👥Группа: $gruppa

📆 Последнее обновление: $kun",
     'parse_mode'=>'markdown', 
  'reply_markup'=>json_encode([   
   'inline_keyboard'=>[   
        [['text'=>'♻Обновить♻', 'callback_data' => "/Stat"]],
]   
])   
]); 
} 
