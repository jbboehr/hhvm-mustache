FIND_PATH(MUSTACHE_INCLUDE_DIR NAMES mustache/mustache.hpp PATHS /usr/include /usr/local/include)
FIND_LIBRARY(MUSTACHE_LIBRARY NAMES mustache PATHS /lib /usr/lib /usr/local/lib)

IF (MUSTACHE_INCLUDE_DIR AND MUSTACHE_LIBRARY)
    MESSAGE(STATUS "mustache Include dir: ${MUSTACHE_INCLUDE_DIR}")
    MESSAGE(STATUS "mustache library: ${MUSTACHE_LIBRARY}")
ELSE()
    MESSAGE(FATAL_ERROR "Cannot find mustache library")
ENDIF()

INCLUDE_DIRECTORIES(${MUSTACHE_INCLUDE_DIR})

SET(CMAKE_CXX_FLAGS_DEBUG "-g -pg -O0")

HHVM_EXTENSION(mustache mustache.cpp)
HHVM_SYSTEMLIB(mustache ext_mustache.php)

TARGET_LINK_LIBRARIES(mustache ${MUSTACHE_LIBRARY})
