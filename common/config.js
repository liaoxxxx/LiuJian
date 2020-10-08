let url_config = ""

if(process.env.NODE_ENV === 'development'){
    // 开发环境http://127.0.0.1:8000/
    url_config = 'http://111.229.128.239:1003'
	// url_config = 'http://106.52.104.35'
	// 106.52.104.35
}else{
    // 生产环境http://ddjadmin.allpao.com/
    url_config = 'http://111.229.128.239:1003'
	// url_config = 'http://106.52.104.35'
}

export default url_config
