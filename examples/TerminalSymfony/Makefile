#!/usr/bin/env make

-include .env
export

default: bash

qa_image=jakzal/phpqa:1.60.1
composer_args=--prefer-dist --no-progress --no-interaction --no-suggest

dockerized=docker run --init -it --rm \
	-u $(shell id -u):$(shell id -g) \
	-v ${CORE_DIR}:/core \
	-v ${DATA_BOARD}:/data-board \
	-v ${BASIC_BLOCK}:/basic-block \
	-v ${SYMFONY_DIR}:/symfony \
	-w ${WORKING_DIR}

qa=${dockerized} \
	-e COMPOSER_CACHE_DIR=${COMPOSER_CACHE_DIR} \
	${qa_image}

test:
	${qa} bin/console

bash:
	${qa} bash

composer-update-local:
	${qa} /symfony/packages/Deployment/local-composer.sh config extra.runtime.dotenv_path "../../../../examples/TerminalSymfony/.env"
	${qa} /symfony/packages/Deployment/local-composer.sh update

behat-run:
	${qa} behat run