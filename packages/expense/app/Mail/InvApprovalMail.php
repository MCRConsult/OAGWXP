<?php

namespace Packages\expense\app\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $userId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $userId)
    {

        $this->data = $data;
        $this->userId = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            // ->from('test@example.com', 'Test')
            ->subject($this->data['subject'])
            ->view($this->data['view'])
            ->with([
                'receiverNames' => $this->data['receiverNames'],
                'title' =>  $this->data['title'],
                'description' =>  $this->data['description'],
                'reason' =>  $this->data['reason'],
                'requestType' =>  $this->data['requestType'],
                'request' =>  $this->data['request'],
                'userId' => $this->userId,
            ]);
    }
}
