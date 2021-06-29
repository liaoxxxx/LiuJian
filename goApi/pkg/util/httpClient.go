package util

import (
	"github.com/go-resty/resty/v2"
)

type httpClient struct {

	//client *resty.Client //连接存储的chan
}

var HttpClient httpClient

func (hc *httpClient) Get(url string, params map[string]string, headers map[string]string) (*resty.Response, error) {
	client := resty.New()
	resp, err := client.R().
		SetHeaders(headers).
		SetQueryParams(params).
		Get(url)
	return resp, err
}

/*
type httpclientPool struct {
	mutex sync.Mutex // 保证多个goroutine访问时候，closed的线程安全
	res chan *resty.Client //连接存储的chan
	factory func() (*resty.Client,error) //新建连接的工厂方法
	closed bool //连接池关闭标志
}


type httpClient struct {
	m sync.Mutex // 保证多个goroutine访问时候，closed的线程安全
	clientPool chan *resty.Client //连接存储的chan
	factory func() (*resty.Client,error) //新建连接的工厂方法
	closed bool //连接池关闭标志
}





func New(fn func() (*resty.Client, error), size uint) (*httpclientPool, error) {
	if size <= 0 {
		return nil, errors.New("size的值太小了。")
	}
	return &httpclientPool{
		factory: fn,
		res:     make(chan *resty.Client, size),
	}, nil
}






func (p *httpclientPool) Acquire() (*resty.Client,error) {

	select {
	case r,ok := <-p.res:
		log.Println("Acquire:共享资源")
		if !ok {
			return nil,fmt.Errorf("获取http客户端失败")
		}
		return r,nil
	default:
		log.Println("Acquire:新生成资源")
		return p.factory()
	}

}

func (p *httpclientPool) Close() {
	p.mutex.Lock()
	defer p.mutex.Unlock()

	if p.closed {
		return
	}

	p.closed = true

	//关闭通道，不让写入了
	close(p.res)

	//关闭通道里的资源
	for r:=range p.res {
		r.GetClient().CloseIdleConnections()
	}
}

func (p *httpclientPool) Release(r *resty.Client){
	//保证该操作和Close方法的操作是安全的
	p.mutex.Lock()
	defer p.mutex.Unlock()

	//资源池都关闭了，就省这一个没有释放的资源了，释放即可
	if p.closed {
		err := r.GetClient().CloseIdleConnections()
		if err != nil {
			return
		}
		return
	}

	select {
	case p.res <- r:
		log.Println("资源释放到池子里了")
	default:
		log.Println("资源池满了，释放这个资源吧")
		err := r.GetClient().
		if err != nil {
			return
		}
	}
}

*/
