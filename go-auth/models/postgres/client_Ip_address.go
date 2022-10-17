package postgres

type ClientIpAddress struct {
	Id         uint64  `gorm:"primarykey,column:id"`
	IpAddress  string  `gorm:"column:ip_address"`
	ClientId   uint64  `gorm:"column:client_id"`
	ClientType string  `gorm:"column:client_type"`
	User       *Member `gorm:"foreignKey:ClientId"`
}

func (*ClientIpAddress) TableName() string {
	return "client_ip_address"
}
