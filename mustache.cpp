
#include "mustache.hpp"

#include "hphp/runtime/ext/std/ext_std_variable.h"
#include "hphp/runtime/base/variable-serializer.h"
#include "hphp/runtime/base/variable-unserializer.h"
#include "hphp/runtime/base/builtin-functions.h"
#include "hphp/runtime/ext/ext_closure.h"
#include "hphp/runtime/base/base-includes.h"

namespace HPHP {



















namespace {
static class MustacheExtension : public Extension {
 public:
  MustacheExtension() : Extension("mustache") {}

  virtual void moduleInit() {
    loadSystemlib();
  }
} s_mustache_extension;
}


HHVM_GET_MODULE(mustache)
}
