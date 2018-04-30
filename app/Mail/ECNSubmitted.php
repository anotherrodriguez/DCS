<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ECN;

class ECNSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $ecn;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ECN $ecn)
    {
        //
        $ecn = $ecn->with('change_request','status','user')->where('id','=',$ecn->id)->first();
        $ecn['url'] = action('ECNController@index');
         $this->ecn = $ecn;
    }

    public function display()
    {
        return $this->ecn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Engineering Change Notice: '.$this->ecn->id);
        return $this->markdown('ECNemail');
    }


}
