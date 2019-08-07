## Invoice Payments Api

A REST API to transfer funds from the sender's account to receiver's account using PHP framework lumen - https://lumen.laravel.com/

## Pre-requisities
1. PHP >= 7.1.3
2. Install composer https://getcomposer.org/
3. composer global require "laravel/lumen-installer"

Check the laravel 5.8 installation https://laravel.com/docs/5.8

## How to setup?
1. clone the code to your machine
    ```
    git clone git@github.com:jaaic/invoice-payments.git
    ```
2. Add correct MYSQL settings to .env file

3. Install dependencies
    ```
    composer install
    ```

4. Create MYSQL database 
    ```
    CREATE DATABASE billing;
    
    ```  

5. Create tables and seed data for invoices table. We assume invoices are pre generated and inserted in MySQL in the 
   assumed schema.
   
    ```
    php artisan migrate
    
    php artisan db:seed
    
    ``` 
## Question 1:
The design of the system should take into consideration multiple notification channels: SMS /
Email / Push notification.

1. Schedule based notifications for tenants
    The project has 2 commands `generate-paymentReminders` and `trigger-paymentReminders` . 
    These are run daily in the first followed by the second as cron jobs.
    
    `php artisan generate-paymentReminders` inserts early, due and late reminder in database for each unpaid invoice 
    present in invoices table for each channel Email, SMS and push Notification. It creates the correct content of 
    reminders, title and fills in other table columns as well.
     
    `php artisan trigger-paymentReminders` scans the Emails, SMS and Notifications tables and picks the reminders which are 
    not triggered and scheduled to be triggered today(process_at = today). It checks if the invoice is still unpaid and if it
    is, then sends notifications by channels mail, sms and push notifications.
    
    For emails put the correct MAIL_DRIVER in .env file
    SMS is sent via Nexmo in laravel. Create Nexmo account and put correct details in .env file
    Push Notifications are sent by Pusher api. Create a Pusher account and put correct details in .env file.

2. Event based notifications for owners/landlords
    Assume when invoice is paid, `isPaid` column in `invoices` table is set to `true` for the corresponding invoice.
    The `Invoice` eloquent model overrides the boot method and triggers update event called `InvoicePayment`.
    Event listener `SendInvoicePaymentNotification` is configured to listen to this event. It triggers mail, sms, push 
    notification to the owner listed in the invoice.

## Question 2:
Create an API that will return the stats of the account notification the api should return the
following:

● Number of recent notifications

● Number of upcoming notifications ( for the scheduled notification)

The api route is present in routes/web.php
The api takes below query params -
- account_number : required param
- past_days: optional, if not given past 5 days is default
- next_days: optional, is not given next 5 days is default

Start web server or laravel in built server by `php artisan serve`

Invoke api
Pick any account numbers from 'invoices' table,(inserted during seeding), 
example - A101
 
      
`curl -X GET 'http://localhost:8000/notification-stats?account_number=A101&past_days=2&next_days=30'`

Check the response

`
{
    "past": {
        "email": 1,
        "sms": 1,
        "notification": 1,
        "total": 3
    },
    "upcoming": {
        "email": 2,
        "sms": 2,
        "notification": 2,
        "total": 6
    }
}
`
## Question 3:
If the SMS / email provider doesn’t provide stats or data on who opens/ receives notifications.
How would you keep track of this kind of data?

- For emails we can build a email tracker or use some third party email tracker like cloudhq, saleshub. 
  Easier way is to send some tiny image or pixel in the email and when there is a download of this pixel we know that
  the email as been opened.
  
- Tracking who reads/ opens SMS is difficult. However we can send some link in the SMS and track the approximate
  number based on the number of link clicks
  
## Assumptions 
1. There is some job which inserts the invoices in database. The seeder does that task.
2. The commands to generate and trigger reminders can be set up as cron jobs to run at fixed schedule.
3. There is a front end hook to catch the broadcast notifications using Echo
