.SILENT:
.PHONY: test build

###########
# Helpers #
###########

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z\-\_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

###########
# Install #
###########

## Install application
install:
	# Composer
	composer install --verbose

############
# Security #
############

## Run security checks
security:
	symfony check:security

security@test: export APP_ENV = test
security@test: security

########
# Lint #
########

## Run linters
lint: lint.phpcsfixer lint.phpstan lint.composer

lint.phpcsfixer: export PHP_CS_FIXER_IGNORE_ENV = true
lint.phpcsfixer:
	vendor/bin/php-cs-fixer fix --dry-run --no-interaction --diff

lint.phpcsfixer-fix: export PHP_CS_FIXER_IGNORE_ENV = true
lint.phpcsfixer-fix:
	vendor/bin/php-cs-fixer fix

lint.phpstan:
	vendor/bin/phpstan analyse .

lint.composer:
	composer validate --strict

########
# Test #
########

## Run tests
test:
	vendor/bin/simple-phpunit
