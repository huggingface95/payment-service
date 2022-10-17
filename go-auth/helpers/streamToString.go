package helpers

import (
	"bytes"
	"io"
)

func StreamToByte(stream io.Reader) []byte {
	buf := new(bytes.Buffer)
	_, err := buf.ReadFrom(stream)
	if err != nil {
		return nil
	}
	return buf.Bytes()
}

//func StreamToString(stream io.Reader) string {
//	buf := new(bytes.Buffer)
//	_, err := buf.ReadFrom(stream)
//	if err != nil {
//		return ""
//	}
//	return buf.String()
//}
