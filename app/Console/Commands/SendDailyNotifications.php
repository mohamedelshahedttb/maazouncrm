<?php

namespace App\Console\Commands;

use App\Services\WhatsAppBusinessService;
use App\Services\FacebookMessengerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDailyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily appointment notifications to admins and clients';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily notifications...');

        try {
            $whatsappService = app(WhatsAppBusinessService::class);
            $facebookService = app(FacebookMessengerService::class);

            // Send admin notifications
            $this->info('Sending admin notifications...');
            $adminResult = $whatsappService->sendDailyAdminNotifications();
            
            if ($adminResult) {
                $this->info('Admin notifications sent successfully');
            } else {
                $this->warn('Failed to send admin notifications');
            }

            // Send client reminders
            $this->info('Sending client appointment reminders...');
            $clientResult = $whatsappService->sendClientAppointmentReminders();
            
            if ($clientResult) {
                $this->info('Client reminders sent successfully');
            } else {
                $this->warn('Failed to send client reminders');
            }

            // Send Facebook Messenger reminders
            $this->info('Sending Facebook Messenger reminders...');
            $facebookResult = $facebookService->sendClientAppointmentReminders();
            
            if ($facebookResult) {
                $this->info('Facebook Messenger reminders sent successfully');
            } else {
                $this->warn('Failed to send Facebook Messenger reminders');
            }

            $this->info('Daily notifications completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error sending daily notifications: ' . $e->getMessage());
            Log::error('Daily notifications command failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
