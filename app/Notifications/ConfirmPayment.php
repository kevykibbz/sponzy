<?php

namespace App\Notifications;

use Laravel\Cashier\Payment;
use App\Models\AdminSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmPayment extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The PaymentIntent identifier.
     *
     * @var string
     */
    public $paymentId;

    /**
     * The payment amount.
     *
     * @var string
     */
    public $amount;

    /**
     * Create a new payment confirmation notification.
     *
     * @param  \Laravel\Cashier\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->paymentId = $payment->id;
        $this->amount   = $payment->amount();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $settings = AdminSettings::first();
        $url = route('cashier.payment', ['id' => $this->paymentId]);

        return (new MailMessage)
            ->subject(trans('general.confirm_payment_in', ["app" => $settings->title]))
            ->greeting(trans('general.confirm_amount', ['amount' => $this->amount]))
            ->line(trans('general.confirm_payment_line'))
            ->action(trans('general.confirm_payment'), $url);
    }
}
