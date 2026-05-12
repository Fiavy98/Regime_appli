#!/bin/bash

# Test 1: Login avec les identifiants admin
echo "=== Test 1: Connexion admin ==="
COOKIE_JAR=$(mktemp)
curl -c "$COOKIE_JAR" -d "email=admin@gmail.com&password=admin123456" \
  http://localhost:8080/index.php/login -s -L > /dev/null

# Test 2: Accès au dashboard admin avec la session
echo "=== Test 2: Accès au dashboard admin ==="
curl -b "$COOKIE_JAR" http://localhost:8080/index.php/admin -s | head -100

# Nettoyer
rm "$COOKIE_JAR"
