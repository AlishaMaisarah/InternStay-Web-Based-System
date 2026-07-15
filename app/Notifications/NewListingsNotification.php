<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NewListingsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Collection $internships,
        public Collection $rentals
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('🎯 New Listings Matching Your Preferences - InternStay')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Great news! We found new listings that match your preferences:');
        
        if ($this->internships->isNotEmpty()) {
            $message->line("### 💼 {$this->internships->count()} New Internship" . ($this->internships->count() > 1 ? 's' : ''));
            
            foreach ($this->internships->take(5) as $internship) {
                $message->line("**{$internship->internship_name}**  \n📍 {$internship->company} • {$internship->location}  \n🏭 {$internship->industry}");
            }
            
            if ($this->internships->count() > 5) {
                $message->line("*...and " . ($this->internships->count() - 5) . " more!*");
            }
            
            $message->action('View All Internships', url('/internships'));
        }
        
        if ($this->rentals->isNotEmpty()) {
            $message->line("### 🏠 {$this->rentals->count()} New Rental" . ($this->rentals->count() > 1 ? 's' : ''));
            
            foreach ($this->rentals->take(5) as $rental) {
                $message->line("**{$rental->property_name}**  \n💰 RM {$rental->price}/month • {$rental->property_type}  \n📍 {$rental->address}");
            }
            
            if ($this->rentals->count() > 5) {
                $message->line("*...and " . ($this->rentals->count() - 5) . " more!*");
            }
            
            $message->action('View All Rentals', url('/accommodation'));
        }
        
        $message->line('---')
            ->line('💡 **Tip:** You can update your notification preferences anytime in your profile settings.')
            ->salutation('Happy hunting! 🚀  
The InternStay Team');
        
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'internships_count' => $this->internships->count(),
            'rentals_count' => $this->rentals->count(),
        ];
    }
}

