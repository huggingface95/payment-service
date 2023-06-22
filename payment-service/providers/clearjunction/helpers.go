package clearjunction

import (
	"fmt"
	"math/rand"
	"time"
)

func GenerateClientOrder() string {
	newRand := rand.New(rand.NewSource(time.Now().UnixNano()))
	part1 := newRand.Intn(900000) + 100000 // Generate random number between 100000 and 999999
	part2 := newRand.Intn(9000) + 1000     // Generate random number between 1000 and 9999
	return fmt.Sprintf("%d-%04d", part1, part2)
}
