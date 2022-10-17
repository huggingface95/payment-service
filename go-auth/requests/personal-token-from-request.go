package requests

type RouteJWT struct {
	Token string `uri:"token" binding:"required"`
}

type BearerJWT struct {
	Bearer string `header:"Authorization" binding:"required"`
}

type InputJWT struct {
	Token string `form:"token" binding:"required"`
}
