# Mailgun plugin for Mautic (AFMailgun)

## Installation

- upload the contents in this repo to mautic instalation `plugins/AFMailgunBundle`
- remove cache `sudo rm -rf app/cache/*`
- go to mautic settings > plugins > click `Install / Upgrade Plugin`
- done.

## Usage

- Choose Mailgun as the mail service, in mautic mail configuration > Email Settings.
- Enter your username e.g postmaster@mg.yourmailgundomain.com and the password (you can get these information on mailgun dashboard)
- Save / Apply

### Add webhook URL to mailgun

Add `https://yourmautic.com/mailer/mailgun/callback` in the mailgun webhook for your selected events.

Now your mautic will be able to send through mailgun and track email events such as bounce, failed, unsubscribe, spam according to the webhook you set in mailgun.

### EU Region setup

If you had to select the EU region when creating your Mailgun account, you need to do the following changes:

- edit /Swiftmailer/Transport/MailgunTransport.php
- replace "smtp.mailgun.org" with "smtp.eu.mailgun.org" (as defined in  mailgun.com/app/sending/domains/{YOUR_DOMAIN}/credentials )
- when using the API, replace "api.mailgun.net" with "api.eu.mailgun.net"

### Screenshots

![plugin-screen](./Assets/plugin-screen.png)

![stat](./Assets/stat.png)

## Author

Muhammad Azamuddin

mas.azamuddin@gmail.com

https://arrowfunxtion.com
