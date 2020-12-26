package helper

type Response struct {
	Code    int16
	ErrCode int32
	Status  string
	Msg     string
	Data    interface{}
}
