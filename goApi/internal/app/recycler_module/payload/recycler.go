package payload

type PasswordLogin struct {
	Phone    string
	Password string
}

type SmsCodeLogin struct {
	Phone   string
	SmsCode string
}
