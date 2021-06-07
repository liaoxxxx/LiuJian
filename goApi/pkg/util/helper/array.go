package helper

func StrArrContains(arr []string, e string) bool {
	for _, str := range arr {
		if str == e {
			return true
		}
	}
	return false
}

func IntArrContains(arr []int, e int) bool {
	for _, n := range arr {
		if n == e {
			return true
		}
	}
	return false
}

/**
 * @Description: 球两个slise的差集
 * @param sourceArr
 * @param compareArr
 * @return []interface{}
 * @return error
 */
func ArrayDiff(sourceArr []interface{}, compareArr ...[]interface{}) ([]interface{}, error) {
	if len(sourceArr) == 0 {
		return []interface{}{}, nil
	}
	if len(sourceArr) > 0 && len(compareArr) == 0 {
		return sourceArr, nil
	}
	var tmp = make(map[interface{}]int, len(sourceArr))
	for _, v := range sourceArr {
		tmp[v] = 1
	}
	for _, param := range compareArr {
		for _, arg := range param {
			if tmp[arg] != 0 {
				tmp[arg]++
			}
		}
	}
	var res = make([]interface{}, 0, len(tmp))
	for k, v := range tmp {
		if v == 1 {
			res = append(res, k)
		}
	}
	return res, nil
}
