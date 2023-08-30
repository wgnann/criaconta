import smtplib
import ssl
from decouple import config
from email.message import EmailMessage

def mail(account, subject, template):
    receiver = account['owner_email']
    server = config('SMTP_SERVER')
    sender = config('MAIL_SENDER')
    smtpuser = config('SMTP_USER')
    smtppass = config('SMTP_PASS')
    content = open(template, 'r').read()

    message = EmailMessage()
    message['Subject'] = subject
    message['From'] = sender
    message['To'] = receiver
    message.set_content(content.format(**account))

    context = ssl.create_default_context()
    with smtplib.SMTP(server, 25) as smtp:
        smtp.starttls(context=context)
        smtp.login(smtpuser, smtppass)
        smtp.send_message(message)
