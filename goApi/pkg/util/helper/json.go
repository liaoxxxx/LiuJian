package helper

import (
	"encoding/json"
)

func JsonMarshal(v interface{}) string {
	bytes, _ := json.Marshal(v)
	/*if err != nil {
		logger.Logger.Error("json序列化：", zap.Error(err))
	}*/
	return Bytes2str(bytes)
}
