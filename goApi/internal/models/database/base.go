package database

type BaseModel interface {
	TableName() string
	//BuildByPayload(interface{}) interface{}
}
