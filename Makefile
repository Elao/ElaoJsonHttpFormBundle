###########
# Install #
###########

install:
	composer update

########
# Lint #
########

lint: lint.php-cs-fixer lint.phpstan lint.composer

lint.php-cs-fixer:
	vendor/bin/php-cs-fixer fix

lint.phpstan:
	vendor/bin/phpstan analyse .

lint.composer:
	composer validate --strict

############
# Security #
############

security.symfony@integration:
	symfony check:security

########
# Test #
########

## Test
test:
	vendor/bin/simple-phpunit
