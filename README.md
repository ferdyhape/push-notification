# Simple Set Push Notification

## Description

Simple Set Push Notification is a lightweight web tool for enabling, managing, and sending browser push notifications using Service Workers and VAPID keys.
This tool includes:

- A subscription page where users can enable browser notifications
- A send form to broadcast custom push messages
- A quick-send API endpoint
- A subscriber viewer for inspecting saved subscriptions
- Full integration with PHP backend and Service Worker (worker.js)

## Features

- Enable browser push notification
- Save user subscription to JSON storage
- Send push messages (title, message, image, icon)
- Quick send endpoint
- View all registered subscribers

## Built With

- PHP (server)
- HTML + Bootstrap 5 (UI)
- JavaScript + jQuery
- Service Worker for Push API

## How to Use

1. Update .env file

   Copy .env.example to .env

   ```
   VAPID_SUBJECT=mailto:your_email@mail.com
   VAPID_PUBLIC_KEY=<<VAPID_PUBLIC_KEY>>
   VAPID_PRIVATE_KEY=<<VAPID_PRIVATE_KEY>>
   BASE_URL=http://localhost:8000
   ENVIRONMENT=local
   ```

   You can get VAPID_PUBLIC_KEY and VAPID_PRIVATE_KEY from [https://vapidkeys.com/](https://vapidkeys.com/)

   Make sure:

   - The VAPID key pair is valid (generated using web-push tools or online generators)
   - BASE_URL refers to where your project is hosted
   - ENVIRONMENT is set to local or production as needed

2. Start a Local Server

   You can use any static server, for example:

   ```
   php -S localhost:8000
   ```

   or using XAMPP / Nginx / Apache.
   Ensure the project root is accessible via the same BASE_URL.

3. Load the Subscription Page
   Open in your browser

   ```
   http://localhost:8000
   ```

   Then:

   - Click Enable Notifications
   - Allow the browser permission prompt
   - Subscription data will be saved to storage/subscription.json

4. Send Push Notifications

   There are two ways to send notifications:

   A. Using the Send Form

   Open:

   ```
   http://localhost:8000/send_form.html
   ```

   Fill out:

   - Notification Title
   - Message Body
   - Image URL (optional)
   - Icon URL (optional)

   Click Send Notification → All subscribers will receive it.

   B. Using Quick Send Endpoint
   Call this URL in your browser:

   ```
   http://localhost:8000/api/send_push.php
   ```

   This sends a default test message immediately.

5. View All Subscribers

   Open the subscriber viewer page:

   ```
   http://localhost:8000/api/subscription_viewer.php
   ```

   You can inspect:

   - Endpoints
   - p256dh keys
   - auth keys

   Useful for debugging or verifying successful subscriptions.

6. Important Notes
   - Service Workers require HTTPS in production
   - Localhost is allowed without HTTPS
   - Chrome, Edge, and Firefox are supported
   - Make sure worker.js is placed in root (/) to have correct scope
