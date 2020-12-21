package helper

type Response struct {
	Code   int16
	ErrMsg string
	Status string
	Msg    string
	Data   interface{}
}
