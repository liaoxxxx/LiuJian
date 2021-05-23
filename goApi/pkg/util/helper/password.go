package helper

/**
获取md5加密后的密码
*/
func GetMd5Pwd(password string, salt string) (md5Pwd string) {
	return MD5(password + salt)
}
