let url_config = "http://localhost:8008"

if(process.env.NODE_ENV === 'development'){
    // 开发环境http://127.0.0.1:8000/
    url_config = 'http://localhost:8008'
	
}else{
    
    url_config = 'http://localhost:8008'
}

export default url_config
