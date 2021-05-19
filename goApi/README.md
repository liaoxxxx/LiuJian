
### 项目结构遵循规范  `    ` https://github.com/golang-standards/project-layout


### 1.目录结构
```
 |----. internal
 |    | 
 |    | ---.app
 |    |    | 
 |    |    |---. moduleXXX       (应用模块)
 |    |    |    |
 |    |    |    |--- api         (控制器)
 |    |    |    |
 |    |    |    |--- payload     （请求载体） 
 |    |    |    |
 |    |    |    |--- service     （服务） 
 |    |    |    |    
 |    |    |    |--- exports     （输出数据载体）     
 |    |    |
 |    |    |
 |    |    |---. moduleYYYY
 |    |    |
 |    |    |
 |    |    |---. moduleZZZZ
 |    |   
 |    |      
 |    | 
 |    |--- middleware        (中间件)
 |    | 
 |    |--- models           （模型实体）
 |    |
 |    |--- respository      （数据存储）
 |   

 |
 |---. pkg
 |   |
 |   |--- enum           (枚举常量)
 |   |
 |   |--- util           (工具类)
 | 
 |
 |---. router
 |
 |
 |____________
```

### 2.业务流程
```
          
1. -----> 【用户请求】

2. ----->         【middleware  过滤，auth验证等】

3. ----->                 【router  分发路由】 

4. ----->                          【api(context) 控制器】

5. ----->                                 【payload  从context 获取请求载体】 

6. ----->                                          【service(payload)  处理业务逻辑】 

7. ----->                                                 【respository(model)  存取数据】 

8. ----->                                                      【models  映射数据库】 


```