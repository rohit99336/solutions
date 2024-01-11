To send logic at scheduled times for different events in Laravel, you can use Laravel's task scheduling along with queues and jobs to manage the sending of logic at specified times. Here's an outline of how you can achieve this:

1. **Create a Job for your task:**

   Create a job using the Artisan command:

   ```bash
   php artisan make:job YourTask
   ```

   Define the logic for your task within this job's `handle()` method. Ensure that it retrieves the events for which logic need to be sent at that specific time.

2. **Set up Task Scheduling:**

   Laravel's task scheduler allows you to define scheduled tasks that will run at specified intervals using Cron expressions. You can define this in the `App\Console\Kernel` class `schedule` method.

   Open the `app\Console\Kernel.php` file and define your scheduled tasks. For example:

   ```php
   protected function schedule(Schedule $schedule)
   {
       // Schedule a job to run every day at a specific time
       $schedule->job(new YourTask)->dailyAt('08:00');
   }
   ```

   This example schedules a job (`YourTask`) to run every day at 8:00 AM. You'll need to define the `YourTask` job next.

3. **Handle Message Sending Logic in the Job:**

   Inside the `YourTask` job's `handle()` method, retrieve the events for the current scheduled time and working accordingly your task or job.

   ```php
   public function handle()
   {
       // Your task or job logic goes here...
   }
   ```

4. **Queue the Job for Execution:**

   To ensure the job runs in the background and doesn't block the application, queue it for execution using Laravel's queue system. Make sure your queue driver (like Redis, database, etc.) is configured properly in your `.env` file.

   Inside the `YourTask` job's `handle()` method, you might want to dispatch additional jobs for your logic to handle them asynchronously using Laravel's `dispatch()` method.

5. **Running the Scheduler:**

   Finally, to activate the Laravel scheduler, you need to add a cron entry to your server. Run the following command:

   ```bash
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
   ```

   This cron command runs Laravel's scheduler every minute, and Laravel internally handles which scheduled tasks to execute based on their defined schedule.
