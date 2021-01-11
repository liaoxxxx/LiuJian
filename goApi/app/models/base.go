package models

type BaseModel interface {
	TableName() string
	//BuildByPayload(interface{}) interface{}
}
