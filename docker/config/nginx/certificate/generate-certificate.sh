#!/bin/bash

#
# Name of this script
#
me=`basename $0`

#
# Some variables
#
domain=$1
colorGreen='\033[0;32m'
colorStop='\033[0m'

#
# Is name of the project's directory set?
#
if [[ -z "${domain}" ]]; then
    printf "You should pass ${colorGreen}domain name${colorStop} as the first argument. Example: ${colorGreen}./${me} my-domain.com${colorStop}\n"
    exit
fi

#
# Run command
#
openssl \
    req \
    -newkey rsa:2048 \
    -x509 \
    -nodes \
    -keyout private-key.pem \
    -new \
    -out public-certificate.pem \
    -subj '/C=PL/ST=podkarpackie/L=Stalowa Wola/O=Meritoo.pl/CN='${domain} \
    -reqexts SAN \
    -extensions SAN \
    -config <(cat /System/Library/OpenSSL/openssl.cnf <(printf '\n[SAN]\nsubjectAltName=DNS:'${domain})) \
    -sha256 \
    -days 3655
