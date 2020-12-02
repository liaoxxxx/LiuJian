package cache

import "github.com/go-redis/redis"

var redisInstance *redis.Client

// 初始化连接
func initClient() (err error) {
	redisInstance = redis.NewClient(&redis.Options{
		Addr:     "localhost:6379",
		Password: "", // no password set
		DB:       0,  // use default DB
	})

	_, err = redisInstance.Ping().Result()
	if err != nil {
		return err
	}
	return nil
}
