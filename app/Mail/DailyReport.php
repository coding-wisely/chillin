<?php

namespace App\Mail;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $date)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $totalIncomes = Income::query()
            ->whereBetween('received_at', [
                Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
                Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
            ])
            ->sum('amount');
        $totalExpenses = Expense::query()->whereBetween('spent_at', [
            Carbon::parse($this->date)->startOfDay()->toDateTimeString(),
            Carbon::parse($this->date)->endOfDay()->toDateTimeString(),
        ])->sum('amount');

        $saldo = Number::currency($totalIncomes - $totalExpenses, 'THB');
        return new Content(
            view: 'emails.daily-report',
            with: [
                'date' => $this->date,
                'display_date' => Carbon::parse($this->date)->format('d-m-Y'),
                'totalIncome' => Number::currency($totalIncomes, 'THB'),
                'totalExpenses' => Number::currency($totalExpenses, 'THB'),
                'saldo' => $saldo,
                'image_url' => asset('images/emails/logo.jpg'),
                'url' => url('/'),

            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
