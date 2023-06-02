package utils

import (
	"path/filepath"
	"runtime"
)

func GetCurrentPackageName() string {
	_, filename, _, _ := runtime.Caller(1)
	return filepath.Base(filepath.Dir(filename))
}
