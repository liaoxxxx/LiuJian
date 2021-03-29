
### 项目结构遵循规范  `    ` https://github.com/golang-standards/project-layout


### 1.目录结构
```
 app
 |---.
 |   |--- contoller         (控制器)
 |   |
 |   |--- enum              (枚举常量)
 |   |
 |   |--- middleware        (中间件)
 |   |
 |   |--- models           （模型实体）
 |   |
 |   |--- payload          （请求载体）    
 |   |
 |   |--- respository      （数据存储）
 |   |
 |   |--- service          （业务逻辑）
 |
 |--- router
 |
 |____________
```

### 2.业务流程
```
          
1. -----> 【用户请求】

2. ----->         【middleware  过滤，auth验证等】

3. ----->                 【router  分发路由】 

4. ----->                          【contoller(context) 控制器】

5. ----->                                 【payload  从context 获取请求载体】 

6. ----->                                          【service(payload)  处理业务逻辑】 

7. ----->                                                 【respository(model)  存取数据】 

8. ----->                                                      【models  映射数据库】 


```