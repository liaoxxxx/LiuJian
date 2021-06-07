package main

import (
	"goApi/internal/task"
	"goApi/pkg/util"
	"sync"
)

func main() {
	var mainWg sync.WaitGroup
	mainWg.Add(1)
	util.Init()
	task.Init()
}
