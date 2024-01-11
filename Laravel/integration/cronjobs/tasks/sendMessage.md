To send messages at scheduled times for different events in Laravel, you can use Laravel's task scheduling along with queues and jobs to manage the sending of messages at specified times. Here's an outline of how you can achieve this:

1. **Set up Task Scheduling:**

   Laravel's task scheduler allows you to define scheduled tasks that will run at specified intervals using Cron expressions. You can define this in the `App\Console\Kernel` class `schedule` method.

   Open the `app\Console\Kernel.php` file and define your scheduled tasks. For example:

   ```php
   protected function schedule(Schedule $schedule)
   {
       // Schedule a job to run every day at a specific time
       $schedule->job(new SendScheduledMessages)->dailyAt('08:00');
   }
   ```

   This example schedules a job (`SendScheduledMessages`) to run every day at 8:00 AM. You'll need to define the `SendScheduledMessages` job next.

2. **Create a Job for Sending Messages:**

   Create a job using the Artisan command:

   ```bash
   php artisan make:job SendScheduledMessages
   ```

   Define the logic for sending messages within this job's `handle()` method. Ensure that it retrieves the events for which messages need to be sent at that specific time.

3. **Handle Message Sending Logic in the Job:**

   Inside the `SendScheduledMessages` job's `handle()` method, retrieve the events for the current scheduled time and send messages accordingly. Here's a basic example:

   ```php
   public function handle()
   {
       // Fetch events that match the current scheduled time
       $events = Event::where('scheduled_time', now())->get();

       foreach ($events as $event) {
           // Send messages for each event
           // Your message sending logic goes here...
           $message = $event->message; // Assuming a message property on the Event model
           // Send $message to the relevant recipients
       }
   }
   ```

4. **Queue the Job for Execution:**

   To ensure the job runs in the background and doesn't block the application, queue it for execution using Laravel's queue system. Make sure your queue driver (like Redis, database, etc.) is configured properly in your `.env` file.

   Inside the `SendScheduledMessages` job's `handle()` method, you might want to dispatch additional jobs for each message to be sent to handle them asynchronously using Laravel's `dispatch()` method.

5. **Running the Scheduler:**

   Finally, to activate the Laravel scheduler, you need to add a cron entry to your server. Run the following command:

   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

   This cron command runs Laravel's scheduler every minute, and Laravel internally handles which scheduled tasks to execute based on their defined schedule.

Ensure that your message sending logic, event scheduling, and queue configuration are set up correctly according to your application's requirements.

Adjust these steps based on your specific application logic, database structure, and the conditions under which you want to send messages for each event.
