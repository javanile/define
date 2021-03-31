#!make

.PHONY: dist
push:
	git config credential.helper 'cache --timeout=3600'
	git pull
	git add .
	git commit -am "push"
	git push

install-dev:
	sudo curl -o /usr/local/bin/box -sL https://github.com/humbug/box/releases/download/3.8.5/box.phar
	sudo chmod +x /usr/local/bin/box

build:
	@docker run --rm -v ${PWD}:/app javanile/lime define.lime > src/GrammarParser.php

dist:
	bash scripts/build.sh
	sudo cp dist/define.phar /usr/local/bin/define
	git add .
	git commit -am "latest release"
	git push

fork:
	curl -sL git.io/fork.sh | bash -

## -------
## Testing
## -------
test-minimal: build
	@php bin/define Example1 --prefix tests/fixtures/minimal-set

test-not-define-input-concept: build
	@php bin/define BadConcept --prefix tests/fixtures/working-set

test-bad-input-concept: build
	@php vendor/bin/pest tests/NotDefinedConceptsTest.php
	@php bin/define RelatedConcept --prefix tests/fixtures/working-set

test-working-set: build
	@php bin/define MainConcept --prefix tests/fixtures/working-set

test-scope-problem-set: build
	@php bin/define MainConcept --prefix tests/fixtures/scope-problem-set

test-build:
	php bin/propan build .
