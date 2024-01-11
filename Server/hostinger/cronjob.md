## To set up a cron job on Hostinger using hPanel, follow these steps:

#### Laravel official documentation: [Task Scheduling](https://laravel.com/docs/10.x/scheduling)

1. Log in to your hPanel and navigate to the Cronjobs section.
![Step 1]( ../hostinger/images/step1.png "step 1")

1. Enter project path and command to run in the corresponding fields. You can also set up the cron job to run at a specific time or date.
![Step 2]( ../hostinger/images/step2.png "step 2")

```bash
# for cpanel - cronjob command
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
```bash
# for hostinger - cronjob command
/project_path/artisan schedule:run
```

![Step 3]( ../hostinger/images/step3.png "step 3")