package pkg

import (
	mail "github.com/xhit/go-simple-mail/v2"
	"jwt-authentication-golang/config"
	"log"
	"time"
)

var MailInstance *mail.SMTPClient
var MailError error

func MailConnect(config *config.EmailConfig) {
	server := mail.NewSMTPClient()

	server.Host = config.Server
	server.Port = config.Port
	server.Username = config.Username
	server.Password = config.Password
	server.Encryption = mail.EncryptionTLS
	// Variable to keep alive connection
	server.KeepAlive = false
	// Timeout for connect to SMTP Server
	server.ConnectTimeout = 10 * time.Second
	// Timeout for send the data and wait respond
	server.SendTimeout = 10 * time.Second

	// SMTP client
	MailInstance, MailError = server.Connect()

	if MailError != nil {
		log.Fatal(MailError)
	}

	log.Println("Mailing system successfully")
}

func Mail(subject string, content string, email string) (err error) {
	newEmail := mail.NewMSG()
	newEmail.
		SetFrom("From Example <test@example.com>").
		AddTo(email).
		SetSubject(subject).
		SetBody(mail.TextHTML, content)

	// always check error after send
	if newEmail.Error != nil {
		err = newEmail.Error
		return
	}

	//Pass the client to the email message to send it
	err = newEmail.Send(MailInstance)
	if err != nil {
		return
	}

	//Get first error
	err = newEmail.GetError()

	return
}