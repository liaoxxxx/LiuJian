package helper

import "fmt"

const (
	FullJoin  = "FULL"
	CrossJoin = "CROSS"
	InnerJoin = "INNER"
	LeftJoin  = "LEFT"
	RightJoin = "RIGHT"
)

type SelectFields struct {
	TableName string
	fieldList []string
}

func JoinTable(mainTableName, joinTaleName, mainTableCond, joinTableCond, joinType string) string {
	return joinType + " JOIN `" + joinTaleName + "` ON `" + mainTableName + "`.`" + mainTableCond + "` = `" + joinTaleName + "`.`" + joinTableCond + "`  "
}

func SelectFieldsBuild([]SelectFields) {
	for f, _ := range SelectFields {
		fmt.Println(f)
	}
}
