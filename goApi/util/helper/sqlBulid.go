package helper

const (
	FullJoin  = "FULL"
	CrossJoin = "CROSS"
	InnerJoin = "INNER"
	LeftJoin  = "LEFT"
	RightJoin = "RIGHT"
)

type SelectFields struct {
	TableName string
	FieldList []string
}

// @Description 拼接Joins参数的 联表string
// @param mainTableName string  主表名称
// @param joinTaleName  string  副表名称
// @param mainTableCondField string 主表关联字段
// @param joinTableCondField string 副表关联字段
// @param joinType  string 关联类型
// @return string
func JoinTable(mainTableName, joinTaleName, mainTableCondField, joinTableCondField, joinType string) string {
	return joinType + " JOIN `" + joinTaleName + "` ON `" + mainTableName + "`.`" + mainTableCondField + "` = `" + joinTaleName + "`.`" + joinTableCondField + "`  "
}

func SelectFieldsBuild(selectFields ...SelectFields) string {
	var fieldStr string
	for _, sfObj := range selectFields {
		for _, field := range sfObj.FieldList {
			fieldStr += "`" + sfObj.TableName + "`.`" + field + "`,"
		}
	}
	fieldStr = fieldStr[0 : len(fieldStr)-1]
	return fieldStr
}
