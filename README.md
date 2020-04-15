# Mailgun plugin for Mautic (AFMailgun)

## Installation

- upload the contents in this repo to mautic instalation `plugins/AFMailgunBundle`
- remove cache `sudo rm -rf app/cache/*`
- go to mautic settings > plugins > click `Install / Upgrade Plugin`
- done.

## Usage

- Choose Mailgun as the mail service, in mautic mail configuration > Email Settings.

Enter yours:
- host: smtp.mailgun.org (non EU) or smtp.eu.mailgun.org (EU)
- username e.g postmaster@mg.yourmailgundomain.com 
- the password (you can get these information on mailgun dashboard)

![image](https://user-images.githubusercontent.com/462477/74548255-cdad8a80-4f4d-11ea-8597-90d3745e9a84.png)

### Add webhook URL to mailgun

Add `https://yourmautic.com/mailer/mailgun/callback` in the mailgun webhook for your selected events:
- permanent failure
- spam complaints
- temporary failure
- unsubscribes

![image](https://user-images.githubusercontent.com/462477/74548580-67753780-4f4e-11ea-8306-6f10fc93353f.png)

Now your mautic will be able to send through mailgun and track email events such as bounce, failed, unsubscribe, spam according to the webhook you set in mailgun.

### Screenshots

![plugin-screen](./Assets/plugin-screen.png)

![stat](./Assets/stat.png)

## Author

Muhammad Azamuddin

mas.azamuddin@gmail.com

https://arrowfunxtion.com
