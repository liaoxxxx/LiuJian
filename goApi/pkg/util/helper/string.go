package helper

import (
	"crypto/hmac"
	"crypto/md5"
	"crypto/sha1"
	"encoding/hex"
	"fmt"
	"math/rand"
	"reflect"
	"strconv"
	"strings"
	"time"
	"unsafe"
)

// 获取MD5
func MD5(str string) string {
	_md5 := md5.New()
	_md5.Write([]byte(str))
	return hex.EncodeToString(_md5.Sum([]byte(nil)))
}

// 获取SHA1
func SHA1(str string) string {
	_sha1 := sha1.New()
	_sha1.Write([]byte(str))
	return hex.EncodeToString(_sha1.Sum([]byte(nil)))
}

// 获取HMAC
func HMAC(key, data string) string {
	_hmac := hmac.New(md5.New, []byte(key))
	_hmac.Write([]byte(data))
	return hex.EncodeToString(_hmac.Sum([]byte(nil)))
}

// 合并字符串
func StrJoin(sep string, e ...string) string {
	return strings.Join(e, sep)
}

// 判断值是否为空
func IsEmpty(v interface{}) bool {
	value := reflect.ValueOf(v)
	switch value.Kind() {
	case reflect.String:
		return value.Len() == 0
	case reflect.Bool:
		return !value.Bool()
	case reflect.Int, reflect.Int8, reflect.Int16, reflect.Int32, reflect.Int64:
		return value.Int() == 0
	case reflect.Uint, reflect.Uint8, reflect.Uint16, reflect.Uint32, reflect.Uint64, reflect.Uintptr:
		return value.Uint() == 0
	case reflect.Float32, reflect.Float64:
		return value.Float() == 0
	case reflect.Interface, reflect.Ptr:
		return value.IsNil()
	}
	return reflect.DeepEqual(value.Interface(), reflect.Zero(value.Type()).Interface())
}

func parseStr2Int(str string) int64 {
	parseInt, err := strconv.ParseInt(str, 10, 64)
	if err != nil {
		return 0
	}
	return parseInt
}

var r *rand.Rand

func init() {
	r = rand.New(rand.NewSource(time.Now().Unix()))
}

func Str2bytes(s string) []byte {
	x := (*[2]uintptr)(unsafe.Pointer(&s))
	h := [3]uintptr{x[0], x[1], x[1]}
	return *(*[]byte)(unsafe.Pointer(&h))
}

func Bytes2str(b []byte) string {
	return *(*string)(unsafe.Pointer(&b))
}

// RandString 生成随机字符串
func RandString(len int) string {
	bytes := make([]byte, len)
	for i := 0; i < len; i++ {
		b := r.Intn(26) + 65
		bytes[i] = byte(b)
	}
	return string(bytes)
}

// Code 生成6位数随机码-- int  短信
func RandSmsCode() (res string) {
	rand.Seed(time.Now().Unix())
	rnd := rand.New(rand.NewSource(time.Now().Unix()))
	res = fmt.Sprintf("%06v", rnd.Intn(1000000))
	// 可能大家都已经发现了，如果随机生成的Code以0开头就会被阿里去掉，所以要稍微解决一下
	if res[0] == '0' {
		res = RandSmsCode()
	}
	return
}
