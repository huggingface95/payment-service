package clearjunction

import (
	"encoding/json"
	"fmt"
	"github.com/aaapi-net/hog"
)

func (cj *ClearJunction) post(in interface{}, out interface{}, typeName, path string) error {
	data, err := json.Marshal(in)
	if err != nil {
		return err
	}

	return hog.
		Post(fmt.Sprintf("%sv7/%s/%s", cj.config.Url, typeName, path)).
		Headers(cj.getHeaders(string(data))).
		Bytes(data).
		ToStruct(out)
}

func (cj *ClearJunction) get(out interface{}, typeName, path string) (err error) {
	return hog.
		Get(fmt.Sprintf("%sv7/%s/%s", cj.config.Url, typeName, path)).
		Headers(cj.getHeaders("")).
		ToStruct(out)
}
