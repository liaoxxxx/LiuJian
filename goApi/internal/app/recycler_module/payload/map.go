package payload

type PathPlanning struct {
	Start Location
	To    Location
}

type Location struct {
	Longitude float64
	Latitude  float64
}
