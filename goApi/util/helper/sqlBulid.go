package helper

const (
	FullJoin  = "FULL"
	CrossJoin = "CROSS"
	InnerJoin = "INNER"
	LeftJoin  = "LEFT"
	RightJoin = "RIGHT"
)

func JoinTable(mainTableName, joinTaleName, mainTableCond, joinTableCond, joinType string) string {
	return joinType + " JOIN " + joinTaleName + " ON " + mainTableName + "." + mainTableCond + " = " + joinTaleName + "." + joinTableCond + ""
}
