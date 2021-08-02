package amap

import (
	"fmt"
	"goApi/configs"
	"goApi/pkg/logger"
	"goApi/pkg/util"
)

// DirectionPlanningElectroBike
/**
 * @Description: 路线规划
 * @receiver at
 * @param geoCodeRespB
 * @return location
 * @return err
 */
func (at *amapTool) DirectionPlanningElectroBike(start, to Location) (res map[string]interface{}, err error) {
	params := map[string]string{
		"key":         configs.AmapAppKey,
		"origin":      fmt.Sprintf("%v,%v", start.Lng, start.Lat),
		"destination": fmt.Sprintf("%v,%v", to.Lng, to.Lat),
	}

	resp, err := util.HttpClient.Get(electrobikePlanningURL, params, map[string]string{})
	logger.Logger.Info(fmt.Sprintf("%v", err))
	logger.Logger.Info(fmt.Sprintf("%v", resp))
	return nil, nil
}
