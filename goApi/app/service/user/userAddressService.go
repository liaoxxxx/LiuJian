package user

import (
	"fmt"
	"github.com/gin-gonic/gin"
	userPld "goApi/app/payload/user"
	"goApi/app/repository"
	"goApi/util/helper"
)

//用户信息
func AddrList(userId int64) helper.Response {
	var userAddrRepo repository.UserAddressRepo
	var dataMap = make(map[string]interface{}, 2)
	var resp helper.Response

	addrList, err := userAddrRepo.AddressList(userId)
	//todo
	if err != nil {
		resp = helper.RespError("111", 222, userId)
	}
	dataMap["addressList"] = addrList
	resp = helper.RespSuccess("222", dataMap)
	return resp
}

func Save(ctx *gin.Context) {

	uid, err := ctx.Get("uid")
	if !err {
		fmt.Println("fmt.Println(err)")
		fmt.Println(err)
	}

	userId := uid.(int64)
	var userAddrAddPld userPld.UAddressAdd
	var userAddrRepo repository.UserAddressRepo
	bindErr := helper.BindQuery(ctx, &userAddrAddPld)
	if bindErr != nil {
		fmt.Println("helper.BindQuery(ctx, &userAddrAddPld)")
		fmt.Println(err)
	}

	uAddrModel := userAddrRepo.BuildByPayload(userAddrAddPld, userId)
	/*fmt.Println("--------- BuildByPayload -----------")
	fmt.Println(uAddrModel)*/

	userAddrRepo.Save(uAddrModel)
}
