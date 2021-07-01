package entity

type AdministrationRegion struct {
	RegionCode       int64  `json:"region_code" gorm:"primaryKey;autoIncrement:true"`
	RegionName       string `json:"region_name"`
	RegionLevel      int8   `json:"region_level"`
	ParentRegionCode int64  `json:"parent_region_code"`
}
