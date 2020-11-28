package helper

type Response struct {
	Code   int16
	Status string
	Msg    string
	Data   interface{}
}
