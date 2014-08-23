
#include <string>

#include "mustache/mustache_config.h"
#include "mustache/mustache.hpp"
#include "mustache/data.hpp"

#include "hphp/runtime/ext/extension.h"
#include "hphp/runtime/ext/std/ext_std_variable.h"
#include "hphp/runtime/base/variable-serializer.h"
#include "hphp/runtime/base/variable-unserializer.h"
#include "hphp/runtime/base/builtin-functions.h"
#include "hphp/runtime/ext/ext_closure.h"
#include "hphp/runtime/base/base-includes.h"


namespace HPHP {

namespace {
static void hhvm_array_to_mustache_array(const Variant& data, mustache::Data * node) {
	  mustache::Data * child = NULL;
	DataType key_type;

	switch( data.getType() ) {
	case KindOfNull:
	case KindOfBoolean:
	case KindOfInt64:
	case KindOfDouble:
		//printf("number");
        node->type = mustache::Data::TypeString;
        node->val = new std::string(data.toString().toCppString());
		break;

	case KindOfStaticString:
	case KindOfString: {
		//printf("string");
        node->type = mustache::Data::TypeString;
        node->val = new std::string(data.toString().toCppString());
		break;
	}

	case KindOfArray: {
		//printf("array");

        node->type = mustache::Data::TypeNone;

        std::string ckey;
		Array tmp = data.toArray();
		ssize_t data_count = tmp.length();
		size_t ArrayPos = 0;

	  for (ArrayIter iter(tmp); iter; ++iter) {
		    const Variant& key(iter.first());
		  	const Variant& value(iter.secondRefPlus());

			key_type = key.getType();
			if( key_type == KindOfInt64 ) {
	            if( node->type == mustache::Data::TypeNone ) {
	              node->init(mustache::Data::TypeArray, data_count);
	            } else if( node->type != mustache::Data::TypeArray ) {
	              printf("Mixed numeric and associative arrays are not supported");
	              return; // EXIT
	            }
			} else if( key_type == KindOfStaticString || key_type == KindOfString ) {
	            if( node->type == mustache::Data::TypeNone ) {
	              node->type = mustache::Data::TypeMap;
	            } else if( node->type != mustache::Data::TypeMap ) {
	              printf("Mixed numeric and associative arrays are not supported");
	              return; // EXIT
	            }
			} else {
				printf("Unknown key type: %d", key_type);
				return; // EXIT
			}

			// Store value
			if( node->type == mustache::Data::TypeArray ) {
				child = node->array[ArrayPos++] = new mustache::Data();
				hhvm_array_to_mustache_array(value, child);
			} else if( node->type == mustache::Data::TypeMap ) {
				child = new mustache::Data;
				hhvm_array_to_mustache_array(value, child);
				ckey.assign(key.toString().toCppString());
				node->data.insert(std::pair<std::string,mustache::Data*>(ckey,child));
			} else {
				// Whoops
			}
        }
		break;
	}

	case KindOfObject:
		printf("object");
		break;
	default:
		printf("unknown");
		break;
	}
}
static void mustache_parse_partials_param(const Array& array, mustache::Mustache * mustache,
        mustache::Node::Partials * partials)
{
	std::string ckey;
	std::string tmpl;
	mustache::Node node;

	for (ArrayIter iter(array); iter; ++iter) {
		const Variant& key(iter.first());
		const Variant& value(iter.secondRefPlus());
		if( !key.isString() ) {
			continue;
		}

		// String key, string value
		ckey.assign(key.toString().toCppString());
		tmpl.assign(value.toString().toCppString());
		partials->insert(std::make_pair(ckey, node));
		mustache->tokenize(&tmpl, &(*partials)[ckey]);
	}
}


String HHVM_FUNCTION(mustache_render,
		const String& tmpl, const Variant& data, const Variant& partials) {
	mustache::Mustache mustache;

	// Tokenize
	std::string tmplCppStr = tmpl.toCppString();
	mustache::Node tokenizedTemplate;
	mustache.tokenize(&tmplCppStr, &tokenizedTemplate);

	// Convert data to our format
	mustache::Data mustacheData;
	hhvm_array_to_mustache_array(data, &mustacheData);

	// Convert partials to our format
    mustache::Node::Partials templatePartials;
    if( partials.isArray() ) {
    	Array partialsArr = partials.toArray();
    	mustache_parse_partials_param(partialsArr, &mustache, &templatePartials);
    }

	// Render
	std::string output;
	mustache.render(&tokenizedTemplate, &mustacheData, &templatePartials, &output);

	// Return
	String ret = output;
	return ret;
}
}

namespace {
static class MustacheExtension : public Extension {
 public:
  MustacheExtension() : Extension("mustache") {}

  virtual void moduleInit() {
	HHVM_FE(mustache_render);
    loadSystemlib();
  }
} s_mustache_extension;
}


HHVM_GET_MODULE(mustache)
}
