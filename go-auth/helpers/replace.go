package helpers

import "strings"

func ReplaceData(content string, data ...string) string {
	replacer := strings.NewReplacer(data...)
	content = replacer.Replace(content)

	return content
}
