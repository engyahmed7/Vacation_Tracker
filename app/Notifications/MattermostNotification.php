<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use ThibaudDauce\Mattermost\MattermostChannel;
use ThibaudDauce\Mattermost\Message as MattermostMessage;
use App\Models\VacationRequest;
use App\Enums\StatusEnum;

class MattermostNotification extends Notification
{
    use Queueable;

    public $data;
    public $channel;

    public function __construct( $data,$channel)
    {
        $this->data = $data;
        $this->channel = $channel;
    }

    public function via($notifiable)
    {
        return [MattermostChannel::class];
    }

    public function toMattermost($notifiable)
    {
        $data=$this->data ; 
        $channel=$this->channel;  
        return (new MattermostMessage)
            ->username('VacationRequest')
            ->channel($channel)
            ->iconUrl(url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvhttOrOyKpsmgNy1lcmWr6QJEHhlNDtFWRg&s'))
            ->text($this->data['text'])
            ->attachment(function ($attachment) use ($data) {
                $text = $this->data['attachments'][0]['text'];
                $attachment->authorName($data['attachments'][0]['author_name'])
                    ->title("[Request #{$data['attachments'][0]['title']}]")
                    ->text($text);
            });
        
    }

}