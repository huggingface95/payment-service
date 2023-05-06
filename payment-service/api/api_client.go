package api

import (
	"fmt"
	"github.com/gofiber/fiber/v2"
)

type RequestParams map[string]interface{}

func request(c *fiber.Ctx, endpoint string, params RequestParams) error {
	switch c.Method() {
	case fiber.MethodGet:
		for key, value := range params {
			c.Query(fmt.Sprintf("%v", key), fmt.Sprintf("%v", value))
		}
	case fiber.MethodPost, fiber.MethodPut, fiber.MethodPatch:
		if err := c.BodyParser(&params); err != nil {
			return err
		}
	default:
		return fmt.Errorf("unsupported HTTP method: %s", c.Method())
	}

	return c.JSON(fiber.Map{
		"endpoint": endpoint,
		"params":   params,
	})
}
