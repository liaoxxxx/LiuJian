package service

import "goApi/internal/app/file_server/payload"

type authService struct {
}

var AuthService authService

func (authService) Login(loginPld payload.AppKeyLogin) {

}

func (authService) Info(loginPld payload.AppKeyLogin) {

}

func (authService) TransToken(loginPld payload.AppKeyLogin) {

}
