package amap

type GeocodeResp struct {
	Status   string    `json:"status"`
	Info     string    `json:"info"`
	Infocode string    `json:"infocode"`
	Count    string    `json:"count"`
	GeoCodes []GeoCode `json:"geocodes"`
}
type GeoCode struct {
	FormattedAddress string        `json:"formatted_address"`
	Country          string        `json:"country"`
	Province         string        `json:"province"`
	Citycode         string        `json:"citycode"`
	City             string        `json:"city"`
	District         string        `json:"district"`
	Township         []interface{} `json:"township"`
	Neighborhood     Neighborhood  `json:"neighborhood"`
	Building         Building      `json:"building"`
	Adcode           string        `json:"adcode"`
	Street           []string      `json:"street"`
	Number           []string      `json:"number"`
	Location         string        `json:"location"`
	Level            string        `json:"level"`
}

type Building struct {
	Name interface{}
	Type interface{}
}

type Neighborhood struct {
	Name  []string `json:"name"`
	Types []string `json:"type"`
}
