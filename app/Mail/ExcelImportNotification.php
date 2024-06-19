<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExcelImportNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $result;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.excel_import_notification')
                    ->subject('Notificación de Importación de Excel');
    }
}
