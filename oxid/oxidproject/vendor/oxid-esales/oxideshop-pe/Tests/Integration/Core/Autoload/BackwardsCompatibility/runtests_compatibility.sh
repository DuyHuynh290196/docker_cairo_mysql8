#!/bin/bash
SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
BASEDIR="$(dirname $(dirname $(dirname $(dirname $(dirname $(dirname $(dirname $(dirname "${SCRIPTDIR}"))))))))/"
VENDORDIR="${BASEDIR}vendor/"
TESTDIR="${VENDORDIR}oxid-esales/oxideshop-pe/Tests/"
for file in "${TESTDIR}Integration/Core/Autoload/BackwardsCompatibility/"*Test.php; do
  "${VENDORDIR}bin/runtests" --tap "${file}"
done
