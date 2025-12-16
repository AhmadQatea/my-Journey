<?php

// app/Mail/TripRejectedMail.php

namespace App\Mail;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;

    public $reason;

    public function __construct(Trip $trip, $reason = null)
    {
        $this->trip = $trip;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('رفض رحلتك: '.$this->trip->title)
            ->markdown('emails.trips.rejected')
            ->with([
                'trip' => $this->trip,
                'reason' => $this->reason,
            ]);
    }
}
